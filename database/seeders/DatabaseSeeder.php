<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Grade;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản người dùng
        $this->call(UserSeeder::class);

        // Tạo 3 Giáo Viên
        Teacher::create([
            'teacher_code' => 'GV001',
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@school.edu.vn',
            'phone' => '0901234567',
            'specialization' => 'Toán Học',
            'status' => 'active',
        ]);

        Teacher::create([
            'teacher_code' => 'GV002',
            'name' => 'Trần Thị B',
            'email' => 'tranthib@school.edu.vn',
            'phone' => '0912345678',
            'specialization' => 'Vật Lý',
            'status' => 'active',
        ]);

        Teacher::create([
            'teacher_code' => 'GV003',
            'name' => 'Lê Văn C',
            'email' => 'levanc@school.edu.vn',
            'phone' => '0923456789',
            'specialization' => 'Tiếng Anh',
            'status' => 'active',
        ]);

        // Tạo 3 Lớp Học
        $classroom1 = Classroom::create([
            'classroom_code' => 'LOP10A1',
            'name' => 'Lớp 10A1',
            'academic_year' => '2025-2026',
            'teacher_id' => 1,
            'capacity' => 45,
        ]);

        $classroom2 = Classroom::create([
            'classroom_code' => 'LOP10A2',
            'name' => 'Lớp 10A2',
            'academic_year' => '2025-2026',
            'teacher_id' => 2,
            'capacity' => 45,
        ]);

        $classroom3 = Classroom::create([
            'classroom_code' => 'LOP10B1',
            'name' => 'Lớp 10B1',
            'academic_year' => '2025-2026',
            'teacher_id' => 3,
            'capacity' => 45,
        ]);

        // Tạo 10 Sinh Viên
        $students = [
            [
                'student_code' => 'SV001',
                'name' => 'Phạm Thanh Tùng',
                'email' => 'ptung@student.edu.vn',
                'phone' => '0987654321',
                'address' => 'Hà Nội',
                'classroom_id' => 1,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV002',
                'name' => 'Hoàng Minh Huy',
                'email' => 'mhuy@student.edu.vn',
                'phone' => '0976543210',
                'address' => 'Hà Nội',
                'classroom_id' => 1,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV003',
                'name' => 'Đỗ Thị Kim Liên',
                'email' => 'klien@student.edu.vn',
                'phone' => '0965432109',
                'address' => 'Hải Phòng',
                'classroom_id' => 1,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV004',
                'name' => 'Vũ Quốc Hùng',
                'email' => 'qhung@student.edu.vn',
                'phone' => '0954321098',
                'address' => 'Hà Nội',
                'classroom_id' => 2,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV005',
                'name' => 'Trần Bảo Nhi',
                'email' => 'bnhi@student.edu.vn',
                'phone' => '0943210987',
                'address' => 'Hà Nội',
                'classroom_id' => 2,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV006',
                'name' => 'Ngô Hữu Phú',
                'email' => 'hphu@student.edu.vn',
                'phone' => '0932109876',
                'address' => 'TP. Hồ Chí Minh',
                'classroom_id' => 2,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV007',
                'name' => 'Phan Đức Linh',
                'email' => 'dlinh@student.edu.vn',
                'phone' => '0921098765',
                'address' => 'Hà Nội',
                'classroom_id' => 3,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV008',
                'name' => 'Dương Thị Yến',
                'email' => 'tyen@student.edu.vn',
                'phone' => '0910987654',
                'address' => 'Đà Nẵng',
                'classroom_id' => 3,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV009',
                'name' => 'Bùi Thành Đạt',
                'email' => 'tdat@student.edu.vn',
                'phone' => '0909876543',
                'address' => 'Hà Nội',
                'classroom_id' => 3,
                'status' => 'active',
            ],
            [
                'student_code' => 'SV010',
                'name' => 'Lương Văn Long',
                'email' => 'vlong@student.edu.vn',
                'phone' => '0898765432',
                'address' => 'Hà Nội',
                'classroom_id' => 1,
                'status' => 'active',
            ],
        ];

        $createdStudents = [];
        foreach ($students as $studentData) {
            $createdStudents[] = Student::create($studentData);
        }

        // Tạo 5 Môn Học
        $subjects = [
            [
                'subject_code' => 'TOAN101',
                'name' => 'Đại Số 1',
                'description' => 'Đại số cơ bản lớp 10',
                'credits' => 3,
            ],
            [
                'subject_code' => 'LY101',
                'name' => 'Vật Lý 1',
                'description' => 'Cơ học cổ điển',
                'credits' => 4,
            ],
            [
                'subject_code' => 'ANH101',
                'name' => 'Tiếng Anh 1',
                'description' => 'Tiếng Anh giao tiếp cơ bản',
                'credits' => 3,
            ],
            [
                'subject_code' => 'HOA101',
                'name' => 'Hóa 1',
                'description' => 'Hóa học đại cương',
                'credits' => 3,
            ],
            [
                'subject_code' => 'SINH101',
                'name' => 'Sinh 1',
                'description' => 'Sinh học tế bào',
                'credits' => 2,
            ],
        ];

        $createdSubjects = [];
        foreach ($subjects as $subjectData) {
            $createdSubjects[] = Subject::create($subjectData);
        }

        // Tạo Enrollment cho các sinh viên
        foreach ($createdStudents as $student) {
            // Mỗi sinh viên đăng ký 3-4 môn ngẫu nhiên
            $enrollCount = rand(3, 4);
            $subjectIds = array_rand($createdSubjects, $enrollCount);
            
            if (!is_array($subjectIds)) {
                $subjectIds = [$subjectIds];
            }

            foreach ($subjectIds as $subjectId) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'subject_id' => $createdSubjects[$subjectId]->id,
                    'enrollment_date' => now(),
                    'status' => 'enrolled',
                ]);

                // Tạo điểm cho mỗi đăng ký
                $score = rand(60, 100);
                Grade::create([
                    'student_id' => $student->id,
                    'subject_id' => $createdSubjects[$subjectId]->id,
                    'score' => $score,
                    'grade_letter' => $this->getLetterGrade($score),
                ]);
            }
        }

        $this->command->info('✅ Database seeder completed!');
        $this->command->info('📊 Created: 3 Teachers, 3 Classrooms, 10 Students, 5 Subjects');
        $this->command->info('📝 Created: ~34 Enrollments and Grades');
    }

    /**
     * Convert score to letter grade
     */
    private function getLetterGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }
}
