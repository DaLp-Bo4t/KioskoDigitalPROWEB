<?php

namespace App\Controllers;

class Menu extends BaseController
{
    public function index()
    {
        $data = [
            'categories' => [
                ['id' => 1, 'name' => 'Todas', 'slug' => 'all'],
                ['id' => 2, 'name' => 'Clásicas', 'slug' => 'clasicas'],
                ['id' => 3, 'name' => 'Especialidades', 'slug' => 'especialidades'],
                ['id' => 4, 'name' => 'Vegetarianas', 'slug' => 'vegetarianas'],
                ['id' => 5, 'name' => 'Bebidas', 'slug' => 'bebidas'],
            ],
            'products' => $this->getProducts()
        ];

        return view('menu/index', $data);
    }

    public function detail($id)
    {
        $products = $this->getProducts();
        $product = null;

        foreach ($products as $p) {
            if ($p['id'] == $id) {
                $product = $p;
                break;
            }
        }

        if (!$product) {
            return redirect()->to('menu');
        }

        // Fetch reviews from DB
        $reviewModel = new \App\Models\ReviewModel();
        $dbReviews = $reviewModel->where('product_id', $id)->orderBy('created_at', 'DESC')->findAll();
        
        // Format for view
        $product['reviews'] = [];
        foreach ($dbReviews as $r) {
            $product['reviews'][] = [
                'user' => $r['user_name'],
                'rating' => $r['rating'],
                'comment' => $r['comment'],
                'date' => date('d M Y', strtotime($r['created_at']))
            ];
        }

        return view('menu/detail', ['product' => $product, 'user' => session()->get('user')]);
    }

    public function submit_review()
    {
        if (!session()->has('user')) {
             return redirect()->to('user/login')->with('error', 'Debes iniciar sesión para opinar.');
        }

        $session = session();
        $rules = [
            'user' => 'required|min_length[3]',
            'comment' => 'required|min_length[5]',
            'rating' => 'required|integer|greater_than[0]|less_than[6]',
            'product_id' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Por favor verifica los datos ingresados.');
        }

        $user = $session->get('user');
        
        $reviewModel = new \App\Models\ReviewModel();
        
        $newReview = [
            'product_id' => $this->request->getPost('product_id'),
            'user_id' => $user['id'] ?? null,
            'user_name' => $this->request->getPost('user'),
            'rating' => (int) $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment')
        ];

        $reviewModel->save($newReview);

        return redirect()->back()->with('success', '¡Gracias por tu opinión!');
    }

