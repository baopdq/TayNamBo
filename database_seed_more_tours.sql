-- Thêm tour mẫu Tây Nam Bộ + ảnh đánh số trong thư mục images/ (4.jpg … 8.jpg).
-- Ảnh 1–3 dành cho 3 tour gốc trong database.sql — gán gallery bằng database_apply_images_1_to_26.sql.
-- Thứ tự: database.sql → database_extensions.sql → file này → database_apply_images_1_to_26.sql
-- Có thể chạy nhiều lần: chỉ chèn khi chưa có tour cùng tên.

USE `tour_du_lich`;

INSERT INTO `tbtour` (`ten_tour`, `Cat_ID`, `Dest_ID`, `Tour_Image`, `thoi_gian`, `Price_Adult`, `Price_Child`, `Duration`, `Vehicle`, `Description`, `mo_ta`, `itinerary`, `cancellation_policy`, `rating`, `trang_thai`)
SELECT 'Tour Bến Tre - Vườn dừa xanh', 4, 4, 'images/4.jpg', '1 ngày', 890000, 450000, '1 ngày', 'Xe du lịch', 'Tham quan vườn dừa, tìm hiểu nghề kẹo dừa và ẩm thực miệt vườn.', 'Hành trình gọn trong ngày tại xứ dừa Bến Tre: xe đạp hoặc xuồng nhỏ trong vườn, thử kẹo dừa và đặc sản địa phương.', 'Sáng: khởi hành — vườn dừa — làng nghề.\nTrưa: bữa trưa đặc sản.\nChiều: mua sắm nhẹ — kết thúc.', 'Hủy trước 48h: hoàn 80%. Hủy trong 24h: phí 30%.', 4.7, 'active'
FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Bến Tre - Vườn dừa xanh');

INSERT INTO `tbtour` (`ten_tour`, `Cat_ID`, `Dest_ID`, `Tour_Image`, `thoi_gian`, `Price_Adult`, `Price_Child`, `Duration`, `Vehicle`, `Description`, `mo_ta`, `itinerary`, `cancellation_policy`, `rating`, `trang_thai`)
SELECT 'Tour Đồng Tháp - Sen hồng và làng quê', 4, 6, 'images/5.jpg', '2 ngày', 3290000, 1650000, '2 ngày', 'Xe du lịch', 'Đồng Tháp mùa sen, nhịp sống chậm ven kênh rạch.', 'Ghé đồng sen, làng nghề, chợ nổi nhỏ và nhà vườn; phù hợp gia đình và nhóm nhỏ.', 'Ngày 1: đồng sen — nhà vườn — tối nghỉ địa phương.\nNgày 2: chợ quê — trải nghiệm ẩm thực — về.', 'Hủy trước 72h: hoàn 70%. Dịp lễ: phụ thu theo thông báo.', 4.8, 'active'
FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Đồng Tháp - Sen hồng và làng quê');

INSERT INTO `tbtour` (`ten_tour`, `Cat_ID`, `Dest_ID`, `Tour_Image`, `thoi_gian`, `Price_Adult`, `Price_Child`, `Duration`, `Vehicle`, `Description`, `mo_ta`, `itinerary`, `cancellation_policy`, `rating`, `trang_thai`)
SELECT 'Tour Vĩnh Long - Homestay miệt vườn', 4, 8, 'images/6.jpg', '2 ngày', 2790000, 1400000, '2 ngày', 'Xe du lịch', 'Nghỉ homestay, học làm bánh, đi chợ nổi nhỏ.', 'Trải nghiệm sống cùng nhà vườn, ăn đặc sản dân dã, đạp xe ven kênh.', 'Ngày 1: homestay — làm bánh — tối giao lưu.\nNgày 2: chợ nổi — vườn trái cây — về.', 'Hủy trước 48h: hoàn 60%.', 4.6, 'active'
FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Vĩnh Long - Homestay miệt vườn');

INSERT INTO `tbtour` (`ten_tour`, `Cat_ID`, `Dest_ID`, `Tour_Image`, `thoi_gian`, `Price_Adult`, `Price_Child`, `Duration`, `Vehicle`, `Description`, `mo_ta`, `itinerary`, `cancellation_policy`, `rating`, `trang_thai`)
SELECT 'Tour Kiên Giang - Biển đảo và hoàng hôn', 3, 5, 'images/7.jpg', '3 ngày', 5890000, 2900000, '3 ngày', 'Xe du lịch, tàu cao tốc (theo chương trình)', 'Tuyến biển đảo Kiên Giang: biển xanh, hải sản tươi, góc chụp hoàng hôn.', 'Lịch trình tham khảo theo tuyến Phú Quốc / Hà Tiên tùy mùa; liên hệ để xác nhận phương tiện.', 'Ngày 1: ra đảo — nhận phòng — tự do tắm biển.\nNgày 2: tham quan — hải sản.\nNgày 3: mua sắm — về đất liền.', 'Theo điều khoản kỳ nghỉ biển: hủy sớm hoàn cao hơn.', 4.9, 'active'
FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Kiên Giang - Biển đảo và hoàng hôn');

