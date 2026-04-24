# 📚 HỆ THỐNG QUẢN LÝ SINH VIÊN - HƯỚNG DẪN SỬ DỤNG

## 🎯 Tính Năng Chính

### 1. Dashboard 📊
- Thống kê tổng quan về sinh viên, lớp, giáo viên, môn học
- Hiển thị lớp có sinh viên nhiều nhất
- Danh sách sinh viên mới nhất
- Top 5 sinh viên xuất sắc
- Điểm trung bình chung

**Truy cập**: `http://localhost:8000/`

### 2. Quản Lý Sinh Viên 👨‍🎓

#### Chức Năng
- ✅ **Thêm Sinh Viên**: Tạo sinh viên mới với đầy đủ thông tin
- ✅ **Danh Sách**: Xem tất cả sinh viên, tìm kiếm, lọc theo lớp/trạng thái
- ✅ **Sửa**: Cập nhật thông tin sinh viên
- ✅ **Xem Chi Tiết**: Xem lịch sử đăng ký, bảng điểm
- ✅ **Xóa Mềm (Soft Delete)**: Xóa sinh viên, có thể khôi phục
- ✅ **Khôi Phục**: Phục hồi sinh viên đã xóa

#### Thông Tin Sinh Viên
- Mã sinh viên (duy nhất)
- Tên
- Email (duy nhất)
- Số điện thoại
- Địa chỉ
- Lớp học
- Trạng thái (Đang học / Bảo lưu)

**Routes**:
```
GET    /students                 → Danh sách
POST   /students                 → Thêm
GET    /students/create         → Form thêm
GET    /students/{id}           → Chi tiết
GET    /students/{id}/edit      → Form sửa
PUT    /students/{id}           → Lưu sửa
DELETE /students/{id}           → Xóa
GET    /students/restore/{id}   → Khôi phục
GET    /students-trashed        → Danh sách đã xóa
```

### 3. Quản Lý Lớp Học 🏫

**Chức Năng**: CRUD đầy đủ, manage sinh viên theo lớp

**Thông Tin Lớp**
- Mã lớp (duy nhất)
- Tên lớp
- Năm học
- Giáo viên hướng dẫn
- Sức chứa

**Routes**:
```
GET    /classrooms            → Danh sách
POST   /classrooms            → Thêm
GET    /classrooms/create     → Form thêm
GET    /classrooms/{id}       → Chi tiết
GET    /classrooms/{id}/edit  → Form sửa
PUT    /classrooms/{id}       → Lưu sửa
DELETE /classrooms/{id}       → Xóa
```

### 4. Quản Lý Giáo Viên 👨‍🏫

**Thông Tin Giáo Viên**
- Mã giáo viên
- Tên
- Email
- Điện thoại
- Chuyên ngành
- Trạng thái

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

**Xếp Loại Điểm**
| Điểm | Xếp Loại |
|------|---------|
| 8.5 - 10.0 | A |
| 7.0 - 8.4 | B |
| 5.5 - 6.9 | C |
| 4.0 - 5.4 | D |
| 0 - 3.9 | F |

### 7. Quản Lý Đăng Ký Học 📝

**Chức Năng**
- Đăng ký sinh viên vào môn học
- Theo dõi trạng thái đăng ký (Đang học, Hoàn thành, Bỏ học)
- Ngăn chặn đăng ký trùng lặp

## 💾 Quan Hệ Dữ Liệu

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

## 🎨 Giao Diện

### Layout Chính
- **Sidebar Navigation**: Menu chính ở bên trái
- **Responsive Design**: Tối ưu hóa cho desktop và mobile
- **Bootstrap 5**: Giao diện hiện đại

### Thành Phần
- **Alert**: Thông báo lỗi/thành công (tự động ẩn sau 5 giây)
- **Badges**: Trạng thái hiển thị (active, inactive, etc.)
- **Tables**: Bảng danh sách với pagination
- **Forms**: Form nhập dữ liệu với validation

## 🔒 Validation Rules

