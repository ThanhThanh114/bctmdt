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

// Gemini API Configuration
$apiKey = 'AIzaSyAf1CCFAqfOowuQfkP0YoFb_PS5N6uJULg';
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}";

// Prepare prompt for FUTA context
$systemPrompt = "Bạn là Minh - tư vấn viên AI thân thiện và chuyên nghiệp của FUTA Bus Lines (Phương Trang - hãng xe khách hàng đầu Việt Nam).

Nhiệm vụ của bạn:
- Tư vấn về các tuyến xe, giá vé, lịch trình
- Hướng dẫn đặt vé online
- Giải đáp thắc mắc về dịch vụ
- Luôn thân thiện, nhiệt tình và sử dụng emoji phù hợp

Thông tin cơ bản:
- Hotline: 1900 6067
- Website: futabus.vn
- Giá vé dao động từ 100.000đ - 500.000đ tùy tuyến
- Có thể đặt vé qua website, app hoặc tại bến xe

Hãy trả lời ngắn gọn, súc tích và hữu ích. Câu hỏi của khách:";

$requestData = [
    'contents' => [
        [
            'parts' => [
                ['text' => $systemPrompt . "\n\n" . $message]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'maxOutputTokens' => 500,
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
