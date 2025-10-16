<?php
// Script sửa tất cả vấn đề trong hệ thống đặt vé

$pdo = new PDO('mysql:host=127.0.0.1;dbname=tmdt_bc;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo str_repeat("=", 70) . "\n";
echo "🔧 SỬA CÁC VẤN ĐỀ HỆ THỐNG ĐẶT VÉ\n";
echo str_repeat("=", 70) . "\n\n";

// BƯỚC 1: Migrate dữ liệu cũ sang format mới
echo "BƯỚC 1: MIGRATE DỮ LIỆU CŨ\n";
echo str_repeat("-", 70) . "\n";

$pdo->beginTransaction();

try {
    // Lấy tất cả bookings có nhiều ghế trong 1 row
    $sql = "SELECT * FROM dat_ve WHERE so_ghe LIKE '%,%'";
    $stmt = $pdo->query($sql);
    $oldBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Tìm thấy " . count($oldBookings) . " booking cần migrate\n\n";

    $migratedCount = 0;
    $newRows = [];

    // Tạo bảng tạm để lưu ID cần xóa
    $idsToDelete = [];

    foreach ($oldBookings as $booking) {
        echo "Xử lý {$booking['ma_ve']}: '{$booking['so_ghe']}'\n";

        // Split ghế: "A01, A02,A03" -> ['A01', 'A02', 'A03']
        $seats = array_map('trim', explode(',', $booking['so_ghe']));
        $seats = array_filter($seats); // Remove empty

        echo "  → Tách thành: " . implode(', ', $seats) . "\n";

        // Lưu ID để xóa sau
        $idsToDelete[] = $booking['id'];

        // Tạo row mới cho mỗi ghế TRƯỚC KHI xóa
        $insertSql = "INSERT INTO dat_ve (user_id, chuyen_xe_id, ma_ve, so_ghe, ngay_dat, trang_thai) 
                      VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $pdo->prepare($insertSql);

        foreach ($seats as $seat) {
            // Chuẩn hóa format ghế: A1 -> A01, B2 -> B02
            $seat = strtoupper(trim($seat));
            if (preg_match('/^([AB])(\d{1})$/', $seat, $matches)) {
                $seat = $matches[1] . '0' . $matches[2];
            }

            $insertStmt->execute([
                $booking['user_id'],
                $booking['chuyen_xe_id'],
                $booking['ma_ve'],
                $seat,
                $booking['ngay_dat'],
                $booking['trang_thai']
            ]);

            $newRows[] = $seat;
            echo "    ✓ Tạo row mới: $seat\n";
        }

        $migratedCount++;
        echo "\n";
    }

    // Xóa tất cả rows cũ sau khi đã tạo mới
    if (!empty($idsToDelete)) {
        $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));
        $deleteSql = "DELETE FROM dat_ve WHERE id IN ($placeholders)";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute($idsToDelete);
        echo "✓ Đã xóa " . count($idsToDelete) . " rows cũ\n";
    }

    $pdo->commit();

    echo "✅ HOÀN TẤT: Migrated $migratedCount bookings\n";
    echo "✅ Tạo mới " . count($newRows) . " rows riêng lẻ\n\n";

} catch (\Exception $e) {
    $pdo->rollBack();
    echo "❌ LỖI: " . $e->getMessage() . "\n\n";
}

// BƯỚC 2: Kiểm tra lại kết quả
echo "\nBƯỚC 2: KIỂM TRA LẠI KẾT QUẢ\n";
echo str_repeat("-", 70) . "\n";

$sql = "SELECT * FROM dat_ve WHERE chuyen_xe_id = 1 ORDER BY ma_ve, so_ghe";
$stmt = $pdo->query($sql);
$allBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($allBookings as $booking) {
    $code = $booking['ma_ve'];
    if (!isset($grouped[$code])) {
        $grouped[$code] = [
            'code' => $code,
            'status' => $booking['trang_thai'],
            'seats' => []
        ];
    }
    $grouped[$code]['seats'][] = $booking['so_ghe'];
}

foreach ($grouped as $booking) {
    $icon = match ($booking['status']) {
        'Đã đặt' => '⏳',
        'Đã thanh toán' => '✅',
        'Đã hủy' => '❌',
        default => '❓'
    };
    echo "$icon {$booking['code']}: " . implode(', ', $booking['seats']) . " ({$booking['status']})\n";
}

// BƯỚC 3: Test query lấy ghế đã đặt
echo "\n\nBƯỚC 3: TEST QUERY LẤY GHẾ ĐÃ ĐẶT\n";
echo str_repeat("-", 70) . "\n";

$sql = "SELECT so_ghe FROM dat_ve 
        WHERE chuyen_xe_id = 1 
        AND trang_thai IN ('Đã đặt', 'Đã thanh toán')";
$stmt = $pdo->query($sql);
$bookedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "Ghế đã đặt:\n";
foreach ($bookedSeats as $seat) {
    echo "  • $seat ";
    if (preg_match('/^[AB]\d{2}$/', $seat)) {
        echo "✓ (format đúng)\n";
    } else {
        echo "❌ (format sai: '$seat')\n";
    }
}

echo "\nTest in_array():\n";
$testSeats = ['A01', 'A02', 'A10'];
foreach ($testSeats as $seat) {
    $found = in_array($seat, $bookedSeats);
    echo "  in_array('$seat', bookedSeats) = " . ($found ? "TRUE ✓" : "FALSE") . "\n";
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ HOÀN TẤT TẤT CẢ\n";
echo str_repeat("=", 70) . "\n";
