<?php

namespace App\Controllers;

class User extends BaseController
{
    public function profile()
    {
        $session = session();
        if (!$session->has('user')) {
            return redirect()->to('login');
        }

        $user = $session->get('user');

        // Initialize addresses list in user session if it doesn't exist
        if (!isset($user['addresses'])) {
            $user['addresses'] = [];
            // If the user has a registration address, use it as 'Principal'
            if (isset($user['address'])) {
                $user['addresses'][] = [
                    'alias' => 'Principal',
                    'address' => $user['address']
                ];
            }
            // Update session so it's persisted
            $session->set('user', $user);
        }

        // Fetch orders from DB
        $orderModel = new \App\Models\OrderModel();
        $orderItemModel = new \App\Models\OrderItemModel();
        
        $userId = $user['id'] ?? 0;
        $dbOrders = $orderModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();
        
        $orders = [];
        foreach ($dbOrders as $o) {
            // Get items
            $items = $orderItemModel->where('order_id', $o['id'])->findAll();
            $itemSummary = [];
            foreach ($items as $item) {
                $itemSummary[] = $item['quantity'] . 'x ' . $item['product_name'];
            }
            
            $orders[] = [
                'id' => $o['id'],
                'date' => date('d M Y, h:i A', strtotime($o['created_at'])),
                'total' => $o['total'],
                'status' => ucfirst($o['status']),
                'items' => implode(', ', $itemSummary),
                'link_invoice' => base_url('order/invoice/' . $o['id']),
                'link_tracking' => base_url('order/tracking/' . $o['id'])
            ];
        }

        $data = [
            'user' => $user,
            'orders' => $orders,
            'addresses' => $user['addresses']
        ];

        return view('user/profile', $data);
    }

    public function add_address()
    {
        $session = session();
        if (!$session->has('user')) {
             return redirect()->to('login');
        }

        $alias = $this->request->getPost('alias');
        $address = $this->request->getPost('address');

        if ($alias && $address) {
            $user = $session->get('user');
            
            // Ensure addresses array exists
            if (!isset($user['addresses'])) {
                $user['addresses'] = [];
            }

            // Add new address
            $user['addresses'][] = [
                'alias' => $alias,
                'address' => $address
            ];

            // Save back to session
            $session->set('user', $user);

            return redirect()->to('profile')->with('success', 'Dirección agregada correctamente.');
        }

        return redirect()->to('profile')->with('error', 'Por favor completa todos los campos.');
    }

    public function remove_address()
    {
        $session = session();
        if (!$session->has('user')) {
             return redirect()->to('login');
        }

        $index = (int) $this->request->getPost('index');
        $user = $session->get('user');

        if (isset($user['addresses'][$index])) {
            array_splice($user['addresses'], $index, 1);
            $session->set('user', $user);
            return redirect()->to('profile')->with('success', 'Dirección eliminada.');
        }

        return redirect()->to('profile')->with('error', 'No se pudo eliminar la dirección.');
    }

    public function login()
    {
        if (session()->has('user')) {
            return redirect()->to('profile');
        }
        return view('user/login');
    }

    public function register_view()
    {
        if (session()->has('user')) {
            return redirect()->to('profile');
        }
        return view('user/register');
    }

    public function create()
    {
        $userModel = new \App\Models\UserModel();
        
        $username = $this->request->getPost('username');
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $username,
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        // Basic validation check
        if ($userModel->where('email', $username)->first()) {
             return redirect()->back()->with('error', 'El nombre de usuario ya está en uso.');
        }

        $userModel->save($data);
        
        // Auto login
        $user = $userModel->where('email', $username)->first();
        // Add avatar for view compatibility
        $user['avatar'] = 'https://ui-avatars.com/api/?background=random&color=fff&name=' . urlencode($user['name']);
        
        session()->set('user', $user);
        return redirect()->to('profile');
    }

    public function attempt_login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('email', $username)->first();

        if ($user) {
             if (password_verify($password, $user['password_hash'])) {
                 $user['avatar'] = 'https://ui-avatars.com/api/?background=FF5722&color=fff&name=' . urlencode($user['name']);
                 session()->set('user', $user);
                 return redirect()->to('profile');
             }
        }
        
        return redirect()->to('login')->with('error', 'Usuario o contraseña incorrectos');
    }

    public function logout()
    {
        session()->remove('user');
        return redirect()->to('/');
    }
}
