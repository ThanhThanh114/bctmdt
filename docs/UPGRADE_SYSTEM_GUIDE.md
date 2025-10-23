# HƯỚNG DẪN HỆ THỐNG NÂNG CẤP USER LÊN NHÀ XE

## Tổng quan
Hệ thống cho phép người dùng (User) nâng cấp tài khoản lên Nhà xe (Bus_owner) thông qua việc:
1. Gửi yêu cầu nâng cấp kèm thông tin doanh nghiệp
2. Thanh toán phí nâng cấp 500,000đ qua QR Code hoặc chuyển khoản
3. Admin xem xét và phê duyệt
4. Tài khoản được nâng cấp và có thể quản lý nhà xe

## Các tính năng đã thực hiện

### 1. Database & Models
- **Bảng `upgrade_requests`**: Lưu thông tin yêu cầu nâng cấp
  - user_id: ID người dùng
  - request_type: Loại nâng cấp (Bus_owner)
  - amount: Số tiền (500,000đ)
  - status: Trạng thái (pending, payment_pending, paid, approved, rejected, cancelled)
  - reason: Lý do nâng cấp
  - business_info: Thông tin doanh nghiệp (JSON)
  - admin_note: Ghi chú của admin
  - approved_by: ID admin phê duyệt
  - Timestamps

- **Bảng `payments`**: Lưu thông tin thanh toán
  - upgrade_request_id: ID yêu cầu nâng cấp
  - transaction_id: Mã giao dịch
  - amount: Số tiền
  - payment_method: Phương thức (qr_code, bank_transfer, cash)
  - status: Trạng thái (pending, completed, failed, refunded)
  - bank_name, account_number, account_name: Thông tin ngân hàng
  - qr_code_url: URL QR code
  - payment_proof: Ảnh chứng từ thanh toán
  - paid_at: Thời gian thanh toán

- **Models**:
  - `UpgradeRequest`: Quản lý yêu cầu nâng cấp
  - `Payment`: Quản lý thanh toán
  - Cập nhật `User` model với relationships và helper methods

### 2. Controllers

#### User\UpgradeController
- `index()`: Hiển thị trang nâng cấp và lịch sử yêu cầu
- `store()`: Tạo yêu cầu nâng cấp mới và thông tin thanh toán
- `payment()`: Hiển thị trang thanh toán với QR code
- `uploadProof()`: Upload chứng từ thanh toán
- `confirmPayment()`: Xác nhận đã thanh toán
- `cancel()`: Hủy yêu cầu nâng cấp
- `show()`: Xem chi tiết yêu cầu

#### Admin\UsersController (Updated)
- `upgradeRequests()`: Danh sách yêu cầu nâng cấp
- `showUpgradeRequest()`: Chi tiết yêu cầu nâng cấp
- `approveUpgrade()`: Phê duyệt yêu cầu (nâng role lên Bus_owner)
- `rejectUpgrade()`: Từ chối yêu cầu
- `assignBusCompany()`: Gán nhà xe cho Bus_owner
- Cập nhật `update()` để hỗ trợ cập nhật ma_nha_xe

### 3. Views

#### User Views (`resources/views/AdminLTE/user/upgrade/`)
- **index.blade.php**: 
  - Form đăng ký nâng cấp với thông tin doanh nghiệp
  - Hiển thị yêu cầu đang xử lý
  - Lịch sử yêu cầu nâng cấp
  - Hướng dẫn và quyền lợi

- **payment.blade.php**:
  - Hiển thị QR code thanh toán (VietQR API)
  - Thông tin chuyển khoản ngân hàng
  - Form upload chứng từ thanh toán
  - Nút xác nhận đã thanh toán
  - Hướng dẫn thanh toán chi tiết

- **show.blade.php**:
  - Chi tiết yêu cầu nâng cấp
  - Thông tin thanh toán
  - Timeline tiến trình
  - Chứng từ đã upload

#### Admin Views (`resources/views/AdminLTE/admin/users/`)
- **upgrade_requests.blade.php**:
  - Danh sách yêu cầu nâng cấp
  - Lọc theo trạng thái
  - Tìm kiếm theo thông tin user
  - Thống kê tổng quan

- **upgrade_request_detail.blade.php**:
  - Chi tiết đầy đủ yêu cầu
  - Thông tin user và doanh nghiệp
  - Thông tin thanh toán và chứng từ
  - Form phê duyệt (gán nhà xe, ghi chú)
  - Form từ chối với lý do
  - Timeline xử lý
  - Thống kê user

- **index.blade.php** (Updated):
  - Thêm button "Yêu cầu nâng cấp" với badge số lượng pending
  - Thêm cột "Nhà xe" hiển thị nhà xe được gán

- **edit.blade.php** (Updated):
  - Thêm dropdown chọn nhà xe khi role là Bus_owner

#### Dashboard Update
- **user/dashboard.blade.php**: 
  - Thêm card "Nâng cấp tài khoản" cho User
  - Hiển thị trạng thái yêu cầu đang xử lý (nếu có)
  - Button "Nâng cấp ngay" với thông tin phí và quyền lợi

### 4. Routes

#### User Routes (`/user/*`)
```php
GET  /user/upgrade                          - Trang nâng cấp
POST /user/upgrade                          - Tạo yêu cầu mới
GET  /user/upgrade/{id}                     - Chi tiết yêu cầu
GET  /user/upgrade/{id}/payment             - Trang thanh toán
POST /user/upgrade/{id}/upload-proof        - Upload chứng từ
POST /user/upgrade/{id}/confirm-payment     - Xác nhận thanh toán
DELETE /user/upgrade/{id}/cancel            - Hủy yêu cầu
```

