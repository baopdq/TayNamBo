-- Bổ sung schema cho mã nguồn hiện tại (chạy sau database.sql). Dùng: mysql -u root tour_du_lich < database_extensions.sql
USE `tour_du_lich`;

ALTER DATABASE `tour_du_lich` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `tblcustomer` ADD COLUMN `Cu_Address` varchar(500) DEFAULT NULL;

ALTER TABLE `tbtour` ADD COLUMN `mo_ta` text DEFAULT NULL;
UPDATE `tbtour` SET `mo_ta` = `Description` WHERE (`mo_ta` IS NULL OR `mo_ta` = '') AND `Description` IS NOT NULL;

ALTER TABLE `tbtour` ADD COLUMN `Price_Child` decimal(10,0) NOT NULL DEFAULT 0;
ALTER TABLE `tbtour` ADD COLUMN `itinerary` text DEFAULT NULL;
ALTER TABLE `tbtour` ADD COLUMN `cancellation_policy` text DEFAULT NULL;
ALTER TABLE `tbtour` ADD COLUMN `rating` decimal(3,1) DEFAULT 4.8;

CREATE TABLE IF NOT EXISTS `tbtour_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tour_images_tour` (`tour_id`),
  CONSTRAINT `fk_tour_images_tour` FOREIGN KEY (`tour_id`) REFERENCES `tbtour` (`Tour_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lich_khai_hanh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `ngay_khai_hanh` date NOT NULL,
  `so_cho_con` int(11) NOT NULL DEFAULT 20,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `fk_lich_tour` FOREIGN KEY (`tour_id`) REFERENCES `tbtour` (`Tour_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `lich_khai_hanh` (`id`, `tour_id`, `ngay_khai_hanh`, `so_cho_con`) VALUES
(1, 1, DATE_ADD(CURDATE(), INTERVAL 14 DAY), 25),
(2, 1, DATE_ADD(CURDATE(), INTERVAL 30 DAY), 20),
(3, 2, DATE_ADD(CURDATE(), INTERVAL 7 DAY), 30),
(4, 3, DATE_ADD(CURDATE(), INTERVAL 21 DAY), 15);

CREATE TABLE IF NOT EXISTS `tblbooking` (
  `B_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ma_don` varchar(50) NOT NULL,
  `Cu_ID` int(11) NOT NULL,
  `Tour_ID` int(11) NOT NULL,
  `so_nguoi_lon` int(11) NOT NULL DEFAULT 1,
  `so_tre_em` int(11) NOT NULL DEFAULT 0,
  `tong_tien` decimal(12,0) NOT NULL DEFAULT 0,
  `gia_nguoi_lon` decimal(12,0) DEFAULT 0,
  `gia_tre_em` decimal(12,0) DEFAULT 0,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai_don` varchar(50) DEFAULT 'pending',
  `trang_thai_tt` varchar(50) DEFAULT 'chua_thanh_toan',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`B_ID`),
  UNIQUE KEY `ma_don` (`ma_don`),
  KEY `Cu_ID` (`Cu_ID`),
  KEY `Tour_ID` (`Tour_ID`),
  CONSTRAINT `fk_booking_customer` FOREIGN KEY (`Cu_ID`) REFERENCES `tblcustomer` (`Cu_ID`),
  CONSTRAINT `fk_booking_tour` FOREIGN KEY (`Tour_ID`) REFERENCES `tbtour` (`Tour_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tblcontact` (
  `Contact_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Ho_Ten` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Dien_Thoai` varchar(50) NOT NULL,
  `Tieu_De` varchar(255) NOT NULL,
  `Noi_Dung` text NOT NULL,
  `Ngay_Tao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`Contact_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tblblogcategory` (
  `Cat_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Cat_Name` varchar(255) NOT NULL,
  PRIMARY KEY (`Cat_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tblblog` (
  `Blog_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Cat_ID` int(11) DEFAULT NULL,
  `Blog_Title` varchar(500) NOT NULL,
  `Blog_Content` longtext,
  `Blog_Image` varchar(500) DEFAULT NULL,
  `Author` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`Blog_ID`),
  KEY `Cat_ID` (`Cat_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
