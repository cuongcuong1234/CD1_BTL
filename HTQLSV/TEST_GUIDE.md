# 📋 Hướng Dẫn Kiểm Tra Nhanh - Test Guide

## ✅ Cách Kiểm Tra Hệ Thống Hoàn Thiện

### 1. Khởi Động Server
```bash
cd d:\HTQLSV
php artisan serve --host=127.0.0.1 --port=8000
```

### 2. Truy Cập URL
- **Dashboard:** http://localhost:8000
- **Sinh Viên:** http://localhost:8000/students
- **Lớp Học:** http://localhost:8000/classrooms
- **Giáo Viên:** http://localhost:8000/teachers
- **Môn Học:** http://localhost:8000/subjects
- **Điểm Số:** http://localhost:8000/grades
- **Đăng Ký:** http://localhost:8000/enrollments

---

## 🎯 Các Tính Năng Mới Để Kiểm Tra

### 1. Lớp Học - View Chi Tiết (MỚI)
```
URL: http://localhost:8000/classrooms/1
Khi click vào bất kỳ lớp nào → Xem:
  ✅ Thông tin lớp (Mã, Tên, Năm Học, Sức Chứa)
  ✅ Danh sách sinh viên trong lớp
  ✅ Button Sửa & Xóa
```

### 2. Lớp Học - Sửa (MỚI)
```
URL: http://localhost:8000/classrooms/1/edit
  ✅ Form sửa tên lớp, năm học
  ✅ Chọn giáo viên hướng dẫn
  ✅ Cập nhật sức chứa
```

### 3. Giáo Viên - View Chi Tiết (MỚI)
```
URL: http://localhost:8000/teachers/1
Khi click vào giáo viên → Xem:
  ✅ Thông tin giáo viên (Tên, Email, Chuyên Ngành)
  ✅ Danh sách lớp hướng dẫn
  ✅ Button Sửa & Xóa
```

### 4. Giáo Viên - Sửa (MỚI)
```
URL: http://localhost:8000/teachers/1/edit
  ✅ Form sửa thông tin giáo viên
  ✅ Cập nhật trạng thái
```

### 5. Môn Học - View Chi Tiết (MỚI)
```
URL: http://localhost:8000/subjects/1
Khi click vào môn học → Xem:
  ✅ Thông tin môn (Mã, Tên, Tín Chỉ)
  ✅ Danh sách sinh viên đăng ký
  ✅ Button Sửa & Xóa
```

### 6. Môn Học - Sửa (MỚI)
```
URL: http://localhost:8000/subjects/1/edit
  ✅ Form sửa môn học
  ✅ Cập nhật tín chỉ, mô tả
```

### 7. Điểm Số - View Chi Tiết (MỚI)
```
URL: http://localhost:8000/grades/1
  ✅ Xem điểm chi tiết
  ✅ Hiển thị sinh viên, môn học
  ✅ Xếp loại (A/B/C/D/F)
  ✅ Button Sửa & Xóa
```

### 8. Đăng Ký - View Chi Tiết (MỚI)
```
URL: http://localhost:8000/enrollments/1
  ✅ Xem thông tin đăng ký
  ✅ Hiển thị sinh viên, môn học
  ✅ Trạng thái (Enrolled/Completed/Dropped)
  ✅ Button Sửa & Xóa
```

---

## 📊 Dữ Liệu Mẫu Có Sẵn

### Sinh Viên (10 bản ghi)
```
SV001 - Phạm Thanh Tùng (10A1)
SV002 - Hoàng Minh Huy (10A1)
SV003 - Đỗ Thị Kim Liên (10A1)
SV004 - Vũ Quốc Hùng (10A2)
SV005 - Trần Bảo Nhi (10A2)
SV006 - Ngô Hữu Phú (10A2)
SV007 - Phan Đức Linh (10B1)
SV008 - Dương Thị Yến (10B1)
SV009 - Bùi Thành Đạt (10B1)
SV010 - Lương Văn Long (10A1)
```

### Giáo Viên (3 bản ghi)
```
GV001 - Nguyễn Văn A (Toán Học)
GV002 - Trần Thị B (Vật Lý)
GV003 - Lê Văn C (Tiếng Anh)
```

