-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_parkiran
CREATE DATABASE IF NOT EXISTS `db_parkiran` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_parkiran`;

-- Dumping structure for table db_parkiran.history
CREATE TABLE IF NOT EXISTS `history` (
  `id_history` int NOT NULL AUTO_INCREMENT,
  `id_masuk` int NOT NULL,
  `plat` varchar(20) NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL,
  `waktu_masuk` datetime NOT NULL,
  `waktu_keluar` datetime NOT NULL,
  `durasi` decimal(5,2) NOT NULL,
  `total_bayar` int NOT NULL,
  `total_akhir` int NOT NULL,
  `tanggal` int NOT NULL,
  `bulan` int NOT NULL,
  `tahun` int NOT NULL,
  `jam` int NOT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id_history`),
  KEY `id_masuk` (`id_masuk`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `history_ibfk_1` FOREIGN KEY (`id_masuk`) REFERENCES `kendaraan_masuk` (`id_masuk`),
  CONSTRAINT `history_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_parkiran.history: ~0 rows (approximately)

-- Dumping structure for table db_parkiran.jenis_kendaraan
CREATE TABLE IF NOT EXISTS `jenis_kendaraan` (
  `id_jenis` int NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(50) NOT NULL,
  `tarif_per_jam` int NOT NULL,
  `tarif_maksimal` int NOT NULL,
  `jam_maksimal` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_parkiran.jenis_kendaraan: ~2 rows (approximately)
INSERT INTO `jenis_kendaraan` (`id_jenis`, `nama_jenis`, `tarif_per_jam`, `tarif_maksimal`, `jam_maksimal`) VALUES
	(1, 'motor', 3000, 9000, 3.000000),
	(2, 'mobil', 5000, 15000, 3.000000);

-- Dumping structure for table db_parkiran.kendaraan_masuk
CREATE TABLE IF NOT EXISTS `kendaraan_masuk` (
  `id_masuk` int NOT NULL AUTO_INCREMENT,
  `barcode` varchar(100) NOT NULL,
  `plat_nomor` varchar(20) NOT NULL,
  `id_jenis` int NOT NULL,
  `id_user` int NOT NULL,
  `waktu_masuk` datetime DEFAULT CURRENT_TIMESTAMP,
  `STATUS` enum('parkir','keluar') NOT NULL DEFAULT 'parkir',
  PRIMARY KEY (`id_masuk`),
  UNIQUE KEY `barcode` (`barcode`),
  KEY `id_jenis` (`id_jenis`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `kendaraan_masuk_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_kendaraan` (`id_jenis`),
  CONSTRAINT `kendaraan_masuk_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_parkiran.kendaraan_masuk: ~0 rows (approximately)

-- Dumping structure for table db_parkiran.log_activity
CREATE TABLE IF NOT EXISTS `log_activity` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `log_activity_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_parkiran.log_activity: ~0 rows (approximately)

-- Dumping structure for table db_parkiran.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `id_masuk` int NOT NULL,
  `waktu_keluar` datetime DEFAULT CURRENT_TIMESTAMP,
  `durasi_jam` decimal(5,2) NOT NULL,
  `total_bayar` int NOT NULL,
  `denda_karcis` int DEFAULT '0',
  `total_akhir` int NOT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `id_masuk` (`id_masuk`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_masuk`) REFERENCES `kendaraan_masuk` (`id_masuk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_parkiran.transaksi: ~0 rows (approximately)

-- Dumping structure for table db_parkiran.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `STATUS` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `create_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_parkiran.user: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
