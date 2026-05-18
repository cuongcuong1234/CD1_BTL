# ⚙️ CONFIGURATION GUIDE - Hoàn Thiện Cấu Hình

## 📋 DANH SÁCH CÔNG VIỆC CẦN HOÀN THÀNH

### 1. ✅ AuthServiceProvider - Đăng ký Policies

**File:** `app/Providers/AuthServiceProvider.php`

```php
<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\Grade;
use App\Policies\StudentPolicy;
use App\Policies\GradePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Student::class => StudentPolicy::class,
        Grade::class => GradePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
```

---

### 2. ✅ AppServiceProvider - Binding Services

**File:** `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use App\Repositories\StudentRepository;
use App\Repositories\ClassroomRepository;
use App\Repositories\GradeRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\EnrollmentRepository;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Enrollment;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind Repositories
        $this->app->bind(StudentRepository::class, function ($app) {
            return new StudentRepository(new Student());
        });

        $this->app->bind(ClassroomRepository::class, function ($app) {
            return new ClassroomRepository(new Classroom());
        });

        $this->app->bind(GradeRepository::class, function ($app) {
            return new GradeRepository(new Grade());
        });

        $this->app->bind(TeacherRepository::class, function ($app) {
            return new TeacherRepository(new Teacher());
        });

        $this->app->bind(SubjectRepository::class, function ($app) {
            return new SubjectRepository(new Subject());
        });

        $this->app->bind(EnrollmentRepository::class, function ($app) {
            return new EnrollmentRepository(new Enrollment());
        });
    }

    public function boot(): void
    {
        // Register EventServiceProvider
    }
}
```

---

### 3. ✅ EventServiceProvider - Đã Tạo

**File:** `app/Providers/EventServiceProvider.php` (✅ đã tạo)

---

### 4. ⏳ Tạo Email View

**File:** `resources/views/emails/student-created.blade.php`

```blade
@component('mail::message')
# Chào mừng {{ $student->name }}

Bạn đã được đăng ký thành công vào hệ thống quản lý học tập.

**Thông tin của bạn:**
- Mã sinh viên: {{ $student->student_code }}
- Email: {{ $student->email }}
- Lớp: {{ $student->classroom->name ?? 'Chưa được phân lớp' }}

@component('mail::button', ['url' => url('/dashboard')])
Đăng nhập vào hệ thống
@endcomponent

Cảm ơn bạn,<br>
{{ config('app.name') }}
@endcomponent
```

---

### 5. ⏳ Cấu Hình Cache

**File:** `.env`

```env
CACHE_DRIVER=redis
# hoặc
CACHE_DRIVER=file
# hoặc  
CACHE_DRIVER=database
```

**Hoặc File:** `config/cache.php`

```php
'default' => env('CACHE_DRIVER', 'file'),

'stores' => [
    'file' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data'),
    ],
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
    ],
    'database' => [
        'driver' => 'database',
        'table' => 'cache',
    ],
],
```

---

### 6. ⏳ Cấu Hình Queue

**File:** `.env`

```env
QUEUE_CONNECTION=database
# hoặc
QUEUE_CONNECTION=redis
# hoặc
QUEUE_CONNECTION=sync
```

**Tạo jobs table:**
```bash
php artisan queue:table
php artisan migrate
```

**Hoặc redis:**
```bash
# Cài Redis: https://redis.io/docs/getting-started/
QUEUE_CONNECTION=redis
```

**Chạy Queue Worker:**
```bash
# Development
php artisan queue:work

# Production
php artisan queue:work --daemon

# Specific queue
php artisan queue:work --queue=emails,reports
```

---

### 7. ⏳ Cấu Hình Permission & Roles (Optional - nếu dùng Spatie)

**Cài đặt:**
```bash
composer require spatie/laravel-permission

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

php artisan migrate
```

**Sử dụng:**

```php
// Tạo roles
$admin = Role::create(['name' => 'admin']);
$teacher = Role::create(['name' => 'teacher']);
$student = Role::create(['name' => 'student']);

// Tạo permissions
Permission::create(['name' => 'view-student']);
Permission::create(['name' => 'create-student']);
Permission::create(['name' => 'update-student']);
Permission::create(['name' => 'delete-student']);

Permission::create(['name' => 'view-grade']);
Permission::create(['name' => 'create-grade']);
Permission::create(['name' => 'update-grade']);
Permission::create(['name' => 'delete-grade']);

// Gán permissions cho roles
$admin->syncPermissions([
    'view-student', 'create-student', 'update-student', 'delete-student',
    'view-grade', 'create-grade', 'update-grade', 'delete-grade'
]);

// Gán role cho user
$user->assignRole('admin');

// Kiểm tra
if ($user->hasPermissionTo('create-student')) {
    // ...
}
```

