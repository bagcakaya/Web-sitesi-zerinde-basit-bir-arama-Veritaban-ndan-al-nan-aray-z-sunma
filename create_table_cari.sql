-- Veritabanını kullan (gerekirse oluşturun: CREATE DATABASE project;)
USE project;

-- CARI_KARTLAR tablosunu temiz oluştur
DROP TABLE IF EXISTS `CARI_KARTLAR`;
CREATE TABLE `CARI_KARTLAR` (
  `Sıra_No` INT NOT NULL AUTO_INCREMENT,
  `Cari_Grup` VARCHAR(50) NOT NULL,
  `Ek_Ad` VARCHAR(50) NULL,
  `Adı` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`Sıra_No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek veriler
INSERT INTO `CARI_KARTLAR` (`Cari_Grup`, `Ek_Ad`, `Adı`) VALUES
('MÜŞTERİ', 'AŞ', 'ABC Yazılım Anonim Şirketi'),
('TEDARİKÇİ', 'Ltd', 'XYZ Teknoloji Limited Şirketi'),
('MÜŞTERİ', NULL, 'Kaya İnşaat'),
('MÜŞTERİ', 'San.', 'Demir Sanayi ve Ticaret');
