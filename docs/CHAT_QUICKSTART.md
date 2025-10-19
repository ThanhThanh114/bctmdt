# ⚡ QUICK START - Chat Widget

## 🚀 Khởi Động

```bash
# 1. Start server
php artisan serve

# 2. Mở browser
http://127.0.0.1:8000

# 3. Click icon 💬 góc dưới phải

# 4. Chat thử!
```

## ✅ Test API

```bash
# Test endpoint
curl http://127.0.0.1:8000/api/chat/test

# Test chat (PowerShell)
$headers = @{"Content-Type"="application/json"}
$body = @{message="xin chào"} | ConvertTo-Json
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/chat" -Method POST -Headers $headers -Body $body
```

## 📁 Files Quan Trọng

- **Backend:** `app/Http/Controllers/ChatController.php`
- **Frontend:** `public/assets/js/futa-chat.js`
- **Routes:** `routes/api.php`
- **Config:** `.env` (chứa GEMINI_API_KEY)

## 🔧 Troubleshoot

### Chat không hiện?

```bash
# Clear cache
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Hard reload browser
Ctrl + Shift + F5
```

### Lỗi 404?

```bash
# Check routes
php artisan route:list --path=api/chat

# Restart server
php artisan serve
```

### AI không trả lời?

- Check `storage/logs/laravel.log`
- Check API quota: https://aistudio.google.com/
- Verify API key trong `.env`

## 📚 Docs Đầy Đủ

👉 `docs/CHAT_WIDGET_GUIDE.md`
👉 `docs/CHAT_UPDATE_SUMMARY.md`

---

**Version:** 2.0  
**Model:** gemini-1.5-flash  
**Project:** tmdt