INSERT INTO `tbtour` (`ten_tour`, `Cat_ID`, `Dest_ID`, `Tour_Image`, `thoi_gian`, `Price_Adult`, `Price_Child`, `Duration`, `Vehicle`, `Description`, `mo_ta`, `itinerary`, `cancellation_policy`, `rating`, `trang_thai`)
SELECT 'Tour Tiền Giang - Mỹ Tho và cồn Phụng', 1, 7, 'images/8.jpg', '1 ngày', 1190000, 0, '1 ngày', 'Xe du lịch, tàu thuyền', 'Một ngày sông nước: cồn, vườn trái, đờn ca tài tử.', 'Khởi hành Mỹ Tho, thuyền nhỏ vào cồn, ăn trái cây miệt vườn, nghe nghệ thuật địa phương.', 'Sáng: tập trung — thuyền — cồn Phụng.\nTrưa: bữa trưa đặc sản.\nChiều: về TP.HCM / Cần Thơ theo chương trình.', 'Hủy trước 24h: hoàn 50%.', 4.5, 'active'
FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Tiền Giang - Mỹ Tho và cồn Phụng');

-- Lịch khởi hành (một ngày mỗi tour mới; không trùng nếu đã có cùng tour_id + ngày)
INSERT INTO `lich_khai_hanh` (`tour_id`, `ngay_khai_hanh`, `so_cho_con`)
SELECT t.`Tour_ID`, DATE_ADD(CURDATE(), INTERVAL 11 DAY), 24 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Bến Tre - Vườn dừa xanh'
AND NOT EXISTS (SELECT 1 FROM `lich_khai_hanh` lk WHERE lk.`tour_id` = t.`Tour_ID` AND lk.`ngay_khai_hanh` = DATE_ADD(CURDATE(), INTERVAL 11 DAY));

INSERT INTO `lich_khai_hanh` (`tour_id`, `ngay_khai_hanh`, `so_cho_con`)
SELECT t.`Tour_ID`, DATE_ADD(CURDATE(), INTERVAL 18 DAY), 20 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Đồng Tháp - Sen hồng và làng quê'
AND NOT EXISTS (SELECT 1 FROM `lich_khai_hanh` lk WHERE lk.`tour_id` = t.`Tour_ID` AND lk.`ngay_khai_hanh` = DATE_ADD(CURDATE(), INTERVAL 18 DAY));

INSERT INTO `lich_khai_hanh` (`tour_id`, `ngay_khai_hanh`, `so_cho_con`)
SELECT t.`Tour_ID`, DATE_ADD(CURDATE(), INTERVAL 9 DAY), 16 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Vĩnh Long - Homestay miệt vườn'
AND NOT EXISTS (SELECT 1 FROM `lich_khai_hanh` lk WHERE lk.`tour_id` = t.`Tour_ID` AND lk.`ngay_khai_hanh` = DATE_ADD(CURDATE(), INTERVAL 9 DAY));

INSERT INTO `lich_khai_hanh` (`tour_id`, `ngay_khai_hanh`, `so_cho_con`)
SELECT t.`Tour_ID`, DATE_ADD(CURDATE(), INTERVAL 25 DAY), 30 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Kiên Giang - Biển đảo và hoàng hôn'
AND NOT EXISTS (SELECT 1 FROM `lich_khai_hanh` lk WHERE lk.`tour_id` = t.`Tour_ID` AND lk.`ngay_khai_hanh` = DATE_ADD(CURDATE(), INTERVAL 25 DAY));

INSERT INTO `lich_khai_hanh` (`tour_id`, `ngay_khai_hanh`, `so_cho_con`)
SELECT t.`Tour_ID`, DATE_ADD(CURDATE(), INTERVAL 6 DAY), 35 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Tiền Giang - Mỹ Tho và cồn Phụng'
AND NOT EXISTS (SELECT 1 FROM `lich_khai_hanh` lk WHERE lk.`tour_id` = t.`Tour_ID` AND lk.`ngay_khai_hanh` = DATE_ADD(CURDATE(), INTERVAL 6 DAY));

-- Gallery nhiều ảnh (9–26.jpg): chạy database_apply_images_1_to_26.sql sau file này.
