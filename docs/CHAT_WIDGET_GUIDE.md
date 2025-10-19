# 🤖 FUTA Chat Widget - Hướng Dẫn Hoàn Chỉnh

## 📋 Tổng Quan

Chat Widget AI sử dụng Google Gemini 1.5 Flash để tư vấn khách hàng về dịch vụ đặt vé xe FUTA.

### ✨ Tính Năng

- 💬 Chat realtime với AI
- 🎯 Tư vấn tuyến xe, giá vé, lịch trình
- 📱 Responsive design (desktop + mobile)
- 🔄 Session management
- ⚡ Fast response (Gemini 1.5 Flash)

---

## 🔧 Cấu Hình

### 1. Google AI Studio Setup

**Project Information:**

- Project ID: `gen-lang-client-0650091375`
- Project Name: `tmdt`
- Model: `gemini-1.5-flash`
- API Key: `AIzaSyAf1CCFAqfOowuQfkP0YoFb_PS5N6uJULg`

### 2. File Cấu Trúc

```
bctmdt/
├── app/Http/Controllers/
│   └── ChatController.php          # Laravel controller
├── public/
│   ├── api/
│   │   └── chat.php                # Direct PHP endpoint (fallback)
│   └── assets/js/
│       └── futa-chat.js            # Frontend widget
├── routes/
│   └── web.php                     # Laravel routes
└── .env                            # Environment config
```

### 3. Environment Variables

Thêm vào file `.env`:

```env
# Gemini AI Configuration
GEMINI_API_KEY=AIzaSyAf1CCFAqfOowuQfkP0YoFb_PS5N6uJULg
GEMINI_MODEL=gemini-1.5-flash
```

---

## 🚀 API Endpoints

### 1. Laravel Route (Khuyến nghị)

**POST** `/api/chat`

**Request:**

```json
{
    "message": "Mình muốn đi từ TP.HCM đến Đà Lạt",
    "session_id": "chat_1697712345_abc123"
}
```

**Response (Success):**

```json
{
    "success": true,
    "content": "Chào bạn! 👋 Tuyến TP.HCM - Đà Lạt rất phổ biến...",
    "timestamp": "2025-10-19T10:30:00+07:00",
    "session_id": "chat_1697712345_abc123"
}
```

**Response (Error):**

```json
{
    "success": false,
    "error": "Không thể kết nối đến AI. Vui lòng thử lại sau!"
}
```

### 2. Test Endpoint

**GET** `/api/chat/test`

**Response:**

```json
{
    "success": true,
    "message": "Chat API working",
    "model": "gemini-1.5-flash"
}
```

### 3. Direct PHP (Fallback)

**POST** `/BC_TMDT/api/chat.php`

Tương tự Laravel route nhưng không qua framework.

---

## 💻 Frontend Integration

### Cách Dùng

**1. Include script trong Blade template:**

```blade
{{-- layouts/footer.blade.php --}}
<script src="{{ asset('assets/js/futa-chat.js') }}?v={{ time() }}"></script>
```

**2. Auto-initialize:**

Widget tự động khởi tạo khi DOM ready. Không cần code thêm!

**3. Disable trên trang cụ thể:**

```blade
<body data-no-chat-widget>
    {{-- Chat widget sẽ không hiển thị --}}
</body>
```

hoặc

```blade
<body class="no-chat-widget">
    {{-- Chat widget sẽ không hiển thị --}}
</body>
```

---

## 🎨 Customization

### 1. Thay Đổi System Prompt

**File:** `app/Http/Controllers/ChatController.php`

```php
$systemPrompt = "Bạn là Minh - tư vấn viên AI của FUTA...

[Thay đổi nội dung tại đây]

Câu hỏi khách hàng:";
```

### 2. Điều Chỉnh Gemini Config

```php
'generationConfig' => [
    'temperature' => 0.8,        // Độ sáng tạo (0-1)
    'maxOutputTokens' => 800,    // Độ dài tối đa
    'topP' => 0.95,              // Nucleus sampling
    'topK' => 40                 // Top-k sampling
]
```

### 3. Thay Đổi CSS

**File:** `public/assets/js/futa-chat.js`

Tìm section `addStyles()` và edit:

```css
.futa-chat-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    /* Thay đổi màu gradient */
}

.futa-chat-box {
    width: 380px;
    height: 500px;
    /* Thay đổi kích thước */
}
```

---

## 🔍 Troubleshooting

### Lỗi 404 - Not Found

**Nguyên nhân:** Route chưa được load

**Giải pháp:**

```bash
php artisan route:clear
php artisan route:cache
php artisan serve
```

### Lỗi CORS

**Nguyên nhân:** Cross-origin request blocked

**Giải pháp:** Thêm middleware vào `ChatController`:

```php
public function __construct()
{
    $this->middleware('cors');
}
```

