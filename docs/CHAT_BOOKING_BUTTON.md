# 🎫 Tích hợp Nút Đặt Vé trong Chat Widget

## 📋 Tổng quan

Chatbot FUTA giờ đây không chỉ trả lời câu hỏi mà còn có **nút "Đặt vé ngay"** cho mỗi chuyến xe. Khi người dùng bấm nút:

1. ✅ Tự động lấy thông tin chuyến xe (điểm đi, điểm đến, ngày, giờ)
2. ✅ Chuyển đến trang đặt vé với thông tin đã điền sẵn
3. ✅ Người dùng chỉ cần chọn ghế và thanh toán

---

## 🎯 Tính năng

### 1️⃣ **Hiển thị Nút Đặt Vé**

Khi chatbot trả lời có kèm thông tin chuyến xe, sẽ hiển thị:

```
┌─────────────────────────────────────┐
│ An Giang → Bắc Ninh                 │
│ 🕐 07:30  💰 400,000đ               │
│                        [Đặt vé →]   │
└─────────────────────────────────────┘
```

### 2️⃣ **Chuyến Gần Nhất**

Nếu không có chuyến trực tiếp, hiển thị **⚠️ Chuyến gần**:

```
┌─────────────────────────────────────┐
│ An Giang → Hưng Yên                 │
│ 🕐 08:00  💰 350,000đ               │
│ ⚠️ Chuyến gần                       │
│                        [Đặt vé →]   │
└─────────────────────────────────────┘
```

---

## 🔧 Cách hoạt động

### Backend (ChatController.php)

**Trả về JSON với dữ liệu chuyến xe:**

```json
{
    "success": true,
    "content": "Dạ có nhiều chuyến An Giang - Bắc Ninh...",
    "routes": [
        {
            "diem_di": "An Giang",
            "tram_di": "Bến xe An Giang",
            "diem_den": "Bắc Ninh",
            "tram_den": "Bến xe Bắc Ninh",
            "ngay_di": "2025-10-20",
            "gio_di": "07:30:00",
            "gia_ve": 400000,
            "loai_xe": "Giường nằm",
            "con_trong": 10
        }
    ],
    "nearby_routes": []
}
```

### Frontend (futa-chat.js)

**Render nút cho mỗi chuyến:**

```javascript
addBookingButtons(routes, isNearby) {
    routes.forEach(route => {
        // Tạo nút với thông tin chuyến
        button.innerHTML = `
            ${route.diem_di} → ${route.diem_den}
            🕐 ${route.gio_di} | 💰 ${route.gia_ve}đ
            [Đặt vé →]
        `;

        // Click → chuyển đến trang booking
        button.onclick = () => goToBooking(route);
    });
}
```

**Chuyển hướng với query params:**

```javascript
goToBooking(route) {
    const params = new URLSearchParams({
        start: route.tram_di,    // "Bến xe An Giang"
        end: route.tram_den,      // "Bến xe Bắc Ninh"
        date: route.ngay_di,      // "2025-10-20"
        ticket: '1',
        trip: 'oneway'
    });

    window.location.href = `/datve?${params}`;
    // → /datve?start=Bến+xe+An+Giang&end=Bến+xe+Bắc+Ninh&date=2025-10-20&...
}
```

---

## 💻 Code Changes

### 1. ChatController.php

**Thêm dữ liệu routes vào response:**

```php
$responseData = [
    'success' => true,
    'content' => $aiResponse,
    'routes' => array_map(function($route) {
        return [
            'diem_di' => $route->diem_di,
            'tram_di' => $route->tram_di,
            'diem_den' => $route->diem_den,
            'tram_den' => $route->tram_den,
            'ngay_di' => $route->ngay_di,
            'gio_di' => $route->gio_di,
            'gia_ve' => $route->gia_ve,
            'loai_xe' => $route->loai_xe,
            'con_trong' => $route->con_trong
        ];
    }, $contextData['routes'])
];
```

### 2. futa-chat.js

