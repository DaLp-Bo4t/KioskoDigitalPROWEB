-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-01-2026 a las 21:09:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kiosko_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `address_json` text DEFAULT NULL,
  `payment_info` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `address_json`, `payment_info`, `created_at`, `updated_at`) VALUES
(1, 1, 50.00, 'pending', '{\"addr\":\"test\"}', '{\"method\":\"zelle\"}', '2026-01-29 23:14:53', '2026-01-29 23:22:11'),
(2, 2, 12.99, 'pending', 'Carayaca', '{\"type\":\"pago_movil\",\"reference\":\"515654\"}', '2026-01-30 03:23:30', '2026-01-30 03:23:30'),
(3, 2, 2.50, 'pending', 'Carayaca', '{\"type\":\"pago_movil\",\"reference\":\"484513\"}', '2026-01-30 03:36:59', '2026-01-30 03:36:59'),
(4, 2, 51.50, 'pending', 'Carayaca', '{\"type\":\"pago_movil\",\"reference\":\"156415\"}', '2026-01-30 04:09:47', '2026-01-30 04:09:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `extras` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `price`, `extras`) VALUES
(1, 1, 'Pizza Test', 1, 50.00, '[]'),
(2, 2, 'Pizza Suprema', 1, 12.99, '[\"Queso Mozzarella\",\"Salsa de Tomate\",\"Pepperoni\",\"Jam\\u00f3n\",\"Champi\\u00f1ones\",\"Pimientos\"]'),
(3, 3, 'Coca Cola', 1, 2.50, '[]'),
(4, 4, 'Pizza Personalizada', 1, 9.00, '[\"Pizza Personal\",\"Mozzarella\",\"Ma\\u00edz\",\"Peperoni\",\"Champi\\u00f1ones\"]'),
(5, 4, 'Veggie Garden', 1, 11.50, '[\"Queso Mozzarella\",\"Salsa de Tomate\",\"Pimientos\",\"Cebolla\",\"Aceitunas\",\"Champi\\u00f1ones\"]'),
(6, 4, 'Coca Cola', 2, 2.50, '[]'),
(7, 4, 'Pizza Personalizada', 1, 9.00, '[\"Pizza Personal\",\"Mozzarella\",\"Ma\\u00edz\",\"Aceitunas\",\"Tocineta\"]'),
(8, 4, 'Perfect', 1, 12.00, '[\"Mozzarella\",\"Maiz\",\"Peperoni\",\"Champi\"]'),
(9, 4, 'quesua', 1, 5.00, '[\"Mozzarella\"]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `product_id` int(11) UNSIGNED DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `user_name`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(3, 2, 5, 'usuario numero 1', 5, 'Refrescante ', '2026-01-30 03:33:52', '2026-01-30 03:33:52'),
(4, 2, NULL, 'usuario numero 1', 5, 'Calidad y Rapidez', '2026-01-30 03:39:33', '2026-01-30 03:39:33'),
(5, 2, NULL, 'usuario numero 1', 4, 'Muy rica la comida ', '2026-01-30 04:11:57', '2026-01-30 04:11:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saved_pizzas`
--

CREATE TABLE `saved_pizzas` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `configuration` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`configuration`)),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `saved_pizzas`
--

INSERT INTO `saved_pizzas` (`id`, `user_id`, `name`, `configuration`, `created_at`) VALUES
(1, 2, 'Perfect', '{\"size\":\"size_medium\",\"cheese\":\"cheese_mozarella\",\"toppings\":[\"MAIZ\",\"PEPERONI\",\"CHAMPI\"]}', '2026-01-29 23:53:15'),
(2, 2, 'quesua', '{\"size\":\"size_personal\",\"cheese\":\"cheese_mozarella\",\"toppings\":[]}', '2026-01-30 00:09:20'),
(3, 3, 'La pepe', '{\"size\":\"size_medium\",\"cheese\":\"cheese_mozarella\",\"toppings\":[\"PEPERONI\",\"CEBOLLA\",\"CHAMPI\"]}', '2026-01-30 00:20:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(2, 'usuario numero 1', 'admin@admin.com', '$2y$10$ZSjiSCkYEgnJqXmSgBsiOuYuaICaf5.whjqrnDkmuVovEU35ZxXom', '04126980234', 'Carayaca', '2026-01-30 03:20:01', '2026-01-30 03:20:01'),
(3, 'usuario numero 2', 'admin@admi.com', '$2y$10$68cByazGDvc.MQEn8fjTNOVmdHc6RW8BPH0kg5b0hdIUbzj8o9gCW', '04126980234', 'Carayaca', '2026-01-30 04:20:01', '2026-01-30 04:20:01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `saved_pizzas`
--
ALTER TABLE `saved_pizzas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `saved_pizzas`
--
ALTER TABLE `saved_pizzas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
