<?php
// Xóa booking test cũ để test lại từ đầu

$pdo = new PDO('mysql:host=127.0.0.1;dbname=tmdt_bc;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== XÓA BOOKING CŨ ===\n\n";

// Xóa các booking test
$sql = "DELETE FROM dat_ve WHERE user_id = 34 AND trang_thai = 'Đã đặt'";
$affected = $pdo->exec($sql);

echo "✓ Đã xóa $affected booking test\n";
echo "\nBây giờ bạn có thể đặt vé mới để test lại!\n";
echo "Nhớ ghi mã booking vào nội dung chuyển khoản nhé!\n";
