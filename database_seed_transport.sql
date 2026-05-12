-- Dữ liệu mẫu nhà xe, xe và đối tác địa điểm (chạy sau database_transport_partners.sql — đã có FK dest_id → tbldestination, nha_xe_id → nha_xe).
-- Có thể chạy lại: bỏ qua bản ghi đã tồn tại (theo tên nhà xe / biển số / tên đối tác + dest_id).
USE `tour_du_lich`;

-- ========== Nhà xe ==========
INSERT INTO `nha_xe` (`ten_nha_xe`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 'Công ty CP Vận tải Lữ hành Tây Nam Bộ', '02903888901', 'booking@taynambo.vn', 'Đội xe Cần Thơ — tour miền Tây'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `nha_xe` WHERE `ten_nha_xe` = 'Công ty CP Vận tải Lữ hành Tây Nam Bộ' LIMIT 1);

INSERT INTO `nha_xe` (`ten_nha_xe`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 'Xe du lịch Minh An (Kiên Giang)', '02973881234', 'minhan.kg@gmail.com', 'Rạch Giá — Hà Tiên — U Minh'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `nha_xe` WHERE `ten_nha_xe` = 'Xe du lịch Minh An (Kiên Giang)' LIMIT 1);

INSERT INTO `nha_xe` (`ten_nha_xe`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 'Nhà xe Cửu Long Mekong', '02753887766', 'dichvu@cuulongmekong.vn', 'Sa Đéc — Cần Thơ — chợ nổi'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `nha_xe` WHERE `ten_nha_xe` = 'Nhà xe Cửu Long Mekong' LIMIT 1);

-- ========== Xe (gắn theo tên nhà xe) ==========
INSERT INTO `xe` (`nha_xe_id`, `bien_so`, `ten_loai_xe`, `so_ghe`)
SELECT n.`id`, '51B-123.45', 'Thaco 45 chỗ', 45 FROM `nha_xe` n
WHERE n.`ten_nha_xe` = 'Công ty CP Vận tải Lữ hành Tây Nam Bộ'
  AND NOT EXISTS (SELECT 1 FROM `xe` WHERE `bien_so` = '51B-123.45' LIMIT 1) LIMIT 1;

INSERT INTO `xe` (`nha_xe_id`, `bien_so`, `ten_loai_xe`, `so_ghe`)
SELECT n.`id`, '51B-234.56', 'Ford Transit VIP', 16 FROM `nha_xe` n
WHERE n.`ten_nha_xe` = 'Công ty CP Vận tải Lữ hành Tây Nam Bộ'
  AND NOT EXISTS (SELECT 1 FROM `xe` WHERE `bien_so` = '51B-234.56' LIMIT 1) LIMIT 1;

INSERT INTO `xe` (`nha_xe_id`, `bien_so`, `ten_loai_xe`, `so_ghe`)
SELECT n.`id`, '68H-901.23', 'Univer 29 chỗ', 29 FROM `nha_xe` n
WHERE n.`ten_nha_xe` = 'Xe du lịch Minh An (Kiên Giang)'
  AND NOT EXISTS (SELECT 1 FROM `xe` WHERE `bien_so` = '68H-901.23' LIMIT 1) LIMIT 1;

INSERT INTO `xe` (`nha_xe_id`, `bien_so`, `ten_loai_xe`, `so_ghe`)
SELECT n.`id`, '68L-778.88', 'Samco 34 chỗ', 34 FROM `nha_xe` n
WHERE n.`ten_nha_xe` = 'Xe du lịch Minh An (Kiên Giang)'
  AND NOT EXISTS (SELECT 1 FROM `xe` WHERE `bien_so` = '68L-778.88' LIMIT 1) LIMIT 1;

INSERT INTO `xe` (`nha_xe_id`, `bien_so`, `ten_loai_xe`, `so_ghe`)
SELECT n.`id`, '66B-045.18', 'Hyundai County 29 chỗ', 29 FROM `nha_xe` n
WHERE n.`ten_nha_xe` = 'Nhà xe Cửu Long Mekong'
  AND NOT EXISTS (SELECT 1 FROM `xe` WHERE `bien_so` = '66B-045.18' LIMIT 1) LIMIT 1;

