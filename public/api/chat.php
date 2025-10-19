<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}

// Gemini API Configuration - Updated Oct 2025
// Project: tmdt (gen-lang-client-0650091375)
// Model: gemini-2.5-flash (latest, fastest)
$apiKey = getenv('GEMINI_API_KEY') ?: 'AIzaSyAf1CCFAqfOowuQfkP0YoFb_PS5N6uJULg';
$apiUrl = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}";

// Prepare enhanced prompt for FUTA context
$systemPrompt = "Bạn là Minh - tư vấn viên AI thân thiện của FUTA Bus Lines.

🎯 NHIỆM VỤ:
- Tư vấn tuyến xe, giá vé, lịch trình đi từ Hà Nội, TP.HCM đến các tỉnh
- Hướng dẫn đặt vé online trên website
- Giải đáp về chính sách hủy vé, đổi vé, hoàn tiền
- Tư vấn khuyến mãi, ưu đãi cho khách hàng thân thiết

📋 THÔNG TIN FUTA:
- Hotline hỗ trợ: 1900 6067 (24/7)
- Website: futabus.vn
- Giá vé: 100.000đ - 500.000đ/vé (tùy tuyến)
- Đặt vé: Website, App FUTA, Tại bến xe
- Tuyến hot: HCM-Đà Lạt, HCM-Nha Trang, HN-Hải Phòng, HN-Vinh

🚌 LOẠI XE:
- Giường nằm cao cấp (limousine)
- Ghế ngồi phòng đôi VIP
- Xe khách thường (ghế ngồi)

💳 THANH TOÁN:
- Chuyển khoản ngân hàng
- Ví điện tử (Momo, ZaloPay)
- Thanh toán tại bến

🎁 ƯU ĐÃI:
- Giảm 10% cho khách đặt vé lần đầu
- Tích điểm đổi quà cho khách thân thiết
- Giảm giá nhóm từ 5 người trở lên

📌 LƯU Ý:
- Trả lời ngắn gọn, dễ hiểu, có emoji
- Nếu không chắc thông tin → gợi ý gọi Hotline 1900 6067
- Luôn lịch sự, nhiệt tình

Câu hỏi khách hàng:";

$requestData = [
    'contents' => [
        [
            'parts' => [
                ['text' => $systemPrompt . "\n\n" . $message]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.8,
        'topP' => 0.95,
        'topK' => 40,
        'maxOutputTokens' => 800,
        'stopSequences' => []
    ],
    'safetySettings' => [
        [
            'category' => 'HARM_CATEGORY_HARASSMENT',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ],
        [
            'category' => 'HARM_CATEGORY_HATE_SPEECH',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ],
        [
            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ],
        [
            'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
            'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
        ]
    ]
];

// Call Gemini API
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to connect to AI service',
        'details' => $error
    ]);
    exit;
}

if ($httpCode !== 200) {
    http_response_code($httpCode);
    echo json_encode([
        'error' => 'AI service error',
        'code' => $httpCode,
        'response' => $response
    ]);
    exit;
}

$data = json_decode($response, true);

if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
    $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
    echo json_encode([
        'success' => true,
        'content' => $aiResponse,
        'timestamp' => date('c')
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'error' => 'Invalid AI response',
        'data' => $data
    ]);
}