### Lớp Học (3 bản ghi)
```
LOP10A1 - Lớp 10A1 (GV001)
LOP10A2 - Lớp 10A2 (GV002)
LOP10B1 - Lớp 10B1 (GV003)
```

### Môn Học (5 bản ghi)
```
TOAN101 - Đại Số 1 (3 tín)
LY101 - Vật Lý 1 (4 tín)
ANH101 - Tiếng Anh 1 (3 tín)
HOA101 - Hóa 1 (3 tín)
SINH101 - Sinh 1 (2 tín)
```

---

## 🧪 Test Cases

### Test 1: Xem Sinh Viên Chi Tiết
1. Vào Students → Danh sách
2. Click "Xem" sinh viên SV001
3. ✅ Xem: Thông tin sinh viên, lớp học
4. ✅ Click "Sửa" → Form sửa
5. ✅ Click "Xóa" → Xác nhận xóa

### Test 2: Xem Lớp Chi Tiết (MỚI)
1. Vào Classrooms → Danh sách
2. Click tên lớp "10A1"
3. ✅ Xem: Thông tin lớp, danh sách học sinh
4. ✅ Click "Sửa" → Form sửa
5. ✅ Click "Xóa" → Xác nhận xóa

### Test 3: Xem Giáo Viên Chi Tiết (MỚI)
1. Vào Teachers → Danh sách
2. Click tên giáo viên "Nguyễn Văn A"
3. ✅ Xem: Thông tin, các lớp hướng dẫn
4. ✅ Click "Sửa" → Form sửa
5. ✅ Click "Xóa" → Xác nhận xóa

### Test 4: Xem Môn Học Chi Tiết (MỚI)
1. Vào Subjects → Danh sách
2. Click "Xem" môn "Đại Số 1"
3. ✅ Xem: Thông tin, danh sách sinh viên đăng ký
4. ✅ Click "Sửa" → Form sửa
5. ✅ Click "Xóa" → Xác nhận xóa

### Test 5: Xem Điểm Chi Tiết (MỚI)
1. Vào Grades → Danh sách
2. Click icon "Xem" trên bất kỳ hàng nào
3. ✅ Xem: Sinh viên, Môn học, Điểm số, Xếp loại
4. ✅ Click "Sửa" → Form sửa điểm
5. ✅ Click "Xóa" → Xác nhận xóa

### Test 6: Xem Đăng Ký Chi Tiết (MỚI)
1. Vào Enrollments → Danh sách
2. Click icon "Xem" trên bất kỳ hàng nào
3. ✅ Xem: Sinh viên, Môn học, Trạng thái
4. ✅ Click "Sửa" → Form sửa trạng thái
5. ✅ Click "Xóa" → Xác nhận xóa

### Test 7: Dashboard
1. Vào Dashboard
2. ✅ Xem: Thống kê tổng số, tháng này, lớp đông nhất
3. ✅ Xem: Danh sách sinh viên mới nhất
4. ✅ Xem: Top 5 sinh viên xuất sắc

---

## 🔍 Debug Tips

### Nếu có lỗi:
```bash
# Xem database
sqlite3 database/database.sqlite ".tables"

# Xem logs
tail -f storage/logs/laravel.log

# Xóa cache
php artisan cache:clear

# Rebuild
php artisan config:cache
php artisan view:clear
```

### Nếu cần reset database:
```bash
php artisan migrate:fresh --seed
```

---

## ✅ Checklist Hoàn Thiện

- [x] Dashboard hoạt động (thống kê)
- [x] Students CRUD + Soft Delete
- [x] Classrooms CRUD + Show + Edit (MỚI)
- [x] Teachers CRUD + Show + Edit (MỚI)
- [x] Subjects CRUD + Show + Edit (MỚI)
- [x] Grades CRUD + Show (MỚI)
- [x] Enrollments CRUD + Show (MỚI)
- [x] 10 dữ liệu mẫu
- [x] Search & Filter
- [x] Pagination
- [x] Responsive UI (Bootstrap 5)
- [x] Vietnamese localization
- [x] Alert messages

---

**Tất cả tính năng đã hoàn thiện! Hệ thống sẵn sàng sử dụng.**
