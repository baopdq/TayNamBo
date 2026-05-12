<?php
/**
 * Nhập CSDL lần đầu (không cần database tour_du_lich đã tồn tại).
 * Trình duyệt: http://localhost/DuLichTayNamBo/import_db.php
 */
$baseDir = __DIR__;
require_once $baseDir . '/includes/config.php';

function runSqlFile(mysqli $conn, string $path): void {
    if (!is_readable($path)) {
        throw new RuntimeException('Không đọc được file: ' . $path);
    }
    $sql = file_get_contents($path);
    if ($conn->multi_query($sql)) {
        do {
            if ($r = $conn->store_result()) {
                $r->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    }
    if ($conn->errno) {
        throw new RuntimeException($conn->error);
    }
}

header('Content-Type: text/html; charset=utf-8');

try {
    $server = mysqli_init();
    if ($server === false) {
        throw new RuntimeException('Không khởi tạo được MySQLi');
    }
    $server->options(MYSQLI_INIT_COMMAND, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    if (!$server->real_connect(DB_HOST, DB_USER, DB_PASS)) {
        throw new RuntimeException('Kết nối MySQL thất bại: ' . $server->connect_error);
    }
    $server->set_charset('utf8mb4');
    @$server->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

    runSqlFile($server, $baseDir . '/database.sql');
    echo '<p>✅ Đã chạy <code>database.sql</code></p>';

    $server->select_db(DB_NAME);
    runSqlFile($server, $baseDir . '/database_extensions.sql');
    echo '<p>✅ Đã chạy <code>database_extensions.sql</code></p>';

    $server->close();
    echo '<p><strong>Hoàn tất.</strong> Bạn có thể tải lại trang chủ. (Nên xóa hoặc bảo vệ file <code>import_db.php</code> trên máy chủ thật.)</p>';
} catch (Throwable $e) {
    echo '<p style="color:#c00"><strong>Lỗi:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p>Nếu báo cột đã tồn tại, có thể bỏ qua hoặc chỉnh <code>database_extensions.sql</code>.</p>';
}