    private function getProducts()
    {
        // Default ingredients for pizzas
        $defaultPizzaIngredients = [
            ['name' => 'Queso Mozzarella', 'default' => true, 'price' => 0],
            ['name' => 'Salsa de Tomate', 'default' => true, 'price' => 0],
            ['name' => 'Extra Queso', 'default' => false, 'price' => 2.00],
            ['name' => 'Borde de Queso', 'default' => false, 'price' => 3.00]
        ];

        return [
            [
                'id' => 1,
                'name' => 'Pizza Suprema',
                'category_slug' => 'especialidades',
                'price' => 12.99,
                'image' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'La combinación perfecta de carnes y vegetales frescos. Carga completa de sabor.',
                'weight' => '450g',
                'calories' => '280 kcal/porción',
                'ingredients' => array_merge($defaultPizzaIngredients, [
                    ['name' => 'Pepperoni', 'default' => true, 'price' => 0],
                    ['name' => 'Jamón', 'default' => true, 'price' => 0],
                    ['name' => 'Champiñones', 'default' => true, 'price' => 0],
                    ['name' => 'Pimientos', 'default' => true, 'price' => 0]
                ]),
                'reviews' => [
                     ['user' => 'Carlos D.', 'rating' => 5, 'comment' => 'Excelente sabor y temperatura.', 'date' => 'Hace 2 días'],
                     ['user' => 'Ana M.', 'rating' => 4, 'comment' => 'Muy rica pero tardó un poco.', 'date' => 'Hace 1 semana']
                ]
            ],
            [
                'id' => 2,
                'name' => 'Pepperoni Lover',
                'category_slug' => 'clasicas',
                'price' => 10.99,
                'image' => 'https://images.unsplash.com/photo-1534308983496-4fabb1a015ee?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'Doble pepperoni crujiente con extra queso mozzarella.',
                'weight' => '400g',
                'calories' => '320 kcal/porción',
                'ingredients' => array_merge($defaultPizzaIngredients, [
                    ['name' => 'Doble Pepperoni', 'default' => true, 'price' => 0]
                ]),
                'reviews' => [
                     ['user' => 'Juan P.', 'rating' => 5, 'comment' => 'La mejor de pepperoni.', 'date' => 'Hace 3 días']
                ]
            ],
            [
                'id' => 3,
                'name' => 'Veggie Garden',
                'category_slug' => 'vegetarianas',
                'price' => 11.50,
                'image' => 'https://images.unsplash.com/photo-1579751626657-72bc17010498?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'Pimientos, cebolla, aceitunas y champiñones frescos.',
                'weight' => '420g',
                'calories' => '240 kcal/porción',
                'ingredients' => array_merge($defaultPizzaIngredients, [
                    ['name' => 'Pimientos', 'default' => true, 'price' => 0],
                    ['name' => 'Cebolla', 'default' => true, 'price' => 0],
                    ['name' => 'Aceitunas', 'default' => true, 'price' => 0],
                    ['name' => 'Champiñones', 'default' => true, 'price' => 0]
                ]),
                'reviews' => [
                     ['user' => 'Maria L.', 'rating' => 5, 'comment' => 'Muy fresca.', 'date' => 'Ayer']
                ]
            ],
            [
                'id' => 4,
                'name' => 'Hawaiiana',
                'category_slug' => 'clasicas',
                'price' => 10.50,
                'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'Jamón, piña y extra queso.',
                'weight' => '430g',
                'calories' => '260 kcal/porción',
                'ingredients' => array_merge($defaultPizzaIngredients, [
                    ['name' => 'Jamón', 'default' => true, 'price' => 0],
                    ['name' => 'Piña', 'default' => true, 'price' => 0]
                ]),
                'reviews' => [
                     ['user' => 'Pedro S.', 'rating' => 4, 'comment' => 'Buena, pero le faltó piña.', 'date' => 'Hace 1 mes']
                ]
            ],
            [
                'id' => 5,
                'name' => 'Coca Cola',
                'category_slug' => 'bebidas',
                'price' => 2.50,
                'image' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'Refresco de cola bien frío.',
                'weight' => '355ml',
                'calories' => '140 kcal',
                'ingredients' => [], // Bebidas sin ingredientes extra
                'reviews' => []
            ],
            [
                'id' => 6,
                'name' => 'BBQ Chicken',
                'category_slug' => 'especialidades',
                'price' => 13.50,
                'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', // Placeholder image
                'description' => 'Pollo a la parrilla, cebolla morada y nuestra salsa BBQ especial.',
                'weight' => '460g',
                'calories' => '300 kcal/porción',
                'ingredients' => array_merge($defaultPizzaIngredients, [
                    ['name' => 'Pollo', 'default' => true, 'price' => 0],
                    ['name' => 'Salsa BBQ', 'default' => true, 'price' => 0],
                    ['name' => 'Cebolla Morada', 'default' => true, 'price' => 0]
                ]),
                'reviews' => []
            ],
            [
                'id' => 7,
                'name' => 'Cuatro Quesos',
                'category_slug' => 'clasicas',
                'price' => 11.99,
                'image' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'Una mezcla rica de Mozzarella, Parmesano, Gorgonzola y Fontina.',
                'weight' => '410g',
                'calories' => '310 kcal/porción',
                'ingredients' => array_merge($defaultPizzaIngredients, [
                    ['name' => 'Mezcla 4 Quesos', 'default' => true, 'price' => 0]
                ]),
                'reviews' => []
            ],
            [
                'id' => 8,
                'name' => 'Margarita',
                'category_slug' => 'clasicas',
                'price' => 9.99,
                'image' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'La clásica italiana: salsa de tomate, mozzarella fresca y albahaca.',
                'weight' => '380g',
                'calories' => '220 kcal/porción',
                'ingredients' => array_merge($defaultPizzaIngredients, [
                    ['name' => 'Albahaca Fresca', 'default' => true, 'price' => 0],
                    ['name' => 'Tomate Fresco', 'default' => true, 'price' => 0]
                ]),
                'reviews' => []
            ],
            [
                'id' => 9,
                'name' => 'Sprite',
                'category_slug' => 'bebidas',
                'price' => 2.50,
                'image' => 'https://images.unsplash.com/photo-1625772299848-391b6a87d7b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'Refresco sabor lima-limón.',
                'weight' => '355ml',
                'calories' => '140 kcal',
                'ingredients' => [],
                'reviews' => []
            ],
            [
                'id' => 10,
                'name' => 'Fanta Naranja',
                'category_slug' => 'bebidas',
                'price' => 2.50,
                'image' => 'https://images.unsplash.com/photo-1624517452488-04869289c4ca?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'Refresco sabor naranja.',
                'weight' => '355ml',
                'calories' => '150 kcal',
                'ingredients' => [],
                'reviews' => []
            ],
            [
                'id' => 11,
                'name' => 'Agua Mineral',
                'category_slug' => 'bebidas',
                'price' => 1.50,
                'image' => 'https://images.unsplash.com/photo-1564419320461-6870880221ad?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                'description' => 'Agua mineral gasificada.',
                'weight' => '500ml',
                'calories' => '0 kcal',
                'ingredients' => [],
                'reviews' => []
            ]
        ];
    }
}