#### Admin Routes (`/admin/*`)
```php
GET    /admin/upgrade-requests                      - Danh sách yêu cầu
GET    /admin/upgrade-requests/{id}                 - Chi tiết yêu cầu
PATCH  /admin/upgrade-requests/{id}/approve         - Phê duyệt
PATCH  /admin/upgrade-requests/{id}/reject          - Từ chối
POST   /admin/users/{user}/assign-bus-company       - Gán nhà xe
```

### 5. Tính năng QR Code
- Sử dụng VietQR API để tạo QR code tự động
- QR code chứa:
  - Số tiền: 500,000đ
  - Nội dung: UPG {transaction_id}
  - Thông tin tài khoản ngân hàng
- URL: `https://img.vietqr.io/image/{BANK_BIN}-{ACCOUNT_NO}-{TEMPLATE}.png`

### 6. Workflow

#### Quy trình User:
1. User đăng nhập và vào Dashboard
2. Click "Nâng cấp ngay" trong card "Nâng cấp tài khoản"
3. Điền form với thông tin:
   - Tên công ty/Nhà xe
   - Mã số thuế (optional)
   - Địa chỉ kinh doanh
   - Số điện thoại, Email liên hệ
   - Lý do nâng cấp (min 20 ký tự)
4. Gửi yêu cầu → Chuyển sang trang thanh toán
5. Quét QR code hoặc chuyển khoản thủ công
6. Upload chứng từ (optional) hoặc click "Tôi đã thanh toán"
7. Chờ admin xác nhận (1-2 ngày)
8. Nhận thông báo kết quả

#### Quy trình Admin:
1. Vào "Yêu cầu nâng cấp" từ trang Users
2. Xem danh sách yêu cầu, filter theo trạng thái
3. Click "Xem" để xem chi tiết
4. Kiểm tra:
   - Thông tin user
   - Thông tin doanh nghiệp
   - Chứng từ thanh toán
5. Phê duyệt:
   - Chọn nhà xe để gán (optional)
   - Thêm ghi chú (optional)
   - Click "Phê duyệt"
6. Hoặc từ chối:
   - Nhập lý do từ chối (required)
   - Click "Từ chối"
7. User tự động được nâng role lên Bus_owner

### 7. Trạng thái Yêu cầu
- **pending**: Chờ xử lý ban đầu
- **payment_pending**: Chờ thanh toán
- **paid**: Đã thanh toán, chờ admin duyệt
- **approved**: Đã phê duyệt, user được nâng cấp
- **rejected**: Bị từ chối
- **cancelled**: User tự hủy

### 8. Trạng thái Thanh toán
- **pending**: Chờ thanh toán
- **completed**: Đã thanh toán
- **failed**: Thất bại
- **refunded**: Đã hoàn tiền (khi reject)

## Cài đặt

### 1. Chạy Migration
```bash
php artisan migrate
```

### 2. Storage Setup (cho upload ảnh)
```bash
php artisan storage:link
```

### 3. Permissions
Đảm bảo folder `storage/app/public/payment_proofs` có quyền write

## Test

### Test User Flow:
1. Đăng nhập với tài khoản User
2. Truy cập: `http://127.0.0.1:8000/user/dashboard`
3. Click "Nâng cấp ngay"
4. Điền form và gửi yêu cầu
5. Thanh toán và upload chứng từ

### Test Admin Flow:
1. Đăng nhập với tài khoản Admin
2. Truy cập: `http://127.0.0.1:8000/admin/users`
3. Click "Yêu cầu nâng cấp"
4. Xem và phê duyệt yêu cầu

## API Endpoints

### VietQR API
```
GET https://img.vietqr.io/image/{BANK_BIN}-{ACCOUNT_NO}-{TEMPLATE}.png
Parameters:
  - amount: Số tiền
  - addInfo: Nội dung chuyển khoản
  - accountName: Tên tài khoản
```

## Notes

1. **Phí nâng cấp**: Mặc định 500,000đ, có thể thay đổi trong migration
2. **VietQR**: Sử dụng API miễn phí, không cần đăng ký
3. **Upload ảnh**: Max 5MB, chỉ chấp nhận JPG, PNG
4. **Security**: 
   - Kiểm tra ownership trước khi cho phép hành động
   - Validate input đầy đủ
   - Foreign key constraints để bảo toàn dữ liệu

## Customization

### Thay đổi phí nâng cấp:
File: `app/Http/Controllers/User/UpgradeController.php`
```php
'amount' => 500000, // Thay đổi số tiền ở đây
```

### Thay đổi thông tin ngân hàng:
File: `app/Http/Controllers/User/UpgradeController.php`
```php
Payment::create([
    'bank_name' => 'Vietcombank', // Tên ngân hàng
    'account_number' => '1234567890', // Số tài khoản
    'account_name' => 'CONG TY TMDT BUS CITY', // Tên chủ TK
]);
```

### Thay đổi VietQR template:
File: `app/Http/Controllers/User/UpgradeController.php` - method `generateQRContent()`
```php
$template = 'compact2'; // Options: 'compact', 'compact2', 'qr_only', 'print'
```

## Troubleshooting

### Lỗi Foreign Key khi migrate:
- Kiểm tra bảng `users` có cột `id` đúng kiểu dữ liệu
- Đảm bảo migration sử dụng đúng kiểu `integer()` hoặc `unsignedInteger()`

### QR Code không hiển thị:
- Kiểm tra URL VietQR API
- Kiểm tra kết nối internet
- Thử thay đổi template khác

### Upload ảnh lỗi:
- Chạy `php artisan storage:link`
- Kiểm tra quyền folder `storage/app/public`
- Kiểm tra size ảnh < 5MB

## Tác giả
Được tạo bởi GitHub Copilot cho dự án Bus City Management
Ngày: 23/10/2025