### Sinh Viên
```
student_code   → required, unique
name           → required, max:255
email          → required, email, unique
phone          → nullable, max:20
address        → nullable
classroom_id   → nullable, exists:classrooms
status         → required, in:active,inactive
```

### Lớp Học
```
classroom_code → required, unique
name           → required, max:255
academic_year  → required
teacher_id     → nullable, exists:teachers
capacity       → required, integer, min:1, max:100
```

### Giáo Viên
```
teacher_code   → required, unique
name           → required, max:255
email          → required, email, unique
phone          → nullable, max:20
specialization → nullable, max:255
status         → required, in:active,inactive
```

### Môn Học
```
subject_code   → required, unique
name           → required, max:255
description    → nullable
credits        → required, integer, min:1, max:10
```

## 🔄 Soft Delete (Xóa Mềm)

Tất cả models đều hỗ trợ xóa mềm:
- Dữ liệu không bị xóa hẳn khỏi database
- Có bảng "Đã Xóa" để xem các bản ghi đã xóa
- Có nút "Khôi Phục" để lấy lại dữ liệu

**Ví dụ**:
```
Sinh Viên Đã Xóa → /students-trashed → Nhấn "Khôi Phục"
```

## 🔍 Tìm Kiếm & Lọc

### Danh Sách Sinh Viên
- Tìm kiếm theo: Tên, Email, Mã SV
- Lọc theo: Trạng thái, Lớp học
- Sắp xếp theo: Ngày tạo, Tên

### Danh Sách Lớp Học
- Tìm kiếm theo: Tên, Mã lớp
- Lọc theo: Năm học

### Danh Sách Điểm
- Lọc theo: Sinh viên, Môn học

### Danh Sách Đăng Ký
- Lọc theo: Sinh viên, Môn học, Trạng thái

## 📱 Phân Trang

- Danh sách Sinh viên: 10 bản ghi/trang
- Danh sách Lớp: 10 bản ghi/trang
- Danh sách Giáo viên: 10 bản ghi/trang
- Danh sách Môn học: 10 bản ghi/trang
- Danh sách Điểm: 20 bản ghi/trang
- Danh sách Đăng ký: 15 bản ghi/trang

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
```

## 📊 Khác Biệt Quan Trọng

### Eager Loading (Tối ưu Queries)
Controllers sử dụng `with()` để tải relationships:
```php
Student::with('classroom', 'grades', 'enrollments')
```

### Mass Assignment Protection
Models sử dụng `$fillable`:
```php
protected $fillable = ['student_code', 'name', 'email', ...];
```

### Attribute Casting
Models cast các attributes:
```php
protected $casts = [
    'status' => 'string',
    'enrollment_date' => 'date',
];
```

## 📝 Các Tệp Quan Trọng

### Models (`app/Models/`)
- `Student.php` - Sinh viên
- `Classroom.php` - Lớp học
- `Teacher.php` - Giáo viên
- `Subject.php` - Môn học
- `Grade.php` - Điểm
- `Enrollment.php` - Đăng ký

### Controllers (`app/Http/Controllers/`)
- `StudentController.php`
- `ClassroomController.php`
- `TeacherController.php`
- `SubjectController.php`
- `GradeController.php`
- `EnrollmentController.php`
- `DashboardController.php`

### Form Requests (`app/Http/Requests/`)
- `StoreStudentRequest.php`
- `StoreClassroomRequest.php`
- `StoreTeacherRequest.php`
- `StoreSubjectRequest.php`

### Views (`resources/views/`)
- `layouts/master.blade.php` - Layout chính
- `dashboard.blade.php` - Dashboard
- `students/` - Views cho sinh viên
- `classrooms/` - Views cho lớp học
- `teachers/` - Views cho giáo viên
- `subjects/` - Views cho môn học
- `grades/` - Views cho điểm
- `enrollments/` - Views cho đăng ký

## 🐛 Troubleshooting

### Lỗi "File not found"
```bash
php artisan cache:clear
php artisan view:clear
```

### Reset Database
```bash
php artisan migrate:refresh
```

### Kiểm Tra Logs
```bash
# Log file
storage/logs/laravel.log
```