**Thêm method mới:**

```javascript
// Thêm nút đặt vé
addBookingButtons(routes, isNearby) {
    // Tạo container cho các nút
    // Render từng nút với thông tin chuyến
    // Bind click event
}

// Chuyển đến trang đặt vé
goToBooking(route) {
    // Tạo URL params
    // Redirect đến /datve
}
```

**Thêm CSS cho nút:**

```css
.futa-booking-btn {
    background: white;
    border: 2px solid #667eea;
    border-radius: 12px;
    padding: 12px 15px;
    cursor: pointer;
    transition: all 0.3s;
}

.futa-booking-btn:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateX(5px);
}
```

---

## 📸 Giao diện

### Trước khi click:

```
┌──────────────────────────────────────────┐
│ 🤖 Bot:                                  │
│ Dạ có nhiều chuyến An Giang - Bắc Ninh:  │
│                                          │
│ ┌────────────────────────────────────┐  │
│ │ An Giang → Bắc Ninh                │  │
│ │ 🕐 07:30  💰 400,000đ  [Đặt vé →] │  │
│ └────────────────────────────────────┘  │
│                                          │
│ ┌────────────────────────────────────┐  │
│ │ An Giang → Bắc Ninh                │  │
│ │ 🕐 09:00  💰 200,000đ  [Đặt vé →] │  │
│ └────────────────────────────────────┘  │
└──────────────────────────────────────────┘
```

### Sau khi click:

```
→ Chuyển đến: /datve?start=Bến+xe+An+Giang&end=Bến+xe+Bắc+Ninh&date=2025-10-20&ticket=1&trip=oneway&sort=time

→ BookingController tự động redirect đến trang chi tiết chuyến xe

→ Người dùng chỉ cần chọn ghế và thanh toán! ✅
```

---

## 🚀 Cách test

### 1. Mở chatbot và hỏi:

```
"Tìm chuyến từ An Giang đến Bắc Ninh"
```

### 2. Chatbot sẽ trả lời kèm nút:

```
Dạ có nhiều chuyến An Giang - Bắc Ninh:

[An Giang → Bắc Ninh | 🕐 07:30 | 💰 400,000đ | Đặt vé →]
[An Giang → Bắc Ninh | 🕐 09:00 | 💰 200,000đ | Đặt vé →]
```

### 3. Click nút "Đặt vé"

- ✅ Chuyển đến trang `/datve`
- ✅ Form tìm kiếm đã điền sẵn
- ✅ Hiển thị chi tiết chuyến xe
- ✅ Sẵn sàng chọn ghế và thanh toán

---

## 🎁 Lợi ích

✅ **Tăng conversion rate**: Từ hỏi → đặt vé chỉ 1 click  
✅ **UX tốt hơn**: Không cần copy-paste thông tin  
✅ **Giảm friction**: Tự động điền form  
✅ **Mobile-friendly**: Nút lớn, dễ bấm  
✅ **Smart**: Hiển thị cả chuyến gần nếu không có chuyến trực tiếp

---

## 📌 Lưu ý

1. **Query params phải đúng format**:
    - `start`: Tên trạm đi (VD: "Bến xe An Giang")
    - `end`: Tên trạm đến
    - `date`: Y-m-d format (2025-10-20)

2. **BookingController sẽ**:
    - Tìm chuyến đầu tiên match với params
    - Redirect đến `/datve/{id}`
    - Hiển thị trang đặt vé chi tiết

3. **Fallback**:
    - Nếu không tìm thấy chuyến → hiển thị danh sách
    - Nếu trạm không match → dùng tên tỉnh

---

## 🔮 Tương lai

Có thể mở rộng:

- ✨ Thêm nút "So sánh giá"
- ✨ Lưu chuyến yêu thích
- ✨ Thông báo khi giá giảm
- ✨ Đặt vé luôn trong chatbox (không cần chuyển trang)

---

**Created:** 2025-10-19  
**Author:** AI Assistant  
**Version:** 1.0