INSERT INTO `xe` (`nha_xe_id`, `bien_so`, `ten_loai_xe`, `so_ghe`)
SELECT n.`id`, '66F-112.00', 'Thaco 16 chỗ', 16 FROM `nha_xe` n
WHERE n.`ten_nha_xe` = 'Nhà xe Cửu Long Mekong'
  AND NOT EXISTS (SELECT 1 FROM `xe` WHERE `bien_so` = '66F-112.00' LIMIT 1) LIMIT 1;

-- ========== Đối tác chủ địa điểm (theo Dest_ID trong database.sql) ==========
INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 1, 'Khu du lịch Mỹ Khánh', 'Chị Lan', '02923895555', 'info@mykhanh.com', 'Ăn trưa, xe điện tham quan'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 1 AND `ten_don_vi` = 'Khu du lịch Mỹ Khánh' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 1, 'Chợ nổi Cái Răng — Ban quản lý', 'Anh Hùng', '02913887700', NULL, 'Canô chợ nổi, vé tập thể'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 1 AND `ten_don_vi` = 'Chợ nổi Cái Răng — Ban quản lý' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 1, 'Homestay Bến Ninh Kiều', 'Cô Mai', '0918123456', 'homestay.ninhkieu@gmail.com', '2–8 phòng, view sông'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 1 AND `ten_don_vi` = 'Homestay Bến Ninh Kiều' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 2, 'Khu du lịch Rừng Tràm Trà Sư', 'Ban hỗ trợ', '02963886622', 'contact@trasu.com.vn', 'Xe điện, xuồng ba lá'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 2 AND `ten_don_vi` = 'Khu du lịch Rừng Tràm Trà Sư' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 2, 'Khách sạn Châu Đốc — Mekong Riverside', 'Lễ tân 24/7', '02963881111', 'sales@mekongriverside.vn', 'Gần chợ Châu Đốc'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 2 AND `ten_don_vi` = 'Khách sạn Châu Đốc — Mekong Riverside' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 3, 'Khu du lịch Đất Mũi', 'Ban quản lý', '02903812299', NULL, 'Cano, cột mốc GPS0001'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 3 AND `ten_don_vi` = 'Khu du lịch Đất Mũi' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 4, 'Nhà vườn Vĩnh Kim (Bến Tre)', 'Chú Tám', '02753889900', 'vườn.vinhtam@gmail.com', 'Trải nghiệm dừa, kẹo dừa'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 4 AND `ten_don_vi` = 'Nhà vườn Vĩnh Kim (Bến Tre)' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 5, 'Nhà hàng Hải sản Hà Tiên', 'Chị Bé', '02973889988', NULL, 'Ăn tối nhóm lớn'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 5 AND `ten_don_vi` = 'Nhà hàng Hải sản Hà Tiên' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 6, 'Làng hoa Sa Đéc', 'Hợp tác xã du lịch', '02773885544', 'langhoa.sadec@yahoo.com', 'Tham quan vườn kiểng'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 6 AND `ten_don_vi` = 'Làng hoa Sa Đéc' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 7, 'KDL Thới Sơn (Tiền Giang)', 'Điều hành tour', '02733882200', 'thoison@tieniang.vn', 'Đò ba lá, kẹo dừa, đàn ca'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 7 AND `ten_don_vi` = 'KDL Thới Sơn (Tiền Giang)' LIMIT 1);

INSERT INTO `doi_tac_diem_den` (`dest_id`, `ten_don_vi`, `nguoi_lien_he`, `dien_thoai`, `email`, `ghi_chu`)
SELECT 8, 'Làng nghề gốm Mang Thít', 'Anh Thọ', '02703881122', NULL, 'Workshop gốm 1–2 giờ'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `doi_tac_diem_den` WHERE `dest_id` = 8 AND `ten_don_vi` = 'Làng nghề gốm Mang Thít' LIMIT 1);
