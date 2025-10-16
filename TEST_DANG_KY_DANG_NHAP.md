# HƯỚNG DẪN TEST ĐĂNG KÝ & ĐĂNG NHẬP

## ✅ CÁC CẢI TIẾN ĐÃ THỰC HIỆN

### 1. **Validation Backend (Server-side)**

- Username: chỉ chứa chữ cái, số và dấu gạch dưới
- Fullname: ít nhất 3 ký tự
- Email: đúng định dạng email, unique
- Phone: 10-11 chữ số, unique
- Password: tối thiểu 6 ký tự, phải khớp với xác nhận

### 2. **Validation Frontend (Client-side)**

- Kiểm tra format trước khi submit
- Hiển thị alert nếu có lỗi
- Giúp giảm tải server và cải thiện UX

### 3. **Hiển thị Lỗi Chi Tiết**

- Thông báo lỗi từng trường riêng biệt
- Thông báo tổng hợp tất cả lỗi validation
- Session messages (success/error)
- Icon trực quan cho mỗi loại thông báo

### 4. **Logging Đầy Đủ**

- Log mỗi lần thử đăng ký
- Log thành công/thất bại
- Log đăng nhập với user ID và role
- Giúp debug và tracking

---

## 🧪 TEST CASE 1: ĐĂNG KÝ THẤT BẠI (Validation Errors)

### Test 1.1: Username không hợp lệ

```
URL: http://127.0.0.1:8000/register

Thông tin:
- Username: test@123  ← có ký tự đặc biệt
- Fullname: Test User
- Email: test123@example.com
- Phone: 0909123456
- Password: password123
- Confirm: password123

Kết quả mong đợi:
❌ "Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới"
```

### Test 1.2: Họ tên quá ngắn

```
Thông tin:
- Username: testuser
- Fullname: AB  ← chỉ 2 ký tự
- Email: test123@example.com
- Phone: 0909123456
- Password: password123
- Confirm: password123

Kết quả mong đợi:
❌ "Họ và tên phải có ít nhất 3 ký tự"
```

### Test 1.3: Số điện thoại không đúng format

```
Thông tin:
- Username: testuser
- Fullname: Test User
- Email: test123@example.com
- Phone: 090912  ← chỉ 6 số
- Password: password123
- Confirm: password123

Kết quả mong đợi:
❌ "Số điện thoại phải là 10-11 chữ số"
```

### Test 1.4: Email đã tồn tại

```
Thông tin:
- Username: newuser123
- Fullname: Test User
- Email: [email đã có trong DB]
- Phone: 0909111222
- Password: password123
- Confirm: password123

Kết quả mong đợi:
❌ "Email đã được sử dụng, vui lòng dùng email khác"
```

### Test 1.5: Số điện thoại đã tồn tại

```
Thông tin:
- Username: newuser456
- Fullname: Test User
- Email: newemail@example.com
- Phone: 0966421557  ← số đã tồn tại (user thanhloine)
- Password: password123
- Confirm: password123

Kết quả mong đợi:
❌ "Số điện thoại đã được đăng ký, vui lòng dùng số khác"
```

### Test 1.6: Mật khẩu quá ngắn

```
Thông tin:
- Username: testuser789
- Fullname: Test User
- Email: test789@example.com
- Phone: 0909333444
- Password: 12345  ← chỉ 5 ký tự
- Confirm: 12345

Kết quả mong đợi:
❌ "Mật khẩu phải có ít nhất 6 ký tự"
```

### Test 1.7: Mật khẩu xác nhận không khớp

```
Thông tin:
- Username: testuser999
- Fullname: Test User
- Email: test999@example.com
- Phone: 0909555666
- Password: password123
- Confirm: password456  ← khác password

Kết quả mong đợi:
❌ "Mật khẩu xác nhận không khớp"
```

---

## ✅ TEST CASE 2: ĐĂNG KÝ THÀNH CÔNG

```
URL: http://127.0.0.1:8000/register

Thông tin:
- Username: testuser20251016
- Fullname: Nguyễn Test User
- Email: testuser20251016@example.com
- Phone: 0909888999
- Password: password123
- Confirm: password123

Kết quả mong đợi:
✅ Chuyển về trang home
✅ Thông báo: "Đăng ký thành công! Chào mừng bạn đến với hệ thống."
✅ Tự động đăng nhập
✅ Tìm thấy user trong database (bảng users)
```

### Kiểm tra trong database:

