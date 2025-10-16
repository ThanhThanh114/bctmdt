<?php
// Sửa toàn bộ vấn đề: Xóa UNIQUE constraint và migrate dữ liệu

$pdo = new PDO('mysql:host=127.0.0.1;dbname=tmdt_bc;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo str_repeat("=", 70) . "\n";
echo "🔧 SỬA TOÀN BỘ HỆ THỐNG ĐẶT VÉ\n";
echo str_repeat("=", 70) . "\n\n";

try {
    $pdo->beginTransaction();

    // BƯỚC 1: Xóa UNIQUE constraint trên ma_ve
    echo "BƯỚC 1: XÓA UNIQUE CONSTRAINT\n";
    echo str_repeat("-", 70) . "\n";

    $sql = "ALTER TABLE dat_ve DROP INDEX ma_ve";
    $pdo->exec($sql);
    echo "✅ Đã xóa UNIQUE constraint trên cột ma_ve\n";
    echo "   → Bây giờ nhiều rows có thể có cùng ma_ve\n\n";

    // BƯỚC 2: Migrate dữ liệu cũ
    echo "BƯỚC 2: MIGRATE DỮ LIỆU CŨ\n";
    echo str_repeat("-", 70) . "\n";

    // Lấy tất cả bookings có nhiều ghế trong 1 row
    $sql = "SELECT * FROM dat_ve WHERE so_ghe LIKE '%,%'";
    $stmt = $pdo->query($sql);
    $oldBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Tìm thấy " . count($oldBookings) . " booking cần migrate\n\n";

    $migratedCount = 0;
    $totalSeats = 0;

    foreach ($oldBookings as $booking) {
        echo "Xử lý {$booking['ma_ve']}: '{$booking['so_ghe']}'\n";

        // Split ghế
        $seats = array_map('trim', explode(',', $booking['so_ghe']));
        $seats = array_filter($seats);

        echo "  → Tách thành " . count($seats) . " ghế: " . implode(', ', $seats) . "\n";

        // Tạo row mới cho mỗi ghế
        $insertSql = "INSERT INTO dat_ve (user_id, chuyen_xe_id, ma_ve, so_ghe, ngay_dat, trang_thai) 
                      VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $pdo->prepare($insertSql);

        foreach ($seats as $seat) {
            // Chuẩn hóa format: A1 -> A01, B2 -> B02
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

            echo "    ✓ Tạo row: $seat\n";
            $totalSeats++;
        }

        // Xóa row cũ
        $deleteSql = "DELETE FROM dat_ve WHERE id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute([$booking['id']]);
        echo "    ✓ Xóa row cũ (ID: {$booking['id']})\n\n";

        $migratedCount++;
    }

    $pdo->commit();

    echo "✅ HOÀN TẤT: Migrated $migratedCount bookings → $totalSeats rows riêng lẻ\n\n";

    // BƯỚC 3: Kiểm tra lại
    echo "BƯỚC 3: KIỂM TRA LẠI\n";
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

    // BƯỚC 4: Test query
    echo "\n\nBƯỚC 4: TEST QUERY LẤY GHẾ\n";
    echo str_repeat("-", 70) . "\n";

    $sql = "SELECT so_ghe FROM dat_ve 
            WHERE chuyen_xe_id = 1 
            AND trang_thai IN ('Đã đặt', 'Đã thanh toán')";
    $stmt = $pdo->query($sql);
    $bookedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Ghế đã đặt: " . implode(', ', $bookedSeats) . "\n";
    echo "Số lượng: " . count($bookedSeats) . " ghế\n\n";

    $allValid = true;
    foreach ($bookedSeats as $seat) {
        if (!preg_match('/^[AB]\d{2}$/', $seat)) {
            echo "❌ Format sai: $seat\n";
            $allValid = false;
        }
    }

    if ($allValid) {
        echo "✅ Tất cả ghế đều đúng format!\n";
    }

    echo "\nTest in_array():\n";
    $testSeats = ['A01', 'A02', 'A10'];
    foreach ($testSeats as $seat) {
        $found = in_array($seat, $bookedSeats);
        echo "  in_array('$seat') = " . ($found ? "TRUE ✓" : "FALSE") . "\n";
    }

    echo "\n" . str_repeat("=", 70) . "\n";
    echo "✅ HOÀN THÀNH TẤT CẢ!\n";
    echo str_repeat("=", 70) . "\n";

} catch (\Exception $e) {
    $pdo->rollBack();
    echo "❌ LỖI: " . $e->getMessage() . "\n";
}