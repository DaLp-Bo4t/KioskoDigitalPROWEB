<?php

namespace App\Controllers;



class Order extends BaseController
{
    public function cart()
    {
        $session = session();
        $cart = $session->get('cart') ?? [];
        
        // Sanitize cart: Fix items from Kiosko needing 'qty'
        $cartDirty = false;
        foreach ($cart as &$item) {
            if (!isset($item['qty'])) {
                $item['qty'] = isset($item['quantity']) ? $item['quantity'] : 1;
                $cartDirty = true;
            }
        }
        unset($item); // Break the reference with the last element
        if ($cartDirty) {
            $session->set('cart', $cart);
        }

        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }



        $data = [
            'cart_items' => $cart,
            'total' => $total,

        ];
        return view('order/cart', $data);
    }

    public function add()
    {
        if (!session()->has('user')) {
            return redirect()->to('user/login')->with('error', 'Debes iniciar sesión para agregar productos.');
        }

        $session = session();
        $cart = $session->get('cart') ?? [];
        
        $extras = $this->request->getPost('extras') ?? [];
        
        $newItem = [
            'name' => $this->request->getPost('name'),
            'price' => (float) $this->request->getPost('price'), // Includes extras cost calculated in front-end
            'qty' => (int) $this->request->getPost('qty'),
            'extras' => $extras, // Store list of selected ingredients
            'image' => $this->request->getPost('image')
        ];

        // For simplicity in this demo, we won't merge items with different extras.
        // We'll just append them as new items.
        // Merging logic would require comparing the 'extras' arrays.
        
        $cart[] = $newItem;

        $session->set('cart', $cart);
        
        return redirect()->to('menu')->with('message', 'Producto agregado al carrito');
    }

    public function update_qty()
    {
        $session = session();
        $cart = $session->get('cart') ?? [];
        $index = $this->request->getPost('index');
        $qty = (int) $this->request->getPost('qty');

        if (isset($cart[$index]) && $qty > 0) {
            $cart[$index]['qty'] = $qty;
            $session->set('cart', $cart);
            return $this->response->setJSON(['status' => 'success', 'total' => $this->calculateTotal($cart)]);
        }

        return $this->response->setJSON(['status' => 'error']);
    }

    public function remove_item()
    {
        $session = session();
        $cart = $session->get('cart') ?? [];
        $index = $this->request->getPost('index');

        if (isset($cart[$index])) {
            array_splice($cart, $index, 1); // Remove and reindex
            $session->set('cart', $cart);
            return $this->response->setJSON(['status' => 'success', 'total' => $this->calculateTotal($cart), 'count' => count($cart)]);
        }

        return $this->response->setJSON(['status' => 'error']);
    }

    private function calculateTotal($cart) {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return number_format($total, 2);
    }

    private function getExchangeRate() {
        $session = session();
        
        // Return cached rate if less than 1 hour old
        if ($session->has('exchange_rate') && $session->has('rate_timestamp')) {
            if (time() - $session->get('rate_timestamp') < 3600) {
                return $session->get('exchange_rate');
            }
        }

        try {
            // Fetch from API
            $json = file_get_contents('https://ve.dolarapi.com/v1/dolares/oficial');
            $data = json_decode($json, true);
            
            if (isset($data['promedio'])) {
                $rate = $data['promedio'];
                $session->set('exchange_rate', $rate);
                $session->set('rate_timestamp', time());
                return $rate;
            }
        } catch (\Exception $e) {
            // Log error if needed
        }

        // Fallback to previous cached rate or default
        return $session->get('exchange_rate') ?? 60.00; 
    }

    public function checkout()
    {
        if (!session()->has('user')) {
            return redirect()->to('user/login')->with('error', 'Debes iniciar sesión para realizar el pedido.');
        }

        $session = session();
        $cart = $session->get('cart') ?? [];

        if (empty($cart)) {
            return redirect()->to('menu');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $exchangeRate = $this->getExchangeRate();
        $totalVES = $total * $exchangeRate;

        $data = [
            'cart' => $cart,
            'total' => $total,
            'totalVES' => $totalVES,
            'exchangeRate' => $exchangeRate
        ];

        return view('order/checkout', $data);
    }

    public function process_payment()
    {
        $paymentMethod = $this->request->getPost('payment_method');
        $session = session();
        
        if (!$paymentMethod) {
             return redirect()->back()->with('error', 'Por favor selecciona un método de pago.');
        }

        if ($paymentMethod === 'zelle') {
             $holder = $this->request->getPost('zelle_holder');
             $reference = $this->request->getPost('zelle_reference');
             
             if (empty($holder) || empty($reference)) {
                 return redirect()->back()->with('error', 'Para pagos con Zelle, debes ingresar el titular y la referencia.');
             }
             $session->set('payment_details', ['type' => 'zelle', 'holder' => $holder, 'reference' => $reference]);

        } elseif ($paymentMethod === 'pago_movil') {
            $reference = $this->request->getPost('pm_reference');
            if (empty($reference)) {
                return redirect()->back()->with('error', 'Por favor ingresa el número de referencia del Pago Móvil.');
            }
            $session->set('payment_details', ['type' => 'pago_movil', 'reference' => $reference]);
        }

        // ... existing payment validation ...
        
        // ... existing payment validation ...
        
        // Calculate totals for invoice
        $cart = $session->get('cart') ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $user = $session->get('user');
        
        // Save Order to DB
        $orderModel = new \App\Models\OrderModel();
        $orderData = [
            'user_id' => $user['id'], // Assuming user is logged in and has ID
            'total' => $total,
            'status' => 'pending',
            'address_json' => $this->request->getPost('delivery_address') ?: ($user['address'] ?? ''),
            'payment_info' => json_encode($session->get('payment_details'))
        ];
        
        $orderId = $orderModel->insert($orderData);
        
        // Save Order Items
        $orderItemModel = new \App\Models\OrderItemModel();
        foreach ($cart as $item) {
            $orderItemModel->insert([
                'order_id' => $orderId,
                'product_name' => $item['name'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'extras' => json_encode($item['extras'] ?? [])
            ]);
        }

        // Prepare data for session/confirmation
        $lastOrder = $orderData;
        $lastOrder['id'] = $orderId;
        $lastOrder['cart'] = $cart;
        $lastOrder['date'] = date('Y-m-d H:i:s');
        $lastOrder['payment'] = $session->get('payment_details');
        
        $session->set('last_order', $lastOrder);

        // Clear cart
        $session->remove('cart');
        // Keep payment details for a moment if needed, or clear them too. 
        // We saved them in last_order so we can clear session specific ones if we want.

        return redirect()->to('order/confirmation')->with('success', '¡Pedido confirmado exitosamente!');
    }

    public function invoice($orderId = null)
    {
        $session = session();
        
        if ($orderId) {
            $orderModel = new \App\Models\OrderModel();
            $order = $orderModel->find($orderId);
            
            if ($order) {
                // Fetch items
                $orderItemModel = new \App\Models\OrderItemModel();
                $items = $orderItemModel->where('order_id', $orderId)->findAll();
                
                // Reconstruct cart format from items for the view
                $cart = [];
                foreach ($items as $item) {
                     $cart[] = [
                         'name' => $item['product_name'],
                         'qty' => $item['quantity'],
                         'price' => $item['price'],
                         'extras' => json_decode($item['extras'], true)
                     ];
                }
                
                $order['cart'] = $cart;
                $order['date'] = $order['created_at'];
                $order['order_id'] = $order['id']; // Map ID to order_id expected by view
                                // Payment info is stored as JSON string in DB
                $order['payment'] = json_decode($order['payment_info'], true);
                // Fetch User
                $userModel = new \App\Models\UserModel();
                $order['user'] = $userModel->find($order['user_id']);
                
                $data = ['order' => $order];
                return view('order/invoice', $data);
            }
        }

        // Fallback to session
        if (!$session->has('last_order')) {
            return redirect()->to('/')->with('error', 'No hay factura disponible.');
        }

        $order = $session->get('last_order');
        
        // Ensure order_id is set
        if (!isset($order['order_id']) && isset($order['id'])) {
            $order['order_id'] = $order['id'];
        }

        // Ensure user is set if logged in
        if (!isset($order['user']) && isset($order['user_id'])) {
            $userModel = new \App\Models\UserModel();
            $order['user'] = $userModel->find($order['user_id']);
        }

        $data = ['order' => $order];

        return view('order/invoice', $data);
    }

    public function pay()
    {
        // Deprecated: redirect to checkout
        return redirect()->to('order/checkout');
    }

    public function confirmation()
    {
        $session = session();
        $data = [
            'order' => $session->get('last_order')
        ];
        return view('order/confirmation', $data);
    }

    public function submit_review()
    {
        $session = session();
        $user = $session->get('user');
        
        $reviewModel = new \App\Models\ReviewModel();
        
        $newReview = [
            'product_id' => null, // General review
            'user_id' => $user['id'] ?? null,
            'user_name' => $user['name'] ?? 'Cliente Feliz',
            'rating' => (int) $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment')
        ];

        $reviewModel->save($newReview);

        return redirect()->to('/#reviews')->with('message', '¡Gracias por tu reseña!');
    }

    public function calculate_delivery()
{
    if (!session()->has('user')) {
         return $this->response->setJSON(['status' => 'error', 'message' => 'Usuario no identificado']);
    }

    $user = session()->get('user');
    
    // Try to get address from POST (if multiple addresses supported later) or fallback to user profile
    $destination = $this->request->getPost('address');
    
    // Get coordinates if provided (from Google Maps link detection)
    $destLat = $this->request->getPost('lat');
    $destLng = $this->request->getPost('lng');
    $mapsLink = $this->request->getPost('maps_link');
    
    // Build destination coords override if we have valid coordinates
    $destCoordsOverride = null;
    if (!empty($destLat) && !empty($destLng) && is_numeric($destLat) && is_numeric($destLng)) {
        $destCoordsOverride = [
            'lat' => floatval($destLat),
            'lng' => floatval($destLng)
        ];
    }
    
    if (!$destination && !$destCoordsOverride) {
         // Fallback to user's main address
         $destination = $user['address'] ?? '';
         // Or check addresses array
         if (empty($destination) && !empty($user['addresses'])) {
             $destination = $user['addresses'][0]['address'];
         }
    }

    // If we have coordinates override, we can use a placeholder for the destination text
    if (empty($destination) && $destCoordsOverride) {
        $destination = "Ubicación de Google Maps ({$destCoordsOverride['lat']}, {$destCoordsOverride['lng']})";
    }

    if (empty($destination) && !$destCoordsOverride) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'No se encontró dirección de entrega']);
    }

    $origin = getenv('STORE_ADDRESS');
    if (empty($origin)) {
        $origin = "Universidad Marítima del Caribe, Catia la Mar, Venezuela"; // Default fallback
    }

    $ors = new \App\Libraries\OpenRouteService();
    
    // Pass destination coordinates override if available
    $result = $ors->getRouteAttributes($origin, $destination, null, $destCoordsOverride);

    if (isset($result['error'])) {
        return $this->response->setJSON(['status' => 'error', 'message' => $result['error']]);
    }

    return $this->response->setJSON([
        'status' => 'success', 
        'data' => $result,
        'origin' => $origin,
        'destination' => $destination,
        'coordinates_used' => $destCoordsOverride !== null
    ]);
}
    public function tracking($orderId = null)
    {
        $session = session();
        // For demo, if no orderId provided, try generic last order
        if (!$orderId) {
             $order = $session->get('last_order');
        } else {
             // ideally fetch from DB
             $order = $session->get('last_order'); // simplified
        }

        if (!$order) {
            return redirect()->to('menu')->with('error', 'No hay pedido activo.');
        }

        $origin = getenv('STORE_ADDRESS') ?: "Universidad Marítima del Caribe, Catia la Mar, Venezuela";

    // Try to calculate delivery time for the view
    $ors = new \App\Libraries\OpenRouteService();
    // Assuming user address is in the order or session
    $destination = $session->get('user')['address'] ?? '';
    
    $deliveryInfo = ['duration_text' => '20 min', 'distance_text' => '3 km']; // fallback
    
    if ($destination) {
         $result = $ors->getRouteAttributes($origin, $destination);
         if (!isset($result['error'])) {
             $deliveryInfo = $result;
         }
    }

    $data = [
        'order' => $order,
        'delivery' => $deliveryInfo,
        'status' => 'preparing' // simple mock status: preparing, on_route, delivered
    ];

        return view('order/tracking', $data);
    }
}
