-- Bổ sung đối tác địa điểm + nhà xe + xe + cột lịch/đơn + KHÓA NGOẠI (chạy sau database_extensions.sql).
-- Cần có bảng tbldestination, tbtour, lich_khai_hanh, tblbooking (InnoDB).
-- Lỗi «Duplicate column» / «Duplicate foreign key»: bỏ qua (đã áp dụng).
USE `tour_du_lich`;

-- Thứ tự: nha_xe → xe → doi_tac_diem_den (FK thêm ở cuối file)
CREATE TABLE IF NOT EXISTS `nha_xe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ten_nha_xe` varchar(255) NOT NULL,
  `dien_thoai` varchar(50) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `xe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nha_xe_id` int(11) NOT NULL,
  `bien_so` varchar(30) NOT NULL,
  `ten_loai_xe` varchar(120) DEFAULT NULL,
  `so_ghe` int(11) NOT NULL DEFAULT 29,
  `trang_thai` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_xe_nha` (`nha_xe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `doi_tac_diem_den` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dest_id` int(11) NOT NULL,
  `ten_don_vi` varchar(255) NOT NULL,
  `nguoi_lien_he` varchar(120) DEFAULT NULL,
  `dien_thoai` varchar(50) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_dtd_dest` (`dest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `lich_khai_hanh` ADD COLUMN `doi_tac_diem_id` int(11) DEFAULT NULL AFTER `so_cho_con`;
ALTER TABLE `lich_khai_hanh` ADD COLUMN `xe_id` int(11) DEFAULT NULL AFTER `doi_tac_diem_id`;
ALTER TABLE `lich_khai_hanh` ADD COLUMN `diem_don_chinh` varchar(500) DEFAULT NULL AFTER `xe_id`;
ALTER TABLE `lich_khai_hanh` ADD COLUMN `gio_xuat_phat` varchar(10) DEFAULT NULL AFTER `diem_don_chinh`;

ALTER TABLE `tblbooking` ADD COLUMN `schedule_id` int(11) DEFAULT NULL AFTER `Tour_ID`;

-- Dọn tham chiếu không hợp lệ trước khi thêm FK
UPDATE `tblbooking` b
  LEFT JOIN `lich_khai_hanh` lk ON b.`schedule_id` = lk.`id`
  SET b.`schedule_id` = NULL
  WHERE b.`schedule_id` IS NOT NULL AND lk.`id` IS NULL;

UPDATE `lich_khai_hanh` lk
  LEFT JOIN `xe` x ON lk.`xe_id` = x.`id`
  SET lk.`xe_id` = NULL
  WHERE lk.`xe_id` IS NOT NULL AND x.`id` IS NULL;

UPDATE `lich_khai_hanh` lk
  LEFT JOIN `doi_tac_diem_den` d ON lk.`doi_tac_diem_id` = d.`id`
  SET lk.`doi_tac_diem_id` = NULL
  WHERE lk.`doi_tac_diem_id` IS NOT NULL AND d.`id` IS NULL;

DELETE x FROM `xe` x
  LEFT JOIN `nha_xe` n ON x.`nha_xe_id` = n.`id`
  WHERE n.`id` IS NULL;

DELETE d FROM `doi_tac_diem_den` d
  LEFT JOIN `tbldestination` t ON d.`dest_id` = t.`Dest_ID`
  WHERE t.`Dest_ID` IS NULL;

-- Khóa ngoại (MySQL không có IF NOT EXISTS cho constraint — chạy lỗi trùng = đã có)
ALTER TABLE `doi_tac_diem_den` ADD CONSTRAINT `fk_dtd_dest` FOREIGN KEY (`dest_id`) REFERENCES `tbldestination` (`Dest_ID`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `xe` ADD CONSTRAINT `fk_xe_nha_xe` FOREIGN KEY (`nha_xe_id`) REFERENCES `nha_xe` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE `lich_khai_hanh` ADD CONSTRAINT `fk_lich_xe` FOREIGN KEY (`xe_id`) REFERENCES `xe` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `lich_khai_hanh` ADD CONSTRAINT `fk_lich_doi_tac` FOREIGN KEY (`doi_tac_diem_id`) REFERENCES `doi_tac_diem_den` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `tblbooking` ADD CONSTRAINT `fk_booking_lich` FOREIGN KEY (`schedule_id`) REFERENCES `lich_khai_hanh` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
