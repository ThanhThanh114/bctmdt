# SƠ ĐỒ HỆ THỐNG BÌNH LUẬN

## 📊 Sơ đồ Luồng Dữ Liệu

```
┌─────────────────────────────────────────────────────────────────┐
│                     HỆ THỐNG BÌNH LUẬN                          │
└─────────────────────────────────────────────────────────────────┘

┌─────────────┐         ┌──────────────┐         ┌──────────────┐
│   KHÁCH     │────────>│   CHUYẾN XE  │<────────│   NHÀ XE     │
│   HÀNG      │         │   (Trip)     │         │  (Staff)     │
│   (User)    │         └──────────────┘         │   (Admin)    │
└─────────────┘                │                 └──────────────┘
      │                        │                        │
      │ 1. Mua vé              │                        │
      │ ────────────>          │                        │
      │                        │                        │
      │ 2. Viết bình luận      │                        │
      │ ──────────────────────>│                        │
      │    + Chọn sao (1-5)    │                        │
      │    + Viết nội dung     │                        │
      │                        │                        │
      │                        │ 3. Kiểm tra & Lưu      │
      │                        │ ────────────────────>  │
      │                        │   - Lọc từ ngữ        │
      │                        │   - Auto duyệt (≥3⭐)  │
      │                        │   - Chờ duyệt (≤2⭐)   │
      │                        │                        │
      │                        │ 4. Xem & Trả lời       │
      │                        │ <────────────────────  │
      │                        │                        │
      │ 5. Xem phản hồi        │                        │
      │ <──────────────────────│                        │
      │                        │                        │
```

## 🗂️ Cấu Trúc Database

```
┌─────────────────────────────────────────────────────────────┐
│                     TABLE: binh_luan                        │
├─────────────────────────────────────────────────────────────┤
│ ma_bl (PK)           │ ID bình luận                         │
│ parent_id (FK)       │ ID bình luận cha (NULL = gốc)       │
│ user_id (FK)         │ ID người bình luận                   │
│ chuyen_xe_id (FK)    │ ID chuyến xe                         │
│ noi_dung             │ Nội dung bình luận                   │
│ so_sao               │ Số sao (1-5, NULL cho reply)        │
│ trang_thai           │ cho_duyet | da_duyet | tu_choi      │
│ ngay_bl              │ Ngày bình luận                       │
│ ngay_duyet           │ Ngày duyệt                           │
│ ly_do_tu_choi        │ Lý do từ chối                        │
│ nv_id                │ ID nhân viên xử lý                   │
└─────────────────────────────────────────────────────────────┘

        │
        │ Relationships
        ├──────────> users (user_id)
        ├──────────> chuyen_xe (chuyen_xe_id)
        ├──────────> binh_luan (parent_id) - Self reference
        └──────────> nhan_vien (nv_id)
```

## 🔄 Quy Trình Tự Động Duyệt

```
┌──────────────────────┐
│ User viết bình luận  │
└──────────┬───────────┘
           │
           ▼
    ┌──────────────┐
    │ Kiểm tra sao │
    └──────┬───────┘
           │
     ┌─────┴─────┐
     │           │
     ▼           ▼
┌─────────┐  ┌─────────┐
│ ≤2 sao  │  │ ≥3 sao  │
└────┬────┘  └────┬────┘
     │            │
     ▼            ▼
┌──────────┐  ┌──────────┐
│CHỜ DUYỆT │  │ TỰ ĐỘNG  │
│ 🔴       │  │  DUYỆT ✅ │
└────┬─────┘  └────┬─────┘
     │            │
     ▼            │
┌──────────────┐  │
│Staff xét duyệt│  │
└────┬──────────┘ │
     │            │
┌────┴────────────┴───┐
│  HIỂN THỊ CÔNG KHAI │
└─────────────────────┘
```

## 👥 Phân Quyền Chi Tiết

