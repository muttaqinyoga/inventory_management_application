-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Des 2019 pada 13.37
-- Versi server: 10.1.30-MariaDB
-- Versi PHP: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_management`
--

DELIMITER $$
--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `convertToRupiah` (`number` INT) RETURNS VARCHAR(255) CHARSET latin1 BEGIN  
DECLARE hasil VARCHAR(255);  
SET hasil = REPLACE(REPLACE(REPLACE(FORMAT(number, 0), '.', '|'), ',', '.'), '|', ',');  
RETURN (hasil);  
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `fRupiah` (`number` BIGINT) RETURNS VARCHAR(255) CHARSET latin1 BEGIN  
DECLARE hasil VARCHAR(255);  
SET hasil = REPLACE(REPLACE(REPLACE(FORMAT(number, 0), '.', '|'), ',', '.'), '|', ',');  
RETURN (hasil);  
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `brand`
--

CREATE TABLE `brand` (
  `brandID` int(12) NOT NULL,
  `categoryID` int(12) NOT NULL,
  `brandName` varchar(100) DEFAULT NULL,
  `brandStatus` enum('active','inactive') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `brand`
--

INSERT INTO `brand` (`brandID`, `categoryID`, `brandName`, `brandStatus`) VALUES
(7013, 10011, 'Polytron', 'active'),
(7014, 10011, 'LG', 'active'),
(7015, 10013, 'Yamaha', 'active'),
(7016, 10012, 'Collage', 'active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `category`
--

CREATE TABLE `category` (
  `categoryID` int(12) NOT NULL,
  `categoryName` varchar(100) DEFAULT NULL,
  `categoryStatus` enum('active','inactive') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`, `categoryStatus`) VALUES
(10011, 'Electronics', 'active'),
(10012, 'Papers', 'active'),
(10013, 'Materials', 'active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory_order`
--

CREATE TABLE `inventory_order` (
  `inventoryOrderID` int(12) NOT NULL,
  `userID` int(12) DEFAULT NULL,
  `inventoryOrderTotal` int(12) DEFAULT NULL,
  `inventoryOrderDate` varchar(25) DEFAULT NULL,
  `inventoryOrderName` varchar(100) DEFAULT NULL,
  `inventoryOrderAddress` varchar(100) DEFAULT NULL,
  `paymentStatus` enum('cash','credit') DEFAULT NULL,
  `inventoryOrderStatus` enum('active','inactive') DEFAULT NULL,
  `inventoryOrderCreatedDate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `inventory_order`
--

INSERT INTO `inventory_order` (`inventoryOrderID`, `userID`, `inventoryOrderTotal`, `inventoryOrderDate`, `inventoryOrderName`, `inventoryOrderAddress`, `paymentStatus`, `inventoryOrderStatus`, `inventoryOrderCreatedDate`) VALUES
(2, 6002, 330000, '02-12-2019', 'Indomaret Asrama', 'Jalan Asrama Medan Helvetia', 'cash', 'active', 1575550462),
(3, 6003, 2200000, '03-12-2019', 'PT Nusa Net', 'Jalan Multatuli Medan Baru', 'cash', 'active', 1575550549);

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory_order_product`
--

CREATE TABLE `inventory_order_product` (
  `inventoryOrderProductID` int(11) NOT NULL,
  `inventoryOrderID` int(11) DEFAULT NULL,
  `productID` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(12) DEFAULT NULL,
  `tax` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `inventory_order_product`
--

INSERT INTO `inventory_order_product` (`inventoryOrderProductID`, `inventoryOrderID`, `productID`, `quantity`, `price`, `tax`) VALUES
(2, 2, 1908, 2, 150000, 15000),
(3, 3, 1907, 1, 2000000, 200000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `productID` int(12) NOT NULL,
  `categoryID` int(12) NOT NULL,
  `brandID` int(12) NOT NULL,
  `productName` varchar(100) NOT NULL,
  `productDescription` varchar(200) NOT NULL,
  `productQuantity` int(11) NOT NULL,
  `productUnit` varchar(150) NOT NULL,
  `productBasePrice` int(12) NOT NULL,
  `productTax` int(12) NOT NULL,
  `productMinimumOrder` int(12) NOT NULL,
  `productEnterBy` int(11) NOT NULL,
  `productStatus` enum('active','inactive') NOT NULL,
  `productDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`productID`, `categoryID`, `brandID`, `productName`, `productDescription`, `productQuantity`, `productUnit`, `productBasePrice`, `productTax`, `productMinimumOrder`, `productEnterBy`, `productStatus`, `productDate`) VALUES
(1907, 10011, 7013, 'LCD Matrix Polytron', 'Lightening and save energy LCD for advertising', 11, 'Unit', 2000000, 200000, 0, 6001, 'active', '2019-12-05'),
(1908, 10012, 7016, 'Collage Cartoon White Papers 10 inch', 'Cartoon papers for advertising or brocures', 13, 'Unit', 150000, 15000, 0, 6001, 'active', '2019-12-05'),
(1909, 10013, 7015, 'Yamaha Automatic Cutter Paper Machine', 'Automatic Cutter Paper Machine with Input Dimensions', 5, 'Pcs', 1700000, 170000, 0, 6001, 'active', '2019-12-05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `userEmail` varchar(70) DEFAULT NULL,
  `userPassword` varchar(100) DEFAULT NULL,
  `userName` varchar(50) DEFAULT NULL,
  `userType` enum('master','user') DEFAULT NULL,
  `userStatus` enum('active','inactive') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`userID`, `userEmail`, `userPassword`, `userName`, `userType`, `userStatus`) VALUES
(6001, 'muttaqinyoga@gmail.com', '$2y$10$IyCd5S/Jhpa/25k3DcED.uDJF5zQyOKjVIstSSDXMp90lD9f4ogTW', 'Muhammad Surya Yoga Muttaqin', 'master', 'active'),
(6002, 'farizhamka@gmail.com', '$2y$10$Xintql5eRuzLjELPT8swfeiorNfkg1hdsiggsfDqKC7yTgifrjY6i', 'Fariz Hamka', 'user', 'active'),
(6003, 'rifqyderama@gmail.com', '$2y$10$4rHlASSXKViMGf/HdqiiAuIgbJDQ75qvAaXbx1V.IgPLQ8ug0jcey', 'Rifqy', 'user', 'active');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brandID`);

--
-- Indeks untuk tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indeks untuk tabel `inventory_order`
--
ALTER TABLE `inventory_order`
  ADD PRIMARY KEY (`inventoryOrderID`);

--
-- Indeks untuk tabel `inventory_order_product`
--
ALTER TABLE `inventory_order_product`
  ADD PRIMARY KEY (`inventoryOrderProductID`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `brand`
--
ALTER TABLE `brand`
  MODIFY `brandID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7017;

--
-- AUTO_INCREMENT untuk tabel `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10014;

--
-- AUTO_INCREMENT untuk tabel `inventory_order`
--
ALTER TABLE `inventory_order`
  MODIFY `inventoryOrderID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `inventory_order_product`
--
ALTER TABLE `inventory_order_product`
  MODIFY `inventoryOrderProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `productID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1910;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6004;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
