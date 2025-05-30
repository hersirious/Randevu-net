-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 30 May 2025, 11:19:53
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `randevunet1`
--
CREATE DATABASE IF NOT EXISTS `randevunet1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `randevunet1`;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adminler`
--

CREATE TABLE `adminler` (
  `admin_id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `sifre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `adminler`
--

INSERT INTO `adminler` (`admin_id`, `kullanici_adi`, `sifre`) VALUES
(1, 'admin', '1234567890');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `firmalar`
--

CREATE TABLE `firmalar` (
  `firma_id` int(11) NOT NULL,
  `firma_adi` varchar(255) NOT NULL,
  `firma_sahibi_id` int(11) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `firmalar`
--

INSERT INTO `firmalar` (`firma_id`, `firma_adi`, `firma_sahibi_id`, `kayit_tarihi`) VALUES
(5, 'Diyarbakır Diş', 11, '2025-05-14 10:55:57');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `iletisim_mesajlari`
--

CREATE TABLE `iletisim_mesajlari` (
  `id` int(11) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `eposta` varchar(255) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `mesaj` text NOT NULL,
  `olusturma_zamani` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `iletisim_mesajlari`
--

INSERT INTO `iletisim_mesajlari` (`id`, `ad`, `eposta`, `telefon`, `mesaj`, `olusturma_zamani`) VALUES
(9, 'rıdvan', 'rkenanoglu@gmail.com', '0531 835 47 84', 'eksikliklerin giderilmesi lazım', '2025-05-14 11:05:41');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `kullanici_id` int(11) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `soyad` varchar(255) NOT NULL,
  `eposta` varchar(255) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `sifre` varchar(255) NOT NULL,
  `firma_sahibi` tinyint(1) DEFAULT 0,
  `firma_adi` varchar(255) DEFAULT NULL,
  `firma_meslek` varchar(255) DEFAULT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `ban` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`kullanici_id`, `ad`, `soyad`, `eposta`, `telefon`, `sifre`, `firma_sahibi`, `firma_adi`, `firma_meslek`, `kayit_tarihi`, `ban`) VALUES
(11, 'Yusuf', 'Dağhan', 'yusufdaghan54@gmail.com', NULL, '123123', 1, 'Diyarbakır Diş', 'Diş hastanesi', '2025-05-14 10:55:57', 0),
(12, 'ibrahim', 'dağhan', 'yusufdaghan53@gmail.com', NULL, '123123', 0, NULL, NULL, '2025-05-14 10:57:26', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevular`
--

CREATE TABLE `randevular` (
  `randevu_id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `firma_adi` varchar(255) NOT NULL,
  `tarih` date NOT NULL,
  `saat` time NOT NULL,
  `olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `durum` enum('Beklemede','Kabul Edildi','İptal Edildi','Tamamlandı') DEFAULT 'Beklemede',
  `aktif_mi` char(1) DEFAULT '*'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `randevular`
--

INSERT INTO `randevular` (`randevu_id`, `kullanici_id`, `firma_adi`, `tarih`, `saat`, `olusturma_tarihi`, `durum`, `aktif_mi`) VALUES
(6, 12, 'Diyarbakır Diş', '2025-05-15', '17:01:00', '2025-05-14 10:58:03', 'Tamamlandı', '*'),
(7, 12, 'Diyarbakır Diş', '2025-05-15', '17:01:00', '2025-05-14 10:58:27', 'İptal Edildi', '*');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `adminler`
--
ALTER TABLE `adminler`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `kullanici_adi` (`kullanici_adi`);

--
-- Tablo için indeksler `firmalar`
--
ALTER TABLE `firmalar`
  ADD PRIMARY KEY (`firma_id`),
  ADD UNIQUE KEY `firma_adi` (`firma_adi`),
  ADD KEY `firma_sahibi_id` (`firma_sahibi_id`);

--
-- Tablo için indeksler `iletisim_mesajlari`
--
ALTER TABLE `iletisim_mesajlari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`kullanici_id`),
  ADD UNIQUE KEY `eposta` (`eposta`);

--
-- Tablo için indeksler `randevular`
--
ALTER TABLE `randevular`
  ADD PRIMARY KEY (`randevu_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `adminler`
--
ALTER TABLE `adminler`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `firmalar`
--
ALTER TABLE `firmalar`
  MODIFY `firma_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `iletisim_mesajlari`
--
ALTER TABLE `iletisim_mesajlari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `kullanici_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `randevular`
--
ALTER TABLE `randevular`
  MODIFY `randevu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `firmalar`
--
ALTER TABLE `firmalar`
  ADD CONSTRAINT `firmalar_ibfk_1` FOREIGN KEY (`firma_sahibi_id`) REFERENCES `kullanicilar` (`kullanici_id`);

--
-- Tablo kısıtlamaları `randevular`
--
ALTER TABLE `randevular`
  ADD CONSTRAINT `randevular_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`kullanici_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