```
┌────────────────────────────────────────────────────────────┐
│                        USER (Khách hàng)                    │
├────────────────────────────────────────────────────────────┤
│ ✅ Xem bình luận đã duyệt của tất cả khách hàng           │
│ ✅ Viết bình luận mới (nếu đã mua vé)                     │
│ ✅ Xóa bình luận của mình (nếu chưa có phản hồi)          │
│ ✅ Xem phản hồi từ nhà xe                                 │
│ ❌ KHÔNG xem bình luận chưa duyệt                         │
│ ❌ KHÔNG chỉnh sửa bình luận có phản hồi                  │
└────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────┐
│                      STAFF (Nhân viên)                      │
├────────────────────────────────────────────────────────────┤
│ ✅ Xem TẤT CẢ bình luận của nhà xe mình                   │
│ ✅ Trả lời bình luận                                       │
│ ✅ Duyệt bình luận chờ duyệt                              │
│ ✅ Từ chối bình luận (với lý do)                          │
│ ✅ Xóa bình luận                                           │
│ ✅ Lọc, tìm kiếm bình luận                                │
│ ❌ KHÔNG xem bình luận nhà xe khác                        │
└────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────┐
│                      ADMIN (Quản trị)                       │
├────────────────────────────────────────────────────────────┤
│ ✅ TOÀN QUYỀN với TẤT CẢ bình luận                        │
│ ✅ Xem bình luận của tất cả nhà xe                        │
│ ✅ Xem thống kê tổng thể                                  │
│ ✅ Quản lý bình luận không phù hợp                        │
│ ✅ Tạo bình luận thay mặt người dùng (nếu cần)            │
└────────────────────────────────────────────────────────────┘
```

## 🎯 Use Cases

### Use Case 1: Khách hàng viết đánh giá tích cực
```
1. User đăng nhập
2. Vào "Vé của tôi"
3. Click "Đánh giá" cho vé đã mua
4. Chọn 5 sao ⭐⭐⭐⭐⭐
5. Viết: "Chuyến đi rất tuyệt vời, tài xế lịch sự!"
6. Click "Gửi đánh giá"
7. ✅ Bình luận TỰ ĐỘNG được duyệt và hiển thị ngay
```

### Use Case 2: Khách hàng viết đánh giá tiêu cực
```
1. User đăng nhập
2. Vào "Vé của tôi"
3. Click "Đánh giá" cho vé đã mua
4. Chọn 1 sao ⭐
5. Viết: "Xe đến muộn 30 phút!"
6. Click "Gửi đánh giá"
7. 🔴 Bình luận vào trạng thái "Chờ duyệt"
8. Staff xem xét và:
   - Duyệt + Trả lời: "Xin lỗi, chúng tôi sẽ cải thiện"
   - Hoặc từ chối nếu không đúng sự thật
```

### Use Case 3: Staff trả lời bình luận
```
1. Staff đăng nhập
2. Vào "Quản lý bình luận"
3. Thấy thông báo có 5 bình luận chờ duyệt
4. Click vào bình luận
5. Đọc nội dung
6. Viết phản hồi chuyên nghiệp
7. Click "Gửi trả lời"
8. ✅ Phản hồi hiển thị với badge "NHÀ XE"
```

## 🔐 Security Features

```
┌────────────────────────────────────────────┐
│         BẢO MẬT & KIỂM SOÁT                │
├────────────────────────────────────────────┤
│ 🔒 Lọc từ ngữ tự động (ProfanityFilter)   │
│ 🔒 Kiểm tra quyền sở hữu vé               │
│ 🔒 Giới hạn 1 bình luận/chuyến xe         │
│ 🔒 Không xóa được bình luận có phản hồi   │
│ 🔒 Middleware phân quyền role-based       │
│ 🔒 CSRF protection                        │
│ 🔒 XSS protection (Blade escaping)        │
└────────────────────────────────────────────┘
```

## 📱 Responsive Design

```
┌─────────────┬─────────────┬─────────────┐
│   Mobile    │   Tablet    │   Desktop   │
│   📱        │   📱        │   💻        │
├─────────────┼─────────────┼─────────────┤
│ Stack       │ 2 columns   │ 3 columns   │
│ Full width  │ Grid layout │ Grid layout │
│ Cards       │ Cards       │ Cards       │
│ Touch-      │ Touch-      │ Mouse-      │
│ friendly    │ friendly    │ friendly    │
└─────────────┴─────────────┴─────────────┘
```

## 🎨 Color Scheme

```
Primary Colors:
├─ Blue (#007bff)     - Bình luận user
├─ Green (#28a745)    - Phản hồi nhà xe
├─ Warning (#ffc107)  - Sao đánh giá
├─ Purple (#667eea)   - Avatar gradient
└─ Pink (#f093fb)     - Badge nhà xe

Status Colors:
├─ Success (#28a745)  - Đã duyệt
├─ Warning (#ffc107)  - Chờ duyệt
└─ Danger (#dc3545)   - Từ chối
```

---

**Ngày tạo:** {{ date('d/m/Y') }}  
**Version:** 1.0