**Sử dụng trong Policy:**
```php
public function create(User $user): bool
{
    return $user->hasPermissionTo('create-student');
}
```

---

### 8. ⏳ Database Migration cho Cache/Queue

```bash
# Tạo cache table
php artisan cache:table
php artisan migrate

# Tạo jobs table
php artisan queue:table
php artisan migrate
```

---

### 9. ⏳ Testing API Endpoints

**Sử dụng Postman:**

1. **GET - Lấy danh sách sinh viên**
```
GET http://localhost:8000/api/v1/students
Query Params:
  - search=Nguyễn
  - status=active
  - classroom_id=1
```

2. **POST - Tạo sinh viên**
```
POST http://localhost:8000/api/v1/students
Body (JSON):
{
  "student_code": "SV001",
  "name": "Nguyễn Văn A",
  "email": "a@example.com",
  "phone": "0123456789",
  "address": "Hà Nội",
  "classroom_id": 1,
  "status": "active"
}
```

3. **PUT - Cập nhật sinh viên**
```
PUT http://localhost:8000/api/v1/students/1
Body (JSON):
{
  "name": "Nguyễn Văn B",
  "phone": "0987654321"
}
```

4. **DELETE - Xóa sinh viên**
```
DELETE http://localhost:8000/api/v1/students/1
```

---

### 10. ⏳ Logging & Debugging

**File:** `.env`

```env
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

**File:** `config/logging.php`

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'daily'],
    ],
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
    ],
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'days' => 14,
    ],
],
```

**Xem logs:**
```bash
# Linux/Mac
tail -f storage/logs/laravel.log

# Windows
Get-Content -Path storage/logs/laravel.log -Wait

# Hoặc sử dụng
php artisan logs
```

---

### 11. ⏳ Middleware cho API

**Tạo API Middleware:**
```bash
php artisan make:middleware EnsureTokenIsValid
```

**File:** `app/Http/Middleware/EnsureTokenIsValid.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra token
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}
```

**Đăng ký trong:** `app/Http/Kernel.php`

```php
protected $routeMiddleware = [
    // ...
    'api.check' => \App\Http\Middleware\EnsureTokenIsValid::class,
];
```

---

## 🧪 TESTING CHECKLIST

- [ ] Repository methods hoạt động
- [ ] Service layer caching
- [ ] Events trigger correctly
- [ ] API endpoints respond JSON
- [ ] Authorization policies work
- [ ] Queue jobs send emails
- [ ] Transactions rollback on error
- [ ] Error handling catches all exceptions
- [ ] Eager loading reduces queries
- [ ] Logging captures all actions

---

## 🔍 TROUBLESHOOTING

### Problem: "Class not found"
**Solution:** Run `composer dump-autoload`

### Problem: Events not firing
**Solution:** Check EventServiceProvider is registered in config/app.php

### Problem: Queue not working
**Solution:** 
1. Check QUEUE_CONNECTION in .env
2. Run `php artisan queue:work`
3. Check database table created

### Problem: Cache not working
**Solution:**
1. Check CACHE_DRIVER in .env
2. Run `php artisan cache:clear`

### Problem: API returns 404
**Solution:**
1. Check routes in routes/api.php
2. Run `php artisan route:list`
3. Check controller namespace

---

## 📚 HELPFUL COMMANDS

```bash
# Clear all caches
php artisan cache:clear

# Restart queue
php artisan queue:restart

# View all routes
php artisan route:list

# Run migrations
php artisan migrate

# Check logs
php artisan logs

# Testing
php artisan test

# Tinker shell
php artisan tinker
```

---

## 🎯 NEXT STEPS

1. [ ] Cấu hình AuthServiceProvider
2. [ ] Cấu hình AppServiceProvider  
3. [ ] Tạo email views
4. [ ] Cấu hình cache driver
5. [ ] Cấu hình queue
6. [ ] Tạo permission & roles
7. [ ] Test API endpoints
8. [ ] Run queue worker
9. [ ] Monitor logs
10. [ ] Deploy to production

---

Generated: May 18, 2026
