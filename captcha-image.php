<?php
/**
 * CAPTCHA — URL gốc (tránh một số cấu hình chặn thư mục /php/).
 */
define('NO_HTML_CONTENT_TYPE', true);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/captcha-image-output.php';
captcha_emit_image_response();
