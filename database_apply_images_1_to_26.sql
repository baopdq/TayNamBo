-- Chạy sau database.sql + database_extensions.sql (+ tùy chọn database_seed_more_tours.sql).
-- Gán images/1.jpg … images/8.jpg làm ảnh bìa và images/9.jpg … 26.jpg vào gallery (bảng tbtour_images).
-- Chạy sau database.sql + database_extensions.sql (cần bảng tbtour_images).
-- Có thể chạy lại: mỗi INSERT kiểm tra NOT EXISTS theo tour_id + image_path.

USE `tour_du_lich`;

-- Ba tour gốc (Tour_ID 1, 2, 3 trong database.sql mẫu): ảnh bìa 1.jpg, 2.jpg, 3.jpg
UPDATE `tbtour` SET `Tour_Image` = 'images/1.jpg' WHERE `Tour_ID` = 1;
UPDATE `tbtour` SET `Tour_Image` = 'images/2.jpg' WHERE `Tour_ID` = 2;
UPDATE `tbtour` SET `Tour_Image` = 'images/3.jpg' WHERE `Tour_ID` = 3;

-- Năm tour thêm (tên cố định từ database_seed_more_tours.sql): đồng bộ bìa 4–8.jpg
UPDATE `tbtour` SET `Tour_Image` = 'images/4.jpg' WHERE `ten_tour` = 'Tour Bến Tre - Vườn dừa xanh';
UPDATE `tbtour` SET `Tour_Image` = 'images/5.jpg' WHERE `ten_tour` = 'Tour Đồng Tháp - Sen hồng và làng quê';
UPDATE `tbtour` SET `Tour_Image` = 'images/6.jpg' WHERE `ten_tour` = 'Tour Vĩnh Long - Homestay miệt vườn';
UPDATE `tbtour` SET `Tour_Image` = 'images/7.jpg' WHERE `ten_tour` = 'Tour Kiên Giang - Biển đảo và hoàng hôn';
UPDATE `tbtour` SET `Tour_Image` = 'images/8.jpg' WHERE `ten_tour` = 'Tour Tiền Giang - Mỹ Tho và cồn Phụng';

-- Gallery: images/9.jpg … 26.jpg chia cho 8 tour (3 gốc + 5 tên trên)
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 1, 'images/9.jpg', 1 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 1 AND g.`image_path` = 'images/9.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 1, 'images/10.jpg', 2 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 1 AND g.`image_path` = 'images/10.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 1, 'images/11.jpg', 3 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 1 AND g.`image_path` = 'images/11.jpg');

INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 2, 'images/12.jpg', 1 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 2 AND g.`image_path` = 'images/12.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 2, 'images/13.jpg', 2 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 2 AND g.`image_path` = 'images/13.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 2, 'images/14.jpg', 3 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 2 AND g.`image_path` = 'images/14.jpg');

INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 3, 'images/15.jpg', 1 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 3 AND g.`image_path` = 'images/15.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 3, 'images/16.jpg', 2 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 3 AND g.`image_path` = 'images/16.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT 3, 'images/17.jpg', 3 FROM (SELECT 1 AS `_`) `_` WHERE NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = 3 AND g.`image_path` = 'images/17.jpg');

INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/18.jpg', 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Bến Tre - Vườn dừa xanh'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/18.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/19.jpg', 2 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Bến Tre - Vườn dừa xanh'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/19.jpg');

INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/20.jpg', 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Đồng Tháp - Sen hồng và làng quê'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/20.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/21.jpg', 2 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Đồng Tháp - Sen hồng và làng quê'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/21.jpg');

INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/22.jpg', 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Vĩnh Long - Homestay miệt vườn'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/22.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/23.jpg', 2 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Vĩnh Long - Homestay miệt vườn'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/23.jpg');

INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/24.jpg', 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Kiên Giang - Biển đảo và hoàng hôn'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/24.jpg');
INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/25.jpg', 2 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Kiên Giang - Biển đảo và hoàng hôn'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/25.jpg');

INSERT INTO `tbtour_images` (`tour_id`, `image_path`, `sort_order`)
SELECT t.`Tour_ID`, 'images/26.jpg', 1 FROM `tbtour` t WHERE t.`ten_tour` = 'Tour Tiền Giang - Mỹ Tho và cồn Phụng'
AND NOT EXISTS (SELECT 1 FROM `tbtour_images` g WHERE g.`tour_id` = t.`Tour_ID` AND g.`image_path` = 'images/26.jpg');
