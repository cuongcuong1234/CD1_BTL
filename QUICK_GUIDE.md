# 🚀 QUICK START GUIDE - Sử dụng Services & Repositories

## 📁 Cấu Trúc Thư Mục

```
app/
├── Repositories/        ← Tương tác với Database
│   ├── BaseRepository.php
│   ├── StudentRepository.php
│   ├── GradeRepository.php
│   └── ...
├── Services/            ← Business Logic
│   ├── StudentService.php
│   ├── GradeService.php
│   └── ...
├── Http/
│   ├── Controllers/     ← Gọi Services
│   │   ├── StudentController.php
│   │   └── ...
│   └── Controllers/Api/ ← API Controllers
│       ├── StudentApiController.php
│       └── GradeApiController.php
├── Events/              ← Sự kiện
│   ├── StudentCreated.php
│   ├── GradeCreated.php
│   └── ...
├── Listeners/           ← Xử lý sự kiện
│   ├── LogStudentCreation.php
│   └── ...
├── Policies/            ← RBAC Authorization
│   ├── StudentPolicy.php
│   └── GradePolicy.php
└── Jobs/                ← Queue Tasks
    ├── SendStudentCreatedEmail.php
    └── GenerateGradeReport.php
```

---

## 🔄 WORKFLOW

### 1️⃣ Controller nhận Request
```
Request → Controller
```

### 2️⃣ Controller gọi Service
```
Controller → Service (Dependency Injection)
```

### 3️⃣ Service gọi Repository
```
Service → Repository
```

### 4️⃣ Repository truy vấn Database
```
Repository → Database
```

### 5️⃣ Trả kết quả ngược lại
```
Database → Repository → Service → Controller → Response
```

---

## 💻 VÍ DỤ THỰC TẾ

### Ví dụ 1: Lấy danh sách sinh viên

**Controller:**
```php
class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $students = $this->studentService->getStudents(
            search: $request->search,
            status: $request->status,
            classroomId: $request->classroom_id
        );

        return view('students.index', compact('students'));
    }
}
```

**Service (StudentService.php):**
```php
class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getStudents($search = null, $status = null, $classroomId = null)
    {
        // Cache data 1 hour
        return Cache::remember('students_list', 3600, function () use ($search, $status, $classroomId) {
            return $this->studentRepository->getStudentsList(
                $search, $status, $classroomId
            );
        });
    }
}
```

**Repository (StudentRepository.php):**
```php
class StudentRepository extends BaseRepository
{
    public function getStudentsList($search = null, $status = null, $classroomId = null)
    {
        $query = $this->model->newQuery();

        // Eager load relationships
        $query->with(['classroom', 'grades.subject']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($classroomId) {
            $query->where('classroom_id', $classroomId);
        }

        return $query->paginate(15);
    }
}
```

---

### Ví dụ 2: Tạo sinh viên với Event & Transaction

**Controller:**
```php
public function store(StoreStudentRequest $request)
{
    $student = $this->studentService->createStudent($request->validated());
    
    return redirect()->route('students.show', $student)
                    ->with('success', 'Tạo sinh viên thành công');
}
```

