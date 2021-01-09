-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jan 2021 pada 18.24
-- Versi server: 10.4.10-MariaDB
-- Versi PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aplikasi_inventori`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `brands`
--

CREATE TABLE `brands` (
  `brand_id` char(36) NOT NULL DEFAULT 'uuid()',
  `brand_name` varchar(128) NOT NULL,
  `category_id` char(36) NOT NULL DEFAULT 'uuid()'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `category_id`) VALUES
('1521c55d-9d19-46e2-a63e-9c24492561be', 'Logitech', '82ebcbeb-0485-49a1-af49-c7455b0a708c'),
('5fc0d837-6b9a-4386-b78c-66e41ce26c27', 'Polytron', 'f49c6d11-856b-4d38-8b96-602d6780d418'),
('ebc8ae61-e79a-4dc1-97af-88b10b328e9b', 'Kenko', '872415b3-6e1a-4110-97f1-d3ac633a0a63');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `category_id` char(36) NOT NULL DEFAULT 'uuid()',
  `category_name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
('82ebcbeb-0485-49a1-af49-c7455b0a708c', 'Aksesoris Laptop'),
('872415b3-6e1a-4110-97f1-d3ac633a0a63', 'Alat Tulis'),
('f49c6d11-856b-4d38-8b96-602d6780d418', 'Material Listrik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `credits`
--
-- Kesalahan membaca struktur untuk tabel aplikasi_inventori.credits: #1932 - Table 'aplikasi_inventori.credits' doesn't exist in engine
-- Kesalahan membaca data untuk tabel aplikasi_inventori.credits: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `aplikasi_inventori`.`credits`' at line 1

-- --------------------------------------------------------

--
-- Struktur dari tabel `orderdetails`
--

CREATE TABLE `orderdetails` (
  `orderdetails_id` char(36) NOT NULL DEFAULT 'uuid()',
  `order_id` char(36) NOT NULL DEFAULT 'uuid()',
  `product_id` char(36) NOT NULL DEFAULT 'uuid()',
  `quantity_ordered` int(11) NOT NULL,
  `total_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--
-- Kesalahan membaca struktur untuk tabel aplikasi_inventori.orders: #1932 - Table 'aplikasi_inventori.orders' doesn't exist in engine
-- Kesalahan membaca data untuk tabel aplikasi_inventori.orders: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `aplikasi_inventori`.`orders`' at line 1

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `payment_id` char(36) NOT NULL DEFAULT 'uuid()',
  `amount` double NOT NULL,
  `order_id` char(36) NOT NULL DEFAULT 'uuid()',
  `payment_status` enum('Lunas','Masih Terhutang') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stuffs`
--

CREATE TABLE `stuffs` (
  `stuff_id` char(36) NOT NULL DEFAULT 'uuid()',
  `stuff_name` varchar(240) NOT NULL,
  `category_id` char(36) NOT NULL DEFAULT 'uuid()',
  `brand_id` char(36) NOT NULL DEFAULT 'uuid()',
  `stuff_buy_price` double NOT NULL,
  `supplier_id` char(36) NOT NULL DEFAULT 'uuid()',
  `stuff_sale_price` double NOT NULL,
  `stuff_in_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `stuffs`
--

INSERT INTO `stuffs` (`stuff_id`, `stuff_name`, `category_id`, `brand_id`, `stuff_buy_price`, `supplier_id`, `stuff_sale_price`, `stuff_in_stock`) VALUES
('b7978f06-a83a-46b6-ac65-c9e61af6de1d', 'Office mouse logitech', '82ebcbeb-0485-49a1-af49-c7455b0a708c', '1521c55d-9d19-46e2-a63e-9c24492561be', 40000, '822af4ba-88fa-40db-934b-622f9ed9f0a3', 60000, 25);

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` char(36) NOT NULL DEFAULT 'uuid()',
  `supplier_name` varchar(128) NOT NULL,
  `supplier_phone` varchar(12) NOT NULL,
  `supplier_email` varchar(128) DEFAULT NULL,
  `supplier_address` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `supplier_phone`, `supplier_email`, `supplier_address`) VALUES
('822af4ba-88fa-40db-934b-622f9ed9f0a3', 'CV. Jaya Abadi Elektronik', '082274956121', 'jayacv@gmail.com', 'Jalan Bilal Medan Barat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` char(36) NOT NULL DEFAULT 'uuid()',
  `user_name` varchar(128) NOT NULL,
  `user_email` varchar(128) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_role` enum('Administrator','Cashier') NOT NULL,
  `user_created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_role`, `user_created_at`) VALUES
('98752696-41d0-44cd-8ead-c5c80a2a5a2e', 'Admin Toko', 'admin@toko.com', '$2y$10$S7x9wLt2kAefexess5VSwumBOcTvDk3I74k56wlr7droI8hmT0aWq', 'Administrator', '2020-12-19 16:38:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indeks untuk tabel `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`orderdetails_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indeks untuk tabel `stuffs`
--
ALTER TABLE `stuffs`
  ADD PRIMARY KEY (`stuff_id`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
