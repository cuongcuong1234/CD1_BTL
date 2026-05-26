# 📚 HỆ THỐNG QUẢN LÝ SINH VIÊN - HƯỚNG DẪN SỬ DỤNG
## 🎯 Tính Năng Chính
### 1. Dashboard 📊
Thống kê tổng quan về sinh viên, lớp, giáo viên, môn học
Hiển thị lớp có sinh viên nhiều nhất
Danh sách sinh viên mới nhất
Top 5 sinh viên xuất sắc
Điểm trung bình chung

**Truy cập**: http://localhost:8000/

### 2. Quản Lý Sinh Viên 👨‍🎓
#### Chức Năng
- ✅ Thêm Sinh Viên: Tạo sinh viên mới với đầy đủ thông tin
- ✅ Danh Sách: Xem tất cả sinh viên, tìm kiếm, lọc theo lớp/trạng thái
- ✅ Sửa: Cập nhật thông tin sinh viên
- ✅ Xem Chi Tiết: Xem lịch sử đăng ký, bảng điểm
- ✅ Xóa Mềm (Soft Delete): Xóa sinh viên, có thể khôi phục
- ✅ Khôi Phục: Phục hồi sinh viên đã xóa

#### Thông Tin Sinh Viên
- Mã sinh viên (duy nhất)
- Tên
- Email (duy nhất)
- Số điện thoại
- Địa chỉ
- Lớp học
- Trạng thái (Đang học / Bảo lưu)

### 3. Quản Lý Lớp Học 🏫
**Chức Năng**: CRUD đầy đủ, manage sinh viên theo lớp

**Thông Tin Lớp**

- Mã lớp (duy nhất)
- Tên lớp
- Năm học
- Giáo viên hướng dẫn
- Sức chứa

### 4. Quản Lý Giáo Viên 👨‍🏫
**Thông Tin Giáo Viên** 

- Mã giáo viên
- Tên
- Email
- Điện thoại
- Chuyên ngành
Trạng thái
### 5. Quản Lý Môn Học 📖
**Thông Tin Môn Học**

- Mã môn (duy nhất)
- Tên môn
- Mô tả
- Số tín chỉ
### 6. Quản Lý Điểm ⭐
**Chức Năng**

- Nhập/Cập nhật điểm cho sinh viên
- Tự động tính xếp loại (A, B, C, D, F)
- Lọc theo sinh viên hoặc môn học
- Xếp Loại Điểm

### 7. Quản Lý Đăng Ký Học 📝
**Chức Năng**

- Đăng ký sinh viên vào môn học
- Theo dõi trạng thái đăng ký (Đang học, Hoàn thành, Bỏ học)
- Ngăn chặn đăng ký trùng lặp
**💾 Quan Hệ Dữ Liệu
 ```
Sinh Viên (Student)
├── belongsTo Lớp Học (Classroom)
├── hasMany Điểm (Grade)
└── hasMany Đăng Ký (Enrollment)

Lớp Học (Classroom)
├── belongsTo Giáo Viên (Teacher)
└── hasMany Sinh Viên (Student)

Giáo Viên (Teacher)
└── hasMany Lớp Học (Classroom)

Môn Học (Subject)
├── hasMany Điểm (Grade)
└── hasMany Đăng Ký (Enrollment)

Điểm (Grade)
├── belongsTo Sinh Viên (Student)
└── belongsTo Môn Học (Subject)

Đăng Ký (Enrollment)
├── belongsTo Sinh Viên (Student)
└── belongsTo Môn Học (Subject)

Many-to-Many:
Sinh Viên ↔ Môn Học (qua bảng Enrollments)
```

## 🚀 Chạy Ứng Dụng
```bash
# 1. Vào thư mục project
cd d:\HTQLSV

# 2. Cài đặt dependencies (nếu chưa làm)
composer install

# 3. Chạy migrations (nếu chưa làm)
php artisan migrate

# 4. Chạy server
php artisan serve

# 5. Truy cập
# Browser: http://localhost:8000