**Service:**
```php
public function createStudent(array $data)
{
    try {
        DB::beginTransaction();

        // Tạo sinh viên
        $student = $this->studentRepository->create($data);

        // Trigger Event
        event(new StudentCreated($student));

        // Dispatch Job
        SendStudentCreatedEmail::dispatch($student);

        // Clear cache
        Cache::forget('students_list');

        DB::commit();

        return $student;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

**Event Listener (LogStudentCreation.php):**
```php
class LogStudentCreation
{
    public function handle(StudentCreated $event): void
    {
        Log::info('Sinh viên mới được tạo: ' . $event->student->name, [
            'student_id' => $event->student->id,
            'student_code' => $event->student->student_code,
        ]);
    }
}
```

---

### Ví dụ 3: API Endpoint

**API Controller (StudentApiController.php):**
```php
class StudentApiController
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $students = $this->studentService->getStudents(
                $request->search,
                $request->status,
                $request->classroom_id
            );

            return response()->json([
                'success' => true,
                'data' => $students,
                'message' => 'Danh sách sinh viên'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'student_code' => 'required|unique:students',
                'name' => 'required|string',
                'email' => 'required|email|unique:students',
                'phone' => 'required|string',
                'address' => 'required|string',
                'classroom_id' => 'required|exists:classrooms,id',
                'status' => 'required|in:active,inactive',
            ]);

            $student = $this->studentService->createStudent($validated);

            return response()->json([
                'success' => true,
                'data' => $student,
                'message' => 'Tạo sinh viên thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
```

---

### Ví dụ 4: RBAC (Authorization)

**Model - Student.php:**
```php
class Student extends Model
{
    // ...
}
```

**Policy - StudentPolicy.php:**
```php
class StudentPolicy
{
    public function view(User $user, Student $student): bool
    {
        return $user->hasPermissionTo('view-student');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-student');
    }

    public function update(User $user, Student $student): bool
    {
        return $user->hasPermissionTo('update-student');
    }
}
```

**Controller - Sử dụng Authorization:**
```php
public function edit(Student $student)
{
    // Kiểm tra quyền
    $this->authorize('update', $student);

    return view('students.edit', compact('student'));
}
```

---

### Ví dụ 5: Queue Job

**Trong Service - khi tạo sinh viên:**
```php
public function createStudent(array $data)
{
    // ...
    $student = $this->studentRepository->create($data);

    // Dispatch email job
    SendStudentCreatedEmail::dispatch($student);

    // ...
}
```

**Job - SendStudentCreatedEmail.php:**
```php
class SendStudentCreatedEmail implements ShouldQueue
{
    protected $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function handle(): void
    {
        Mail::to($this->student->email)
            ->queue(new StudentCreatedMail($this->student));
    }
}
```

**Chạy Queue Worker:**
```bash
php artisan queue:work
```

---

## 📝 CHEAT SHEET

### Repository Methods
```php
// Lấy tất cả
$students = $studentRepository->all();

// Phân trang
$students = $studentRepository->paginate(15);

// Lấy theo ID
$student = $studentRepository->find($id);

// Lấy hoặc thất bại
$student = $studentRepository->findOrFail($id);

// Lấy theo điều kiện
$student = $studentRepository->findBy('email', 'test@example.com');

// Tạo mới
$student = $studentRepository->create($data);

// Cập nhật
$student = $studentRepository->update($id, $data);

// Xóa
$studentRepository->delete($id);

// Kiểm tra tồn tại
$exists = $studentRepository->exists('email', 'test@example.com');
```

### Service Methods
```php
// Lấy danh sách
$students = $studentService->getStudents($search, $status, $classroomId);

// Lấy chi tiết (có cache)
$student = $studentService->getStudentDetail($id);

// Tạo (có event, transaction)
$student = $studentService->createStudent($data);

// Cập nhật (có event, transaction)
$student = $studentService->updateStudent($id, $data);

// Xóa (có event, transaction)
$studentService->deleteStudent($id);
```

---

## 🎓 BEST PRACTICES

### ✅ DO's
```php
// ✅ Sử dụng Service cho business logic
$student = $studentService->createStudent($data);

// ✅ Sử dụng Repository cho database queries
$students = $studentRepository->getStudentsList();

// ✅ Cache kết quả expensive queries
Cache::remember('key', 3600, fn() => $query);

// ✅ Sử dụng Transactions cho operations quan trọng
DB::beginTransaction();
// ... operations
DB::commit();

// ✅ Dispatch events
event(new StudentCreated($student));

// ✅ Use Eager Loading
$students = $query->with(['classroom', 'grades']);
```

### ❌ DON'Ts
```php
// ❌ Không trực tiếp query trong Controller
$students = Student::all();

// ❌ Không có error handling
$student = Student::create($data);

// ❌ Không eager load
foreach ($students as $student) {
    echo $student->classroom->name; // N+1 query!
}

// ❌ Không cache
$expensive_data = ExpensiveQuery::get();

// ❌ Không use transactions
Model::create($data);
Model::update($data);
```

---

## 📞 NEED HELP?

Tham khảo file: `IMPROVEMENT_GUIDE.md`

Generated: May 18, 2026