### API Key Invalid

**Nguyên nhân:** API key sai hoặc hết hạn

**Giải pháp:**

1. Vào [Google AI Studio](https://aistudio.google.com/app/apikey)
2. Tạo API key mới
3. Cập nhật trong `.env`:
    ```env
    GEMINI_API_KEY=your_new_api_key_here
    ```
4. Clear config cache:
    ```bash
    php artisan config:clear
    ```

### Chat Widget Không Hiện

**Kiểm tra:**

1. **Console log** (F12):

    ```
    🚀 FUTA Chat Widget script loaded
    🌟 DOM ready, initializing...
    ✅ FUTA Chat Widget initialized successfully!
    ```

2. **Network tab:** Kiểm tra file JS đã load chưa

3. **CSS conflict:** Kiểm tra `z-index` của widget (hiện tại: 999999)

### AI Không Trả Lời

**Kiểm tra:**

1. **Server logs:** `storage/logs/laravel.log`
2. **API response:** F12 → Network → chat request
3. **Quota:** [AI Studio Console](https://aistudio.google.com/)

---

## 📊 Testing

### 1. Test Chat API

```bash
# Windows PowerShell
Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/chat/test" -Method GET
```

**Expected:**

```json
{
    "success": true,
    "message": "Chat API working",
    "model": "gemini-1.5-flash"
}
```

### 2. Test Chat Message

```bash
curl -X POST http://127.0.0.1:8000/api/chat `
  -H "Content-Type: application/json" `
  -d '{"message":"Xin chào"}'
```

### 3. Browser Test

1. Mở trang: `http://127.0.0.1:8000`
2. Click icon chat (💬) góc dưới phải
3. Gửi: "Mình muốn đặt vé từ HCM đi Đà Lạt"
4. Kiểm tra response từ AI

---

## 🔐 Security

### Rate Limiting

Thêm middleware vào route:

```php
Route::post('/api/chat', [ChatController::class, 'chat'])
    ->middleware('throttle:60,1')  // 60 requests/phút
    ->name('chat.api');
```

### Input Validation

ChatController đã validate:

- Message: required, string, max 1000 ký tự
- Session ID: optional, string

### XSS Protection

Frontend dùng `textContent` thay vì `innerHTML` để tránh XSS.

---

## 📈 Performance

### Caching

Có thể cache response cho câu hỏi phổ biến:

```php
use Illuminate\Support\Facades\Cache;

$cacheKey = 'chat_' . md5($message);
$response = Cache::remember($cacheKey, 3600, function() use ($message) {
    // Call Gemini API
});
```

### Timeout

Hiện tại: 30s timeout cho API call. Điều chỉnh nếu cần:

```php
$response = Http::timeout(30)->post($apiUrl, $requestData);
```

---

## 📝 Maintenance

### Update API Key

1. Tạo key mới tại [AI Studio](https://aistudio.google.com/app/apikey)
2. Cập nhật `.env`:
    ```env
    GEMINI_API_KEY=new_key_here
    ```
3. Clear cache:
    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```

### Monitor Usage

- Dashboard: [Google AI Studio](https://aistudio.google.com/)
- Quota: Free tier = 15 requests/phút, 1500 requests/ngày
- Logs: `storage/logs/laravel.log`

### Backup

Quan trọng:

- `.env` file (chứa API key)
- `public/api/chat.php` (fallback endpoint)
- `app/Http/Controllers/ChatController.php`
- `public/assets/js/futa-chat.js`

---

## 🎯 Best Practices

1. **Luôn dùng HTTPS** trong production
2. **Rate limit** để tránh spam
3. **Log errors** để debug
4. **Cache** response cho câu hỏi phổ biến
5. **Validate input** trước khi gửi API
6. **Handle errors** gracefully với UX tốt
7. **Version control** cho widget script
8. **Monitor quota** để tránh hết limit

---

## 📞 Support

- **Hotline FUTA:** 1900 6067
- **Email:** support@futabus.vn
- **Google AI Studio:** https://aistudio.google.com/

---

## 📜 Changelog

### Version 2.0 (Oct 19, 2025)

- ✨ Upgraded to Gemini 1.5 Flash
- 🔄 Added Laravel routing support
- 🎨 Enhanced UI/UX
- 📝 Improved system prompt
- 🔒 Added safety settings
- 🐛 Fixed CORS issues

### Version 1.0 (Sep 26, 2025)

- 🎉 Initial release
- 💬 Basic chat functionality
- 🤖 Gemini Pro integration

---

## ⚖️ License

Proprietary - FUTA Bus Lines Internal Use Only

---

**Được tạo bởi:** GitHub Copilot  
**Ngày cập nhật:** October 19, 2025  
**Project:** tmdt (gen-lang-client-0650091375)
