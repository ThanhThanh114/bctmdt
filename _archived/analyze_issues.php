<?php
// Phân tích chi tiết các vấn đề trong hệ thống

$pdo = new PDO('mysql:host=127.0.0.1;dbname=tmdt_bc;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo str_repeat("=", 70) . "\n";
echo "🔍 PHÂN TÍCH CHI TIẾT HỆ THỐNG ĐẶT VÉ\n";
echo str_repeat("=", 70) . "\n\n";

// VẤN ĐỀ 1: Cấu trúc dữ liệu không nhất quán
echo "❌ VẤN ĐỀ 1: CẤU TRÚC DỮ LIỆU KHÔNG NHỤ QUÁN\n";
echo str_repeat("-", 70) . "\n";

$sql = "SELECT * FROM dat_ve WHERE chuyen_xe_id = 1";
$stmt = $pdo->query($sql);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Dữ liệu CŨ trong database:\n";
foreach ($bookings as $booking) {
    echo "  • {$booking['ma_ve']}: so_ghe = '{$booking['so_ghe']}' ({$booking['trang_thai']})\n";
    if (strpos($booking['so_ghe'], ',') !== false) {
        echo "    ⚠️  NHIỀU GHẾ TRONG 1 ROW (format cũ)\n";
    } else {
        echo "    ✓ 1 ghế/1 row (format mới)\n";
    }
}

echo "\nCode PHP HIỆN TẠI đang tạo:\n";
echo "  • Mỗi ghế = 1 row riêng\n";
echo "  • VD: Chọn A01, A02 → Tạo 2 rows với cùng ma_ve\n\n";

echo "⚠️  MÂU THUẪN:\n";
echo "  • Database CŨ: Nhiều ghế trong 1 row (A01, A02, A03)\n";
echo "  • Code MỚI: Mỗi ghế 1 row riêng\n";
echo "  • Query lấy ghế đã đặt: pluck('so_ghe') → Lấy từng row\n\n";

// VẤN ĐỀ 2: Kiểm tra ghế đã đặt
echo "\n❌ VẤN ĐỀ 2: QUERY LẤY GHẾ ĐÃ ĐẶT\n";
echo str_repeat("-", 70) . "\n";

// Query hiện tại
$sql = "SELECT so_ghe FROM dat_ve 
        WHERE chuyen_xe_id = 1 
        AND trang_thai IN ('Đã đặt', 'Đã thanh toán')";
$stmt = $pdo->query($sql);
$bookedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "Query hiện tại:\n";
echo "  SELECT so_ghe FROM dat_ve WHERE ... AND trang_thai IN ('Đã đặt', 'Đã thanh toán')\n\n";

echo "Kết quả:\n";
foreach ($bookedSeats as $seat) {
    echo "  • '$seat'\n";
    if (strpos($seat, ',') !== false) {
        echo "    ⚠️  Đây là STRING chứa nhiều ghế, không phải 1 ghế!\n";
        echo "    ⚠️  in_array('A01', ['A01, A02, A03']) = FALSE\n";
        echo "    ⚠️  Ghế sẽ KHÔNG được đánh dấu đã đặt trên UI!\n";
    }
}

// VẤN ĐỀ 3: Timestamps
echo "\n\n❌ VẤN ĐỀ 3: TIMESTAMPS\n";
echo str_repeat("-", 70) . "\n";
echo "Database có cột: ngay_dat (timestamp)\n";
echo "Model DatVe.php: public \$timestamps = false;\n";
echo "⚠️  Laravel sẽ KHÔNG tự động set ngay_dat khi create()\n";
echo "⚠️  Phải thêm 'ngay_dat' vào fillable và set manually\n\n";

// VẤN ĐỀ 4: Format ghế
echo "❌ VẤN ĐỀ 4: FORMAT GHẾ\n";
echo str_repeat("-", 70) . "\n";

$sql = "SELECT DISTINCT so_ghe FROM dat_ve ORDER BY so_ghe";
$stmt = $pdo->query($sql);
$allSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);

$formats = [];
foreach ($allSeats as $seat) {
    if (strpos($seat, ',') !== false) {
        $formats['Nhiều ghế/1 row (cũ)'][] = $seat;
    } else if (preg_match('/^[AB]\d{2}$/', $seat)) {
        $formats['1 ghế/1 row (mới, đúng)'][] = $seat;
    } else {
        $formats['Format lỗi'][] = $seat;
    }
}

foreach ($formats as $type => $seats) {
    echo "$type:\n";
    foreach (array_slice($seats, 0, 5) as $seat) {
        echo "  • $seat\n";
    }
    if (count($seats) > 5) {
        echo "  ... và " . (count($seats) - 5) . " ghế khác\n";
    }
    echo "\n";
}

// GIẢI PHÁP
echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ GIẢI PHÁP\n";
echo str_repeat("=", 70) . "\n\n";

echo "1️⃣ SỬA QUERY LẤY GHẾ ĐÃ ĐẶT:\n";
echo "   Nếu giữ format CŨ (nhiều ghế/1 row):\n";
echo "   • Cần split string: FIND_IN_SET() hoặc xử lý PHP\n\n";
echo "   Nếu chuyển sang format MỚI (1 ghế/1 row) - KHUYẾN NGHỊ:\n";
echo "   • Migrate dữ liệu cũ\n";
echo "   • Code hiện tại đã đúng\n\n";

echo "2️⃣ SỬA MODEL DatVe:\n";
echo "   • Thêm 'ngay_dat' vào fillable\n";
echo "   • Hoặc dùng DB default value (hiện tại có: current_timestamp())\n\n";

echo "3️⃣ MIGRATE DỮ LIỆU CŨ:\n";
echo "   • Split 'A01, A02, A03' thành 3 rows riêng\n";
echo "   • Update lại tất cả bookings cũ\n\n";

// Test với dữ liệu hiện tại
echo "🧪 TEST VỚI DỮ LIỆU HIỆN TẠI:\n";
echo str_repeat("-", 70) . "\n";

$testSeats = ['A01', 'A02', 'A03'];
$testBookedString = 'A01, A02,A03'; // Format cũ

echo "Ghế cần check: " . implode(', ', $testSeats) . "\n";
echo "Dữ liệu trong DB: '$testBookedString'\n\n";

foreach ($testSeats as $seat) {
    $found = in_array($seat, [$testBookedString]);
    echo "  in_array('$seat', ['$testBookedString']) = " . ($found ? 'TRUE ✓' : 'FALSE ❌') . "\n";
}

echo "\n⚠️  Kết luận: Ghế sẽ KHÔNG được đánh dấu đã đặt!\n";
