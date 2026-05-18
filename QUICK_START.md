# 🚀 QUICK START - HỆ THỐNG QUẢN LÝ SINH VIÊN

## 📌 Bắt Đầu Nhanh (2 phút)

### 1. **Cài Đặt**
```bash
cd d:\HTQLSV

# Chạy server
php artisan serve
```

### 2. **Truy Cập**
```
http://localhost:8000
```

### 3. **Sử Dụng**
- Sidebar bên trái: Navigate đến từng module
- Dashboard: Xem thống kê
- Thêm/Sửa/Xóa: Click nút tương ứng

---

## 🎯 Các Chức Năng Chính

| Chức Năng | URL | Mô Tả |
|----------|-----|-------|
| **Dashboard** | / | Thống kê tổng quát |
| **Sinh Viên** | /students | Quản lý CRUD |
| **Lớp Học** | /classrooms | Quản lý lớp |
| **Giáo Viên** | /teachers | Quản lý GV |
| **Môn Học** | /subjects | Quản lý môn |
| **Điểm** | /grades | Nhập/sửa điểm |
| **Đăng Ký** | /enrollments | Đăng ký môn học |

---

## ✨ Tính Năng Nổi Bật

✅ **Soft Delete** - Xóa mềm, có thể khôi phục  
✅ **Search & Filter** - Tìm kiếm nhanh  
✅ **Pagination** - Phân trang danh sách  
✅ **Validation** - Kiểm tra dữ liệu  
✅ **Many-to-Many** - Sinh viên <-> Môn học  
✅ **Dashboard** - Thống kê trực quan  
✅ **Eager Loading** - Tối ưu database  
✅ **Responsive UI** - Bootstrap 5  

---

## 📞 Hỗ Trợ

**Lỗi?** Kiểm tra: `storage/logs/laravel.log`

**Reset DB?** Chạy: `php artisan migrate:refresh`

**Cache?** Xóa: `php artisan cache:clear`

---

**Tài liệu chi tiết**: Xem `HUONG_DAN.md`  
**Danh sách đầy đủ**: Xem `TONG_HOP.md`
