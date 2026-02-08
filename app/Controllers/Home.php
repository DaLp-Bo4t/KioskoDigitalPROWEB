<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Fetch general reviews from DB
        $reviewModel = new \App\Models\ReviewModel();
        $dbReviews = $reviewModel->where('product_id', null)->orderBy('created_at', 'DESC')->limit(6)->findAll();
        
        $reviews = [];
        foreach ($dbReviews as $r) {
            $reviews[] = [
                'user' => $r['user_name'],
                'comment' => $r['comment'],
                'rating' => $r['rating']
            ];
        }

        $data = [
            'featured_products' => $this->getProducts(),
            'reviews' => $reviews,
            'chefs' => [
                [
                    'name' => 'Mario Rossi',
                    'role' => 'Maestro Pizzaiolo',
                    'image' => 'https://images.unsplash.com/photo-1583394293214-28ded15ee548?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                    'bio' => 'Con más de 20 años de experiencia en Nápoles, Mario trae la auténtica tradición italiana.'
                ],
                [
                    'name' => 'Laura Chen',
                    'role' => 'Chef de Innovación',
                    'image' => 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                    'bio' => 'Experta en fusión de sabores, creando nuestras especialidades más atrevidas.'
                ],
                [
                    'name' => 'Carlos Ruiz',
                    'role' => 'Jefe de Cocina',
                    'image' => 'https://images.unsplash.com/photo-1566554273541-37a9ca77b91f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
                    'bio' => 'Asegura que cada pizza salga perfecta del horno de leña cada vez.'
                ]
            ]
        ];

        return view('home', $data);
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
                'category' => 'Especialidad',
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
                'category' => 'Clásica',
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
                'category' => 'Vegetariana',
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
                'category' => 'Clásica',
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
                'category' => 'Bebidas',
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
                'category' => 'Especialidad',
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
                'category' => 'Clásica',
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
                'category' => 'Clásica',
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
                'category' => 'Bebidas',
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
                'category' => 'Bebidas',
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
                'category' => 'Bebidas',
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
