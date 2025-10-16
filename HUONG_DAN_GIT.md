# 📘 HƯỚNG DẪN SỬ DỤNG GIT VÀ GITHUB

## 📋 Mục Lục
1. [Cài Đặt Ban Đầu](#cài-đặt-ban-đầu)
2. [Push Code Lên GitHub](#push-code-lên-github)
3. [Pull Code Về Từ GitHub](#pull-code-về-từ-github)
4. [Các Lệnh Git Thường Dùng](#các-lệnh-git-thường-dùng)
5. [Xử Lý Conflict](#xử-lý-conflict)
6. [Tips & Tricks](#tips--tricks)

---

## 🚀 Cài Đặt Ban Đầu

### 1. Cài đặt Git
- Tải Git tại: https://git-scm.com/download/win
- Cài đặt và restart máy

### 2. Cấu hình Git lần đầu
```bash
# Cấu hình tên và email
git config --global user.name "Tên Của Bạn"
git config --global user.email "email@example.com"

# Kiểm tra cấu hình
git config --list
```

### 3. Tạo Repository mới trên GitHub
1. Truy cập https://github.com
2. Đăng nhập tài khoản
3. Click nút "New" để tạo repository mới
4. Đặt tên repository và click "Create repository"

---

## ⬆️ Push Code Lên GitHub

### Phương pháp 1: Khởi tạo repository mới

```bash
# Bước 1: Di chuyển vào thư mục project
cd d:\github\bctmdt

# Bước 2: Khởi tạo git (nếu chưa có)
git init

# Bước 3: Thêm remote repository
git remote add origin https://github.com/ThanhThanh114/bctmdt.git

# Bước 4: Thêm tất cả file vào staging area
git add .

# Bước 5: Tạo commit với message
git commit -m "Initial commit"

# Bước 6: Đổi tên branch thành main (nếu cần)
git branch -M main

# Bước 7: Push code lên GitHub
git push -u origin main
```

### Phương pháp 2: Push code thường ngày (đã có repository)

```bash
# Bước 1: Kiểm tra trạng thái
git status

# Bước 2: Thêm file đã thay đổi
git add .
# Hoặc thêm file cụ thể:
# git add index.php
# git add style.css

# Bước 3: Commit với message mô tả
git commit -m "Mô tả những thay đổi của bạn"

# Bước 4: Push lên GitHub
git push origin main
```

### 📝 Ví dụ commit messages tốt:
```bash
git commit -m "Add login feature"
git commit -m "Fix bug in user authentication"
git commit -m "Update homepage design"
git commit -m "Remove unused files"
git commit -m "Refactor database connection"
```

---

## ⬇️ Pull Code Về Từ GitHub

### Phương pháp 1: Clone repository lần đầu

```bash
# Clone toàn bộ repository về máy
git clone https://github.com/ThanhThanh114/bctmdt.git

# Di chuyển vào thư mục vừa clone
cd bctmdt
```

### Phương pháp 2: Pull code mới nhất (đã có repository)

```bash
# Bước 1: Di chuyển vào thư mục project
cd d:\github\bctmdt

# Bước 2: Pull code mới nhất từ GitHub
git pull origin main
```

### Phương pháp 3: Fetch và Merge

```bash
# Fetch: Lấy thông tin mới nhất từ remote (không merge)
git fetch origin

# Xem sự khác biệt
git diff main origin/main

# Merge: Gộp code từ remote vào local
git merge origin/main
```

---

## 🛠️ Các Lệnh Git Thường Dùng

### Kiểm tra trạng thái
```bash
# Xem trạng thái hiện tại
git status

# Xem lịch sử commit
git log
git log --oneline
git log --graph --oneline --all

# Xem thay đổi trong file
git diff
```

### Quản lý branches
```bash
# Xem danh sách branch
git branch

# Tạo branch mới
git branch ten-branch-moi

# Chuyển sang branch khác
git checkout ten-branch

# Tạo và chuyển sang branch mới
git checkout -b ten-branch-moi

# Xóa branch
git branch -d ten-branch

# Push branch lên GitHub
git push origin ten-branch
```

### Làm việc với remote
```bash
# Xem danh sách remote
git remote -v

# Thêm remote mới
git remote add origin https://github.com/username/repo.git

# Thay đổi URL remote
git remote set-url origin https://github.com/username/repo.git

# Xóa remote
git remote remove origin
```

### Hoàn tác thay đổi
```bash
# Hoàn tác thay đổi trong file (chưa add)
git checkout -- ten-file.php

# Bỏ file ra khỏi staging area
git reset HEAD ten-file.php

# Hoàn tác commit gần nhất (giữ thay đổi)
git reset --soft HEAD~1

# Hoàn tác commit gần nhất (xóa thay đổi)
git reset --hard HEAD~1
```

---

## ⚠️ Xử Lý Conflict

### Khi có conflict khi pull/merge:

```bash
# 1. Pull code và gặp conflict
git pull origin main

# 2. Git sẽ báo file bị conflict
# Mở file đó và tìm dòng:
# <<<<<<< HEAD
# Code của bạn
# =======
# Code từ GitHub
# >>>>>>> origin/main

# 3. Sửa conflict: Chọn code nào giữ lại

# 4. Sau khi sửa xong:
git add .
git commit -m "Resolve merge conflict"
git push origin main
```

### Tránh conflict:
```bash
# Luôn pull code trước khi làm việc
git pull origin main

# Commit và push thường xuyên
git add .
git commit -m "Update feature"
git push origin main
```

---

## 💡 Tips & Tricks

### 1. .gitignore - Bỏ qua file không cần push
Tạo file `.gitignore` trong thư mục gốc:
```
# Bỏ qua file config
config.php
.env

# Bỏ qua thư mục
node_modules/
vendor/
uploads/

# Bỏ qua file log
*.log
*.tmp

# Bỏ qua file hệ thống
.DS_Store
Thumbs.db
```

### 2. Alias - Tạo lệnh tắt
```bash
git config --global alias.st status
git config --global alias.co checkout
git config --global alias.br branch
git config --global alias.ci commit
git config --global alias.unstage 'reset HEAD --'

# Sử dụng:
git st      # thay vì git status
git co main # thay vì git checkout main
```

### 3. Stash - Lưu tạm thay đổi
```bash
# Lưu thay đổi hiện tại
git stash

# Xem danh sách stash
git stash list

# Lấy lại thay đổi
git stash pop

# Xóa stash
git stash drop
```

### 4. Xem ai sửa dòng code
```bash
git blame ten-file.php
```

### 5. Tìm kiếm trong code
```bash
# Tìm kiếm trong tất cả file
git grep "function login"

# Tìm trong lịch sử commit
git log -S "function login"
```

---

## 🔄 Quy Trình Làm Việc Hàng Ngày

```bash
# SÁNG: Pull code mới nhất
cd d:\github\bctmdt
git pull origin main

# TRONG NGÀY: Làm việc và commit thường xuyên
git add .
git commit -m "Add new feature"

# CUỐI NGÀY: Push code lên
git push origin main
```

---

## 📞 Liên Hệ & Hỗ Trợ

- **GitHub Repository**: https://github.com/ThanhThanh114/bctmdt
- **GitHub Docs**: https://docs.github.com
- **Git Documentation**: https://git-scm.com/doc

---

## ⚡ Quick Reference (Tham Khảo Nhanh)

| Lệnh | Mô tả |
|------|-------|
| `git init` | Khởi tạo repository |
| `git clone <url>` | Clone repository về máy |
| `git status` | Xem trạng thái |
| `git add .` | Thêm tất cả file |
| `git commit -m "message"` | Tạo commit |
| `git push origin main` | Push lên GitHub |
| `git pull origin main` | Pull code về |
| `git branch` | Xem danh sách branch |
| `git checkout <branch>` | Chuyển branch |
| `git merge <branch>` | Merge branch |
| `git log` | Xem lịch sử |
| `git diff` | Xem thay đổi |

---

**📌 Lưu ý:** Luôn pull code trước khi làm việc và push code sau khi hoàn thành để tránh conflict!

**✅ Version:** 1.0 - Updated: October 16, 2025
