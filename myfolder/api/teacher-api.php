<?php
require_once '../config.php';
header('Content-Type: application/json');

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get database connection
try {
    $pdo = getDBConnection();
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$teacherId = $_SESSION['teacher_id'];

switch($action) {
    // ========================================
    // GET ASSIGNED STUDENTS (NEW - FROM ADMIN ASSIGNMENT)
    // ========================================
    case 'getAssignedStudents':
        try {
            // Get students assigned by admin through teacher_students table
            $stmt = $pdo->prepare("
                SELECT 
                    s.student_id,
                    s.student_name,
                    s.grade_level,
                    sec.section_id,
                    sec.section_name,
                    ts.assigned_date
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                LEFT JOIN enrollment e ON s.student_id = e.student_id AND e.enrollment_status = 'enrolled'
                LEFT JOIN section sec ON e.section_id = sec.section_id
                WHERE ts.teacher_id = ?
                ORDER BY sec.section_name, s.student_name
            ");
            $stmt->execute([$teacherId]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Group students by section
            $groupedStudents = [];
            foreach ($students as $student) {
                $sectionName = $student['section_name'] ?? 'Unassigned Section';
                if (!isset($groupedStudents[$sectionName])) {
                    $groupedStudents[$sectionName] = [
                        'section_id' => $student['section_id'],
                        'section_name' => $sectionName,
                        'grade_level' => $student['grade_level'],
                        'students' => []
                    ];
                }
                $groupedStudents[$sectionName]['students'][] = [
                    'student_id' => $student['student_id'],
                    'student_name' => $student['student_name'],
                    'assigned_date' => $student['assigned_date']
                ];
            }
            
            echo json_encode([
                'success' => true, 
                'students' => $students,
                'grouped' => array_values($groupedStudents),
                'total' => count($students)
            ]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET ASSIGNED SECTIONS SUMMARY
    // ========================================
    case 'getAssignedSections':
        try {
            // Get unique sections from assigned students
            $stmt = $pdo->prepare("
                SELECT DISTINCT 
                    sec.section_id,
                    sec.section_name,
                    sec.grade_level,
                    COUNT(DISTINCT ts.student_id) as student_count
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                INNER JOIN enrollment e ON s.student_id = e.student_id AND e.enrollment_status = 'enrolled'
                INNER JOIN section sec ON e.section_id = sec.section_id
                WHERE ts.teacher_id = ?
                GROUP BY sec.section_id, sec.section_name, sec.grade_level
                ORDER BY sec.grade_level, sec.section_name
            ");
            $stmt->execute([$teacherId]);
            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'sections' => $sections]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET TEACHER SECTIONS (UPDATED - Uses assigned students)
    // ========================================
    case 'getTeacherSections':
        try {
            // Get sections from assigned students (not from schedule)
            $stmt = $pdo->prepare("
                SELECT DISTINCT sec.section_id, sec.section_name, sec.grade_level
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                INNER JOIN enrollment e ON s.student_id = e.student_id AND e.enrollment_status = 'enrolled'
                INNER JOIN section sec ON e.section_id = sec.section_id
                WHERE ts.teacher_id = ?
                ORDER BY sec.grade_level, sec.section_name
            ");
            $stmt->execute([$teacherId]);
            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'sections' => $sections]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET TEACHER SUBJECTS
    // ========================================
    case 'getTeacherSubjects':
        try {
            $stmt = $pdo->prepare("
                SELECT DISTINCT sub.subject_code, sub.subject_name
                FROM schedule sch
                INNER JOIN subject sub ON sch.subject_code = sub.subject_code
                WHERE sch.teacher_id = ?
                ORDER BY sub.subject_name
            ");
            $stmt->execute([$teacherId]);
            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'subjects' => $subjects]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET STUDENTS FOR GRADING (UPDATED - Uses assigned students)
    // ========================================
    case 'getStudentsForGrading':
        try {
            $sectionId = $_POST['section_id'] ?? '';
            $subjectCode = $_POST['subject_code'] ?? '';
            $gradingPeriod = $_POST['grading_period'] ?? '1st';
            
            if (empty($sectionId) || empty($subjectCode)) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                break;
            }
            
            // Get students assigned to this teacher in the specified section
            $stmt = $pdo->prepare("
                SELECT DISTINCT s.student_id, s.student_name
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                INNER JOIN enrollment e ON s.student_id = e.student_id
                WHERE ts.teacher_id = ? 
                AND e.section_id = ? 
                AND e.enrollment_status = 'enrolled'
                ORDER BY s.student_name
            ");
            $stmt->execute([$teacherId, $sectionId]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get current grades for each student
            foreach ($students as &$student) {
                $stmt = $pdo->prepare("
                    SELECT grade_score 
                    FROM grade 
                    WHERE student_id = ? 
                    AND subject_code = ? 
                    AND grading_period = ?
                ");
                $stmt->execute([$student['student_id'], $subjectCode, $gradingPeriod]);
                $grade = $stmt->fetch(PDO::FETCH_ASSOC);
                $student['current_grade'] = $grade ? $grade['grade_score'] : null;
            }
            
            echo json_encode(['success' => true, 'students' => $students]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // SAVE GRADE
    // ========================================
    case 'saveGrade':
        try {
            $studentId = $_POST['student_id'] ?? '';
            $subjectCode = $_POST['subject_code'] ?? '';
            $gradingPeriod = $_POST['grading_period'] ?? '';
            $grade = $_POST['grade'] ?? '';
            
            if (empty($studentId) || empty($subjectCode) || empty($gradingPeriod) || $grade === '') {
                echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
                break;
            }
            
            // Verify teacher has access to this student
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM teacher_students 
                WHERE teacher_id = ? AND student_id = ?
            ");
            $stmt->execute([$teacherId, $studentId]);
            $access = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($access['count'] == 0) {
                echo json_encode(['success' => false, 'message' => 'You do not have access to grade this student']);
                break;
            }
            
            // Validate grade range
            if ($grade < 0 || $grade > 100) {
                echo json_encode(['success' => false, 'message' => 'Grade must be between 0 and 100']);
                break;
            }
            
            // Check if grade already exists
            $stmt = $pdo->prepare("
                SELECT grade_id 
                FROM grade 
                WHERE student_id = ? 
                AND subject_code = ? 
                AND grading_period = ?
            ");
            $stmt->execute([$studentId, $subjectCode, $gradingPeriod]);
            $existingGrade = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existingGrade) {
                // Update existing grade
                $stmt = $pdo->prepare("
                    UPDATE grade 
                    SET grade_score = ? 
                    WHERE grade_id = ?
                ");
                $stmt->execute([$grade, $existingGrade['grade_id']]);
            } else {
                // Insert new grade
                $stmt = $pdo->prepare("
                    INSERT INTO grade (student_id, subject_code, grading_period, grade_score)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$studentId, $subjectCode, $gradingPeriod, $grade]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Grade saved successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        break;

    // ========================================
    // GET GRADES REPORT (UPDATED - Uses assigned students)
    // ========================================
    case 'getGradesReport':
        try {
            $sectionId = $_GET['section_id'] ?? '';
            $subjectCode = $_GET['subject_code'] ?? '';
            
            if (empty($sectionId) || empty($subjectCode)) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                break;
            }
            
            // Get assigned students in section
            $stmt = $pdo->prepare("
                SELECT DISTINCT s.student_id, s.student_name
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                INNER JOIN enrollment e ON s.student_id = e.student_id
                WHERE ts.teacher_id = ?
                AND e.section_id = ? 
                AND e.enrollment_status = 'enrolled'
                ORDER BY s.student_name
            ");
            $stmt->execute([$teacherId, $sectionId]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get grades for each student
            foreach ($students as &$student) {
                $stmt = $pdo->prepare("
                    SELECT grading_period, grade_score
                    FROM grade
                    WHERE student_id = ? AND subject_code = ?
                ");
                $stmt->execute([$student['student_id'], $subjectCode]);
                $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Format grades by period
                $student['grades'] = [];
                $total = 0;
                $count = 0;
                
                foreach ($grades as $g) {
                    $student['grades'][$g['grading_period']] = number_format($g['grade_score'], 2);
                    $total += $g['grade_score'];
                    $count++;
                }
                
                $student['average'] = $count > 0 ? number_format($total / $count, 2) : null;
            }
            
            echo json_encode(['success' => true, 'grades' => $students]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET MY SCHEDULE
    // ========================================
    case 'getMySchedule':
        try {
            $stmt = $pdo->prepare("
                SELECT 
                    sch.day_time,
                    sch.room_number,
                    sub.subject_name,
                    sec.section_name
                FROM schedule sch
                INNER JOIN subject sub ON sch.subject_code = sub.subject_code
                INNER JOIN section sec ON sch.section_id = sec.section_id
                WHERE sch.teacher_id = ?
                ORDER BY sch.day_time
            ");
            $stmt->execute([$teacherId]);
            $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'schedules' => $schedules]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET STUDENTS BY SECTION (FOR MODAL DISPLAY)
    // ========================================
    case 'getStudentsBySection':
        try {
            $sectionId = $_POST['section_id'] ?? '';
            
            if (empty($sectionId)) {
                echo json_encode(['success' => false, 'message' => 'Section ID is required']);
                break;
            }
            
            // Get students assigned to this teacher in the specified section
            $stmt = $pdo->prepare("
                SELECT DISTINCT s.student_id, s.student_name
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                INNER JOIN enrollment e ON s.student_id = e.student_id
                WHERE ts.teacher_id = ? 
                AND e.section_id = ? 
                AND e.enrollment_status = 'enrolled'
                ORDER BY s.student_name
            ");
            $stmt->execute([$teacherId, $sectionId]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true, 
                'students' => $students,
                'count' => count($students)
            ]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET DASHBOARD STATS (NEW)
    // ========================================
    case 'getDashboardStats':
        try {
            // Count assigned students
            $stmt = $pdo->prepare("
                SELECT COUNT(DISTINCT student_id) as count 
                FROM teacher_students 
                WHERE teacher_id = ?
            ");
            $stmt->execute([$teacherId]);
            $totalStudents = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Count assigned sections
            $stmt = $pdo->prepare("
                SELECT COUNT(DISTINCT sec.section_id) as count
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                INNER JOIN enrollment e ON s.student_id = e.student_id
                INNER JOIN section sec ON e.section_id = sec.section_id
                WHERE ts.teacher_id = ?
            ");
            $stmt->execute([$teacherId]);
            $totalSections = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Count subjects
            $stmt = $pdo->prepare("
                SELECT COUNT(DISTINCT subject_code) as count 
                FROM schedule 
                WHERE teacher_id = ?
            ");
            $stmt->execute([$teacherId]);
            $totalSubjects = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            echo json_encode([
                'success' => true,
                'stats' => [
                    'totalStudents' => $totalStudents,
                    'totalSections' => $totalSections,
                    'totalSubjects' => $totalSubjects
                ]
            ]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET ARCHIVED STUDENTS FOR TEACHER
    // ========================================
    case 'getTeacherArchivedStudents':
        try {
            $status = $_GET['status'] ?? 'all';
            $schoolYear = $_GET['school_year'] ?? 'all';
            $search = $_GET['search'] ?? '';
            
            $query = "
                SELECT DISTINCT
                    s.student_id,
                    s.student_name,
                    s.grade_level,
                    s.student_status,
                    s.archive_date,
                    s.archive_reason,
                    sec.section_name,
                    e.school_year
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                LEFT JOIN enrollment e ON s.student_id = e.student_id
                LEFT JOIN section sec ON e.section_id = sec.section_id
                WHERE ts.teacher_id = ?
                AND s.student_status != 'active'
            ";
            
            $params = [$teacherId];
            
            if ($status !== 'all') {
                $query .= " AND s.student_status = ?";
                $params[] = $status;
            }
            
            if ($schoolYear !== 'all') {
                $query .= " AND e.school_year = ?";
                $params[] = $schoolYear;
            }
            
            if (!empty($search)) {
                $query .= " AND (s.student_name LIKE ? OR s.student_id LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
            
            $query .= " ORDER BY s.archive_date DESC, s.student_id DESC";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get statistics
            $statsQuery = "
                SELECT 
                    s.student_status,
                    COUNT(*) as count
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                WHERE ts.teacher_id = ?
                AND s.student_status != 'active'
                GROUP BY s.student_status
            ";
            $stmt = $pdo->prepare($statsQuery);
            $stmt->execute([$teacherId]);
            $stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Get school years
            $yearsQuery = "
                SELECT DISTINCT e.school_year
                FROM teacher_students ts
                INNER JOIN student s ON ts.student_id = s.student_id
                INNER JOIN enrollment e ON s.student_id = e.student_id
                WHERE ts.teacher_id = ?
                AND s.student_status != 'active'
                ORDER BY e.school_year DESC
            ";
            $stmt = $pdo->prepare($yearsQuery);
            $stmt->execute([$teacherId]);
            $schoolYears = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            echo json_encode([
                'success' => true,
                'students' => $students,
                'stats' => $stats,
                'schoolYears' => $schoolYears
            ]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // GET ARCHIVED STUDENT DETAILS WITH GRADES
    // ========================================
    case 'getArchivedStudentDetails':
        try {
            $studentId = $_GET['student_id'] ?? '';
            
            if (empty($studentId)) {
                echo json_encode(['success' => false, 'message' => 'Student ID is required']);
                break;
            }
            
            // Verify teacher has access to this student
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM teacher_students 
                WHERE teacher_id = ? AND student_id = ?
            ");
            $stmt->execute([$teacherId, $studentId]);
            $hasAccess = $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
            
            if (!$hasAccess) {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                break;
            }
            
            // Get student details
            $stmt = $pdo->prepare("
                SELECT s.*, e.school_year, e.enrollment_status, sec.section_name
                FROM student s
                LEFT JOIN enrollment e ON s.student_id = e.student_id
                LEFT JOIN section sec ON e.section_id = sec.section_id
                WHERE s.student_id = ?
            ");
            $stmt->execute([$studentId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get all grades
            $stmt = $pdo->prepare("
                SELECT g.*, sub.subject_name
                FROM grade g
                INNER JOIN subject sub ON g.subject_code = sub.subject_code
                WHERE g.student_id = ?
                ORDER BY sub.subject_name, g.grading_period
            ");
            $stmt->execute([$studentId]);
            $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'student' => $student,
                'grades' => $grades
            ]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>