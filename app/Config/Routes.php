<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Menu
$routes->get('menu', 'Menu::index');
$routes->get('menu/detail/(:num)', 'Menu::detail/$1');

// User
$routes->get('profile', 'User::profile');
$routes->get('login', 'User::login');
$routes->get('user/login', 'User::login');
$routes->get('register', 'User::register_view');
$routes->get('user/register', 'User::register_view');
$routes->post('auth/login', 'User::attempt_login');
$routes->post('auth/create', 'User::create');
$routes->post('user/add_address', 'User::add_address');
$routes->post('user/remove_address', 'User::remove_address');
$routes->get('logout', 'User::logout');
$routes->get('cart', 'Menu::index'); // Redirect to menu for demo

// Order
$routes->get('order', 'Order::cart');
$routes->post('order/add', 'Order::add');
$routes->post('order/update_qty', 'Order::update_qty');
$routes->post('order/remove', 'Order::remove_item');
$routes->get('order/checkout', 'Order::checkout');
$routes->post('order/process_payment', 'Order::process_payment');
$routes->post('menu/submit_review', 'Menu::submit_review');
$routes->get('order/pay', 'Order::pay');
$routes->get('order/confirmation', 'Order::confirmation');
$routes->post('order/submit_review', 'Order::submit_review');
$routes->get('order/invoice', 'Order::invoice');
$routes->get('order/invoice/(:num)', 'Order::invoice/$1');
$routes->post('order/calculate_delivery', 'Order::calculate_delivery');
$routes->get('order/tracking', 'Order::tracking');
$routes->get('order/tracking/(:any)', 'Order::tracking/$1');

// Kiosko
$routes->get('kiosko', 'Kiosko::index');
$routes->post('kiosko/add', 'Kiosko::addToCart');
$routes->post('kiosko/saveConfiguration', 'Kiosko::saveConfiguration');
$routes->get('kiosko/orderFavorite/(:num)', 'Kiosko::orderFavorite/$1');
$routes->get('kiosko/addFavoriteToCart/(:num)', 'Kiosko::addFavoriteToCart/$1');

// Static Pages
$routes->get('reviews', 'Home::index'); // Anchor logic on home
$routes->get('contact', 'Home::index'); // Anchor logic