```sql
SELECT * FROM users WHERE username = 'testuser20251016';
```

---

## 🔐 TEST CASE 3: ĐĂNG NHẬP

### Test 3.1: Đăng nhập bằng username

```
URL: http://127.0.0.1:8000/login

Thông tin:
- Identifier: testuser20251016
- Password: password123

Kết quả mong đợi:
✅ Đăng nhập thành công
✅ Chuyển về trang home (role user)
✅ Thông báo: "Đăng nhập thành công! Chào [Tên người dùng]"
```

### Test 3.2: Đăng nhập bằng email

```
Thông tin:
- Identifier: testuser20251016@example.com
- Password: password123

Kết quả mong đợi:
✅ Đăng nhập thành công
```

### Test 3.3: Đăng nhập bằng số điện thoại

```
Thông tin:
- Identifier: 0909888999
- Password: password123

Kết quả mong đợi:
✅ Đăng nhập thành công
```

### Test 3.4: Tài khoản không tồn tại

```
Thông tin:
- Identifier: userkhongtontai
- Password: password123

Kết quả mong đợi:
❌ "Tài khoản không tồn tại trong hệ thống!"
```

### Test 3.5: Sai mật khẩu

```
Thông tin:
- Identifier: testuser20251016
- Password: wrongpassword

Kết quả mong đợi:
❌ "Mật khẩu không chính xác!"
```

---

## 📋 CHECKLIST KIỂM TRA

### Frontend

- [ ] Form hiển thị đúng layout
- [ ] Placeholder text rõ ràng
- [ ] Icon hiển thị đúng
- [ ] Validation client-side hoạt động (alert trước khi submit)
- [ ] Lỗi hiển thị với style đỏ
- [ ] Success message hiển thị với style xanh
- [ ] Auto-hide message sau 5 giây
- [ ] Input fields highlight khi có lỗi

### Backend

- [ ] Validation rules đầy đủ
- [ ] Custom error messages tiếng Việt
- [ ] Check unique cho username, email, phone
- [ ] Password được hash trước khi lưu
- [ ] Tự động đăng nhập sau register
- [ ] Redirect đúng theo role
- [ ] Log ghi đầy đủ

### Database

- [ ] User được tạo với đầy đủ fields
- [ ] Password đã được hash
- [ ] Role mặc định là 'user'
- [ ] Không có duplicate username/email/phone

---

## 🔍 DEBUG TOOLS

### 1. Xem log Laravel:

```bash
Get-Content storage\logs\laravel.log -Tail 50
```

### 2. Kiểm tra user trong DB:

```bash
php artisan tinker
>>> App\Models\User::where('email', 'test@example.com')->first();
```

### 3. Test script có sẵn:

```bash
php check_duplicate_users.php
php test_new_registration.php
```

### 4. Browser DevTools:

- F12 → Console: xem JavaScript errors
- F12 → Network: xem HTTP requests/responses
- F12 → Application → Cookies: xem session

---

## 📝 VALIDATION RULES TÓM TẮT

| Field    | Rules                                        | Error Message                               |
| -------- | -------------------------------------------- | ------------------------------------------- |
| username | required, max:50, unique, regex:[a-zA-Z0-9_] | Tên đăng nhập đã tồn tại / chỉ chữ số và \_ |
| fullname | required, max:100, min:3                     | Họ tên ít nhất 3 ký tự                      |
| email    | required, email, max:100, unique             | Email không đúng / đã tồn tại               |
| phone    | required, max:15, unique, regex:[0-9]{10,11} | Phone 10-11 số / đã tồn tại                 |
| password | required, min:6, confirmed                   | Mật khẩu ít nhất 6 ký tự / không khớp       |

---

## 🎯 ƯU TIÊN TEST

1. **Ưu tiên cao**: Test 1.5 (phone trùng) và Test 2 (đăng ký thành công)
2. **Ưu tiên trung bình**: Các test validation khác
3. **Ưu tiên thấp**: Test đăng nhập (đã ổn định)

---

## 🚀 LƯU Ý QUAN TRỌNG

1. **Trước khi test**: Xóa cache browser (Ctrl+Shift+Delete)
2. **Sau mỗi test thất bại**: Kiểm tra log để debug
3. **Test với dữ liệu mới**: Đừng dùng email/phone đã tồn tại
4. **F5 trang register**: Sau mỗi lần sửa code

---

**Ngày cập nhật**: 16/10/2025
**Version**: 2.0 - Full Validation & Error Display
