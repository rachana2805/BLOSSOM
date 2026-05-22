DROP DATABASE IF EXISTS blossom_db;
CREATE DATABASE blossom_db;
USE blossom_db;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin User', 'admin@blossom.com', 'admin123', 'admin'),
('Test User', 'test@blossom.com', 'test123', 'user');

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'Flowers',
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 10,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`name`, `category`, `price`, `image`, `stock`, `status`) VALUES
('Red Roses Bouquet', 'Flowers', 1500.00, 'rose.jpg', 10, 'active'),
('White Lilies', 'Flowers', 1200.00, 'lily.jpg', 10, 'active'),
('Mixed Flowers', 'Flowers', 1800.00, 'mixed.jpg', 10, 'active'),
('Dairy Milk', 'Chocolates', 50.00, NULL, 50, 'active'),
('Ferrero Rocher', 'Chocolates', 150.00, NULL, 30, 'active'),
('KitKat', 'Chocolates', 40.00, NULL, 40, 'active'),
('Sports Car', 'Hot Wheels', 250.00, NULL, 20, 'active'),
('Racing Truck', 'Hot Wheels', 300.00, NULL, 15, 'active'),
('Teddy Bear', 'Extras', 500.00, NULL, 10, 'active'),
('LED Lights', 'Extras', 200.00, NULL, 25, 'active'),
('Small Flower Bunch', 'Extras', 100.00, NULL, 20, 'active');

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `customization_details` text DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 999,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `items` (`name`, `category`, `price`, `stock`, `status`) VALUES
('Dairy Milk', 'Chocolates', 50.00, 999, 'active'),
('Ferrero Rocher', 'Chocolates', 150.00, 999, 'active'),
('KitKat', 'Chocolates', 40.00, 999, 'active'),
('Sports Car', 'Hot Wheels', 250.00, 999, 'active'),
('Racing Truck', 'Hot Wheels', 300.00, 999, 'active'),
('Teddy Bear', 'Extras', 500.00, 999, 'active'),
('LED Lights', 'Extras', 200.00, 999, 'active'),
('Small Flower Bunch', 'Extras', 100.00, 999, 'active');

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `ribbon` varchar(100) DEFAULT NULL,
  `wrap` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `items_json` text DEFAULT NULL,
  `wrapping_type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `order_id` varchar(50) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_type` varchar(50) DEFAULT NULL,
  `Customer` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `bouquet_type` varchar(100) DEFAULT NULL,
  `wrapping_type` varchar(100) DEFAULT NULL,
  `items_json` text DEFAULT NULL,
  `custom_message` text DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT 'Order Placed',
  `delivery_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `ribbon` varchar(100) DEFAULT NULL,
  `wrap` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'completed',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
