<?php
require_once '../config.php';
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
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

switch($action) {
    // ========================================
    // DASHBOARD STATISTICS
    // ========================================
    case 'getDashboardStats':
        try {
            $stats = [];
            
            // Total Teachers
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM teacher");
            $stats['totalTeachers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Teachers with accounts
            $stmt = $pdo->query("
                SELECT COUNT(*) as count 
                FROM teacher t 
                INNER JOIN user_account u ON t.teacher_id = u.teacher_id
            ");
            $stats['teachersWithAccounts'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Teachers without accounts
            $stats['teachersWithoutAccounts'] = $stats['totalTeachers'] - $stats['teachersWithAccounts'];
            
            // Total Students
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM student");
            $stats['totalStudents'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Enrolled Students
            $stmt = $pdo->query("
                SELECT COUNT(*) as count 
                FROM enrollment 
                WHERE enrollment_status = 'enrolled'
            ");
            $stats['enrolledStudents'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Active Schedules
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM schedule");
            $stats['totalSchedules'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Pending Enrollments
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM enrollment WHERE enrollment_status = 'pending'");
            $stats['pendingEnrollments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            echo json_encode(['success' => true, 'stats' => $stats]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'getActivityLogs':
        try {
            // Check if activity_log table exists
            $stmt = $pdo->query("SHOW TABLES LIKE 'activity_log'");
            $tableExists = $stmt->rowCount() > 0;
            
            if ($tableExists) {
                // Get recent login activities from activity_log table
                $stmt = $pdo->query("
                    SELECT 
                        al.username,
                        al.role,
                        al.login_time,
                        al.activity_type,
                        al.activity_description,
                        CASE 
                            WHEN al.role = 'teacher' THEN t.teacher_name
                            WHEN al.role = 'student' THEN s.student_name
                            WHEN al.role = 'admin' THEN 'Administrator'
                            ELSE al.username
                        END as name
                    FROM activity_log al
                    LEFT JOIN user_account u ON al.user_id = u.user_id
                    LEFT JOIN teacher t ON u.teacher_id = t.teacher_id
                    LEFT JOIN student s ON u.student_id = s.student_id
                    WHERE al.role IN ('teacher', 'student', 'admin')
                    ORDER BY al.login_time DESC
                    LIMIT 50
                ");
                $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Fallback: use user_account creation dates
                $logs = [];
            }
            
            // If no logs, fallback to user_account creation dates
            if (empty($logs)) {
                $stmt = $pdo->query("
                    SELECT 
                        u.username,
                        u.role,
                        u.date_created as login_time,
                        'account_created' as activity_type,
                        'Account created' as activity_description,
                        CASE 
                            WHEN u.role = 'teacher' THEN t.teacher_name
                            WHEN u.role = 'student' THEN s.student_name
                            ELSE 'Admin'
                        END as name
                    FROM user_account u
                    LEFT JOIN teacher t ON u.teacher_id = t.teacher_id
                    LEFT JOIN student s ON u.student_id = s.student_id
                    WHERE u.role IN ('teacher', 'student')
                    ORDER BY u.date_created DESC
                    LIMIT 20
                ");
                $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            echo json_encode(['success' => true, 'logs' => $logs]);
        } catch(PDOException $e) {
            // Return empty logs instead of error
            echo json_encode(['success' => true, 'logs' => []]);
        }
        break;

    // ========================================
    // TEACHER ACCOUNTS
    // ========================================
    case 'getTeacherAccounts':
        try {
            $stmt = $pdo->query("
                SELECT t.*, u.username 
                FROM teacher t
                LEFT JOIN user_account u ON t.teacher_id = u.teacher_id
                ORDER BY t.teacher_id
            ");
            $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'teachers' => $teachers]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'addTeacher':
        try {
            $teacherName = $_POST['teacher_name'];
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // Generate teacher ID
            $stmt = $pdo->query("SELECT teacher_id FROM teacher ORDER BY teacher_id DESC LIMIT 1");
            $lastTeacher = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($lastTeacher) {
                $lastNum = (int)substr($lastTeacher['teacher_id'], 1);
                $newNum = $lastNum + 1;
            } else {
                $newNum = 1;
            }
            $teacherId = 'T' . str_pad($newNum, 3, '0', STR_PAD_LEFT);
            
            // Insert teacher
            $stmt = $pdo->prepare("INSERT INTO teacher (teacher_id, teacher_name) VALUES (?, ?)");
            $stmt->execute([$teacherId, $teacherName]);
            
            // Create user account
            $stmt = $pdo->prepare("
                INSERT INTO user_account (username, password, role, teacher_id) 
                VALUES (?, ?, 'teacher', ?)
            ");
            $stmt->execute([$username, $password, $teacherId]);
            
            echo json_encode(['success' => true, 'message' => 'Teacher added successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'getTeacherDetails':
        try {
            $teacherId = $_GET['teacher_id'];
            $stmt = $pdo->prepare("
                SELECT t.*, u.username,
                    (SELECT COUNT(*) FROM subject WHERE teacher_id = t.teacher_id) as subject_count,
                    (SELECT COUNT(*) FROM schedule WHERE teacher_id = t.teacher_id) as schedule_count
                FROM teacher t
                LEFT JOIN user_account u ON t.teacher_id = u.teacher_id
                WHERE t.teacher_id = ?
            ");
            $stmt->execute([$teacherId]);
            $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'teacher' => $teacher]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'updateTeacher':
        try {
            $teacherId = $_POST['teacher_id'];
            $teacherName = $_POST['teacher_name'];
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Update teacher name
            $stmt = $pdo->prepare("UPDATE teacher SET teacher_name = ? WHERE teacher_id = ?");
            $stmt->execute([$teacherName, $teacherId]);
            
            // Update username if provided
            if (!empty($username)) {
                // Check if user account exists
                $stmt = $pdo->prepare("SELECT user_id FROM user_account WHERE teacher_id = ?");
                $stmt->execute([$teacherId]);
                $userExists = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($userExists) {
                    // Update existing account
                    if (!empty($password)) {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE user_account SET username = ?, password = ? WHERE teacher_id = ?");
                        $stmt->execute([$username, $hashedPassword, $teacherId]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE user_account SET username = ? WHERE teacher_id = ?");
                        $stmt->execute([$username, $teacherId]);
                    }
                } else {
                    // Create new account if it doesn't exist
                    if (!empty($password)) {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("
                            INSERT INTO user_account (username, password, role, teacher_id) 
                            VALUES (?, ?, 'teacher', ?)
                        ");
                        $stmt->execute([$username, $hashedPassword, $teacherId]);
                    }
                }
            } elseif (!empty($password)) {
                // Only update password if username not provided
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE user_account SET password = ? WHERE teacher_id = ?");
                $stmt->execute([$hashedPassword, $teacherId]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Teacher updated successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'deleteTeacher':
        try {
            $teacherId = $_POST['teacher_id'];
            
            // Delete user account first
            $stmt = $pdo->prepare("DELETE FROM user_account WHERE teacher_id = ?");
            $stmt->execute([$teacherId]);
            
            // Update subjects to remove teacher assignment
            $stmt = $pdo->prepare("UPDATE subject SET teacher_id = NULL WHERE teacher_id = ?");
            $stmt->execute([$teacherId]);
            
            // Delete schedules
            $stmt = $pdo->prepare("DELETE FROM schedule WHERE teacher_id = ?");
            $stmt->execute([$teacherId]);
            
            // Delete teacher
            $stmt = $pdo->prepare("DELETE FROM teacher WHERE teacher_id = ?");
            $stmt->execute([$teacherId]);
            
            echo json_encode(['success' => true, 'message' => 'Teacher deleted successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // STUDENT ACCOUNTS
    // ========================================
    case 'getStudentAccounts':
        try {
            $stmt = $pdo->query("
                SELECT s.*, e.enrollment_status, sec.section_name
                FROM student s
                LEFT JOIN enrollment e ON s.student_id = e.student_id
                LEFT JOIN section sec ON e.section_id = sec.section_id
                ORDER BY s.student_id DESC
            ");
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'students' => $students]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'getStudentDetails':
        try {
            $studentId = $_GET['student_id'];
            $stmt = $pdo->prepare("
                SELECT s.*, e.enrollment_status, sec.section_name
                FROM student s
                LEFT JOIN enrollment e ON s.student_id = e.student_id
                LEFT JOIN section sec ON e.section_id = sec.section_id
                WHERE s.student_id = ?
            ");
            $stmt->execute([$studentId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'student' => $student]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'updateStudent':
        try {
            $studentId = $_POST['student_id'];
            $studentName = $_POST['student_name'];
            $gradeLevel = $_POST['grade_level'];
            $gender = $_POST['gender'] ?? null;
            $birthdate = $_POST['birthdate'] ?? null;
            $religion = $_POST['religion'] ?? null;
            $address = $_POST['address'] ?? null;
            $contactNumber = $_POST['contact_number'] ?? null;
            
            $stmt = $pdo->prepare("
                UPDATE student SET 
                    student_name = ?,
                    grade_level = ?,
                    gender = ?,
                    birthdate = ?,
                    religion = ?,
                    address = ?,
                    contact_number = ?
                WHERE student_id = ?
            ");
            $stmt->execute([
                $studentName, 
                $gradeLevel, 
                $gender, 
                $birthdate ?: null, 
                $religion, 
                $address, 
                $contactNumber, 
                $studentId
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Student updated successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'deleteStudent':
        try {
            $studentId = $_POST['student_id'];
            
            // Delete related records first
            $stmt = $pdo->prepare("DELETE FROM grade WHERE student_id = ?");
            $stmt->execute([$studentId]);
            
            $stmt = $pdo->prepare("DELETE FROM enrollment WHERE student_id = ?");
            $stmt->execute([$studentId]);
            
            $stmt = $pdo->prepare("DELETE FROM user_account WHERE student_id = ?");
            $stmt->execute([$studentId]);
            
            // Delete student
            $stmt = $pdo->prepare("DELETE FROM student WHERE student_id = ?");
            $stmt->execute([$studentId]);
            
            echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // SCHEDULE MANAGEMENT
    // ========================================
    case 'getSchedules':
        try {
            $stmt = $pdo->query("
                SELECT 
                    sch.*,
                    t.teacher_name,
                    sub.subject_name,
                    sec.section_name
                FROM schedule sch
                INNER JOIN teacher t ON sch.teacher_id = t.teacher_id
                INNER JOIN subject sub ON sch.subject_code = sub.subject_code
                INNER JOIN section sec ON sch.section_id = sec.section_id
                ORDER BY sch.day_time, sec.section_name
            ");
            $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'schedules' => $schedules]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'getSubjects':
        try {
            $stmt = $pdo->query("SELECT * FROM subject ORDER BY subject_name");
            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'subjects' => $subjects]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'getSections':
        try {
            $stmt = $pdo->query("SELECT * FROM section ORDER BY grade_level, section_name");
            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'sections' => $sections]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'addSchedule':
        try {
            $teacherId = $_POST['teacher_id'];
            $subjectCode = $_POST['subject_code'];
            $sectionId = $_POST['section_id'];
            $day = $_POST['day'];
            $time = $_POST['time'];
            $roomNumber = $_POST['room_number'];
            
            // Create datetime from day and time
            // Find next occurrence of the specified day
            $dayOfWeek = date('N', strtotime($day)); // 1 (Monday) through 7 (Sunday)
            $currentDayOfWeek = date('N');
            $daysUntilNext = ($dayOfWeek - $currentDayOfWeek + 7) % 7;
            if ($daysUntilNext == 0) $daysUntilNext = 7; // If today, schedule for next week
            
            $nextDate = date('Y-m-d', strtotime("+$daysUntilNext days"));
            $datetime = $nextDate . ' ' . $time;
            
            // Check for conflicts
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM schedule 
                WHERE section_id = ? 
                AND DATE_FORMAT(day_time, '%Y-%m-%d %H:%i') = ?
            ");
            $stmt->execute([$sectionId, $datetime]);
            $conflict = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($conflict['count'] > 0) {
                echo json_encode(['success' => false, 'message' => 'Schedule conflict detected for this section!']);
                break;
            }
            
            // Insert schedule
            $stmt = $pdo->prepare("
                INSERT INTO schedule (day_time, room_number, subject_code, section_id, teacher_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$datetime, $roomNumber, $subjectCode, $sectionId, $teacherId]);
            
            echo json_encode(['success' => true, 'message' => 'Schedule added successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'deleteSchedule':
        try {
            $scheduleId = $_POST['schedule_id'];
            $stmt = $pdo->prepare("DELETE FROM schedule WHERE schedule_id = ?");
            $stmt->execute([$scheduleId]);
            echo json_encode(['success' => true, 'message' => 'Schedule deleted successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ========================================
    // ENROLLMENT MANAGEMENT
    // ========================================
    case 'getPendingEnrollments':
        try {
            // Get pending enrollments
            $stmt = $pdo->query("
                SELECT e.*, s.student_name, s.grade_level
                FROM enrollment e
                INNER JOIN student s ON e.student_id = s.student_id
                WHERE e.enrollment_status = 'pending'
                ORDER BY e.date_enrolled DESC
            ");
            $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get all sections
            $stmt = $pdo->query("SELECT * FROM section ORDER BY grade_level, section_id");
            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true, 
                'enrollments' => $enrollments,
                'sections' => $sections
            ]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'approveEnrollment':
        try {
            $enrollmentId = $_POST['enrollment_id'];
            $sectionId = $_POST['section_id'];
            
            // Get student details for logging
            $stmt = $pdo->prepare("
                SELECT s.student_name, s.student_id, s.grade_level
                FROM enrollment e
                INNER JOIN student s ON e.student_id = s.student_id
                WHERE e.enrollment_id = ?
            ");
            $stmt->execute([$enrollmentId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Update enrollment
            $stmt = $pdo->prepare("
                UPDATE enrollment 
                SET enrollment_status = 'enrolled', section_id = ?
                WHERE enrollment_id = ?
            ");
            $stmt->execute([$sectionId, $enrollmentId]);
            
            // Log the enrollment approval activity
            if ($student) {
                logActivity(
                    $student['student_name'],
                    'student',
                    'enrollment_approved',
                    "Enrollment approved for " . $student['student_name'] . " - " . $student['grade_level']
                );
            }
            
            echo json_encode(['success' => true, 'message' => 'Enrollment approved successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        case 'rejectEnrollment':
        try {
            $enrollmentId = $_POST['enrollment_id'];
            
            // Update enrollment status to rejected
            $stmt = $pdo->prepare("
                UPDATE enrollment 
                SET enrollment_status = 'rejected'
                WHERE enrollment_id = ?
            ");
            $stmt->execute([$enrollmentId]);
            
            echo json_encode(['success' => true, 'message' => 'Enrollment rejected']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
    // ========================================
    // TEACHER-STUDENT ASSIGNMENT
    // ========================================
    
    case 'getTeacherSectionsForStudents':
    try {
        $teacher_id = $_GET['teacher_id'] ?? '';
        
        if (empty($teacher_id)) {
            echo json_encode(['success' => false, 'message' => 'Teacher ID is required']);
            exit;
        }
        
        // Get ALL sections with enrolled students (not dependent on schedule)
        $stmt = $pdo->query("
            SELECT DISTINCT sec.section_id, sec.section_name, sec.grade_level 
            FROM section sec
            INNER JOIN enrollment e ON sec.section_id = e.section_id
            WHERE e.enrollment_status = 'enrolled'
            ORDER BY sec.grade_level, sec.section_name
        ");
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'sections' => $sections
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
    break;

case 'getStudentsByTeacherSections':
    try {
        $teacher_id = $_GET['teacher_id'] ?? '';
        
        if (empty($teacher_id)) {
            echo json_encode(['success' => false, 'message' => 'Teacher ID is required']);
            exit;
        }
        
        // Get ALL enrolled students (not filtered by teacher's schedules)
        $studentsQuery = "
            SELECT DISTINCT 
                s.student_id, 
                s.student_name, 
                sec.section_id, 
                sec.section_name, 
                sec.grade_level
            FROM student s
            INNER JOIN enrollment e ON s.student_id = e.student_id
            INNER JOIN section sec ON e.section_id = sec.section_id
            WHERE e.enrollment_status = 'enrolled'
            ORDER BY sec.section_name, s.student_name
        ";
        
        $stmt = $pdo->prepare($studentsQuery);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get students already assigned to this teacher
        $assignedQuery = "
            SELECT 
                ts.student_id, 
                s.student_name, 
                sec.section_name,
                sec.section_id,
                ts.assigned_date
            FROM teacher_students ts
            INNER JOIN student s ON ts.student_id = s.student_id
            LEFT JOIN enrollment e ON s.student_id = e.student_id
            LEFT JOIN section sec ON e.section_id = sec.section_id
            WHERE ts.teacher_id = ?
            ORDER BY s.student_name
        ";
        
        $stmt = $pdo->prepare($assignedQuery);
        $stmt->execute([$teacher_id]);
        $assignedStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'students' => $students,
            'assignedStudents' => $assignedStudents
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
    break;

case 'assignStudentToTeacher':
    try {
        $teacher_id = $_POST['teacher_id'] ?? '';
        $student_id = $_POST['student_id'] ?? '';
        
        if (empty($teacher_id) || empty($student_id)) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }
        
        // Verify student exists and is enrolled
        $stmt = $pdo->prepare("
            SELECT s.student_id, s.student_name, e.enrollment_status 
            FROM student s
            LEFT JOIN enrollment e ON s.student_id = e.student_id
            WHERE s.student_id = ?
        ");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
            exit;
        }
        
        if ($student['enrollment_status'] !== 'enrolled') {
            echo json_encode(['success' => false, 'message' => 'Student must be enrolled first']);
            exit;
        }
        
        // Check if already assigned
        $stmt = $pdo->prepare("SELECT id FROM teacher_students WHERE teacher_id = ? AND student_id = ?");
        $stmt->execute([$teacher_id, $student_id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Student already assigned to this teacher']);
            exit;
        }
        
        // Insert assignment
        $stmt = $pdo->prepare("INSERT INTO teacher_students (teacher_id, student_id, assigned_date) VALUES (?, ?, NOW())");
        $stmt->execute([$teacher_id, $student_id]);
        
        echo json_encode(['success' => true, 'message' => 'Student assigned successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
    break;

case 'assignMultipleStudentsToTeacher':
    try {
        $teacher_id = $_POST['teacher_id'] ?? '';
        $student_ids = $_POST['student_ids'] ?? '';
        
        if (empty($teacher_id) || empty($student_ids)) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }
        
        $studentArray = explode(',', $student_ids);
        $successCount = 0;
        $skipCount = 0;
        
        $pdo->beginTransaction();
        
        // Check enrollment status
        $checkStmt = $pdo->prepare("
            SELECT s.student_id, e.enrollment_status 
            FROM student s
            LEFT JOIN enrollment e ON s.student_id = e.student_id
            WHERE s.student_id = ?
        ");
        
        $insertStmt = $pdo->prepare("INSERT IGNORE INTO teacher_students (teacher_id, student_id, assigned_date) VALUES (?, ?, NOW())");
        
        foreach ($studentArray as $student_id) {
            $student_id = trim($student_id);
            if (!empty($student_id)) {
                // Check if student is enrolled
                $checkStmt->execute([$student_id]);
                $student = $checkStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($student && $student['enrollment_status'] === 'enrolled') {
                    $insertStmt->execute([$teacher_id, $student_id]);
                    if ($insertStmt->rowCount() > 0) {
                        $successCount++;
                    }
                } else {
                    $skipCount++;
                }
            }
        }
        
        $pdo->commit();
        
        $message = "$successCount student(s) assigned successfully";
        if ($skipCount > 0) {
            $message .= " ($skipCount skipped - not enrolled)";
        }
        
        echo json_encode([
            'success' => true, 
            'message' => $message
        ]);
    } catch(Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
    break;

case 'assignSectionStudentsToTeacher':
    try {
        $teacher_id = $_POST['teacher_id'] ?? '';
        $section_name = $_POST['section_name'] ?? '';
        
        if (empty($teacher_id) || empty($section_name)) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }
        
        $pdo->beginTransaction();
        
        // Get all enrolled students from the section
        $stmt = $pdo->prepare("
            SELECT s.student_id 
            FROM student s
            INNER JOIN enrollment e ON s.student_id = e.student_id
            INNER JOIN section sec ON e.section_id = sec.section_id
            WHERE sec.section_name = ?
            AND e.enrollment_status = 'enrolled'
        ");
        $stmt->execute([$section_name]);
        
        $successCount = 0;
        $insertStmt = $pdo->prepare("INSERT IGNORE INTO teacher_students (teacher_id, student_id, assigned_date) VALUES (?, ?, NOW())");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $insertStmt->execute([$teacher_id, $row['student_id']]);
            if ($insertStmt->rowCount() > 0) {
                $successCount++;
            }
        }
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => "$successCount student(s) from $section_name assigned successfully"
        ]);
    } catch(Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
    break;

case 'removeStudentFromTeacher':
    try {
        $teacher_id = $_POST['teacher_id'] ?? '';
        $student_id = $_POST['student_id'] ?? '';
        
        if (empty($teacher_id) || empty($student_id)) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }
        
        $stmt = $pdo->prepare("DELETE FROM teacher_students WHERE teacher_id = ? AND student_id = ?");
        $stmt->execute([$teacher_id, $student_id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Student removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Assignment not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
    break;

case 'removeAllTeacherStudents':
    try {
        $teacher_id = $_POST['teacher_id'] ?? '';
        
        if (empty($teacher_id)) {
            echo json_encode(['success' => false, 'message' => 'Missing teacher ID']);
            exit;
        }
        
        $stmt = $pdo->prepare("DELETE FROM teacher_students WHERE teacher_id = ?");
        $stmt->execute([$teacher_id]);
        
        $count = $stmt->rowCount();
        
        echo json_encode([
            'success' => true, 
            'message' => "$count assignment(s) removed successfully"
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
    break;

case 'getTeacherAssignmentStats':
    try {
        $teacher_id = $_GET['teacher_id'] ?? '';
        
        if (empty($teacher_id)) {
            echo json_encode(['success' => false, 'message' => 'Missing teacher ID']);
            exit;
        }
        
        // Count assigned students
        $countQuery = "SELECT COUNT(*) as total FROM teacher_students WHERE teacher_id = ?";
        $stmt = $pdo->prepare($countQuery);
        $stmt->execute([$teacher_id]);
        $totalAssigned = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Count total enrolled students (available pool)
        $availableQuery = "
            SELECT COUNT(*) as total
            FROM student s
            INNER JOIN enrollment e ON s.student_id = e.student_id
            WHERE e.enrollment_status = 'enrolled'
        ";
        $stmt = $pdo->prepare($availableQuery);
        $stmt->execute();
        $totalAvailable = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Count unique sections of assigned students
        $sectionsQuery = "
            SELECT COUNT(DISTINCT sec.section_id) as total
            FROM teacher_students ts
            INNER JOIN student s ON ts.student_id = s.student_id
            INNER JOIN enrollment e ON s.student_id = e.student_id
            INNER JOIN section sec ON e.section_id = sec.section_id
            WHERE ts.teacher_id = ?
        ";
        $stmt = $pdo->prepare($sectionsQuery);
        $stmt->execute([$teacher_id]);
        $totalSections = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        echo json_encode([
            'success' => true,
            'stats' => [
                'totalAssigned' => (int)$totalAssigned,
                'totalSections' => (int)$totalSections,
                'totalAvailable' => (int)$totalAvailable,
                'unassigned' => max(0, $totalAvailable - $totalAssigned)
            ]
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
    break;

// New case: Get students by grade/section for easier assignment
case 'getStudentsByGradeSection':
    try {
        $grade_level = $_GET['grade_level'] ?? '';
        $section_id = $_GET['section_id'] ?? '';
        
        $query = "
            SELECT 
                s.student_id, 
                s.student_name, 
                s.grade_level,
                sec.section_name,
                sec.section_id
            FROM student s
            INNER JOIN enrollment e ON s.student_id = e.student_id
            INNER JOIN section sec ON e.section_id = sec.section_id
            WHERE e.enrollment_status = 'enrolled'
        ";
        
        $params = [];
        
        if (!empty($grade_level)) {
            $query .= " AND s.grade_level = ?";
            $params[] = $grade_level;
        }
        
        if (!empty($section_id)) {
            $query .= " AND sec.section_id = ?";
            $params[] = $section_id;
        }
        
        $query .= " ORDER BY sec.section_name, s.student_name";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'students' => $students
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
    break;

    
// ========================================
// STUDENT ARCHIVE MANAGEMENT
// ========================================

case 'getArchivedStudents':
    try {
        $status = $_GET['status'] ?? 'all'; // all, graduated, transferred, archived
        
        $query = "
            SELECT 
                s.*,
                e.school_year,
                e.enrollment_status,
                sec.section_name,
                al.action_date as last_action_date,
                al.reason as last_action_reason
            FROM student s
            LEFT JOIN enrollment e ON s.student_id = e.student_id
            LEFT JOIN section sec ON e.section_id = sec.section_id
            LEFT JOIN archive_log al ON s.student_id = al.student_id 
                AND al.log_id = (
                    SELECT MAX(log_id) 
                    FROM archive_log 
                    WHERE student_id = s.student_id
                )
            WHERE s.student_status != 'active'
        ";
        
        if ($status !== 'all') {
            $query .= " AND s.student_status = :status";
        }
        
        $query .= " ORDER BY s.archive_date DESC, s.student_id DESC";
        
        $stmt = $pdo->prepare($query);
        
        if ($status !== 'all') {
            $stmt->execute(['status' => $status]);
        } else {
            $stmt->execute();
        }
        
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'students' => $students]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    break;

case 'getArchiveStatistics':
    try {
        // Get counts by status
        $stmt = $pdo->query("
            SELECT 
                student_status,
                COUNT(*) as count
            FROM student
            GROUP BY student_status
        ");
        $statusCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Get counts by school year
        $stmt = $pdo->query("
            SELECT 
                e.school_year,
                COUNT(DISTINCT s.student_id) as count
            FROM student s
            INNER JOIN enrollment e ON s.student_id = e.student_id
            WHERE s.student_status != 'active'
            GROUP BY e.school_year
            ORDER BY e.school_year DESC
        ");
        $yearCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get recent archive actions
        $stmt = $pdo->query("
            SELECT 
                al.*,
                s.student_name
            FROM archive_log al
            INNER JOIN student s ON al.student_id = s.student_id
            ORDER BY al.action_date DESC
            LIMIT 10
        ");
        $recentActions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'statusCounts' => $statusCounts,
            'yearCounts' => $yearCounts,
            'recentActions' => $recentActions
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    break;

case 'archiveStudent':
    try {
        $studentId = $_POST['student_id'] ?? '';
        $reason = $_POST['reason'] ?? '';
        $newStatus = $_POST['status'] ?? 'archived'; // graduated, transferred, archived
        
        if (empty($studentId) || empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            break;
        }
        
        // Get current status
        $stmt = $pdo->prepare("SELECT student_status FROM student WHERE student_id = ?");
        $stmt->execute([$studentId]);
        $currentStatus = $stmt->fetch(PDO::FETCH_ASSOC)['student_status'];
        
        $pdo->beginTransaction();
        
        // Update student status
        $stmt = $pdo->prepare("
            UPDATE student 
            SET student_status = ?, 
                archive_date = NOW(), 
                archive_reason = ?
            WHERE student_id = ?
        ");
        $stmt->execute([$newStatus, $reason, $studentId]);
        
        // Update enrollment status
        $stmt = $pdo->prepare("
            UPDATE enrollment 
            SET enrollment_status = ?
            WHERE student_id = ?
        ");
        $stmt->execute([$newStatus, $studentId]);
        
        // Log the action
        $stmt = $pdo->prepare("
            INSERT INTO archive_log 
            (student_id, action, action_by, reason, previous_status, new_status)
            VALUES (?, 'archived', ?, ?, ?, ?)
        ");
        $stmt->execute([
            $studentId, 
            $_SESSION['admin_id'], 
            $reason, 
            $currentStatus, 
            $newStatus
        ]);
        
        $pdo->commit();
        
        echo json_encode(['success' => true, 'message' => 'Student archived successfully']);
    } catch(Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    break;

case 'restoreStudent':
    try {
        $studentId = $_POST['student_id'] ?? '';
        $reason = $_POST['reason'] ?? 'Restored by admin';
        
        if (empty($studentId)) {
            echo json_encode(['success' => false, 'message' => 'Student ID is required']);
            break;
        }
        
        // Get current status
        $stmt = $pdo->prepare("SELECT student_status FROM student WHERE student_id = ?");
        $stmt->execute([$studentId]);
        $currentStatus = $stmt->fetch(PDO::FETCH_ASSOC)['student_status'];
        
        $pdo->beginTransaction();
        
        // Restore student
        $stmt = $pdo->prepare("
            UPDATE student 
            SET student_status = 'active', 
                archive_date = NULL, 
                archive_reason = NULL
            WHERE student_id = ?
        ");
        $stmt->execute([$studentId]);
        
        // Update enrollment status
        $stmt = $pdo->prepare("
            UPDATE enrollment 
            SET enrollment_status = 'enrolled'
            WHERE student_id = ?
            ORDER BY date_enrolled DESC
            LIMIT 1
        ");
        $stmt->execute([$studentId]);
        
        // Log the action
        $stmt = $pdo->prepare("
            INSERT INTO archive_log 
            (student_id, action, action_by, reason, previous_status, new_status)
            VALUES (?, 'restored', ?, ?, ?, 'active')
        ");
        $stmt->execute([
            $studentId, 
            $_SESSION['admin_id'], 
            $reason, 
            $currentStatus
        ]);
        
        $pdo->commit();
        
        echo json_encode(['success' => true, 'message' => 'Student restored successfully']);
    } catch(Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    break;

case 'getStudentArchiveHistory':
    try {
        $studentId = $_GET['student_id'] ?? '';
        
        if (empty($studentId)) {
            echo json_encode(['success' => false, 'message' => 'Student ID is required']);
            break;
        }
        
        // Get student details
        $stmt = $pdo->prepare("
            SELECT * FROM student WHERE student_id = ?
        ");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get all enrollments
        $stmt = $pdo->prepare("
            SELECT 
                e.*,
                sec.section_name
            FROM enrollment e
            LEFT JOIN section sec ON e.section_id = sec.section_id
            WHERE e.student_id = ?
            ORDER BY e.school_year DESC
        ");
        $stmt->execute([$studentId]);
        $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get all grades
        $stmt = $pdo->prepare("
            SELECT 
                g.*,
                sub.subject_name
            FROM grade g
            INNER JOIN subject sub ON g.subject_code = sub.subject_code
            WHERE g.student_id = ?
            ORDER BY g.grading_period
        ");
        $stmt->execute([$studentId]);
        $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get archive log
        $stmt = $pdo->prepare("
            SELECT * FROM archive_log 
            WHERE student_id = ?
            ORDER BY action_date DESC
        ");
        $stmt->execute([$studentId]);
        $archiveLog = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'student' => $student,
            'enrollments' => $enrollments,
            'grades' => $grades,
            'archiveLog' => $archiveLog
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    break;

case 'exportArchivedStudents':
    try {
        $schoolYear = $_GET['school_year'] ?? 'all';
        $status = $_GET['status'] ?? 'all';
        
        $query = "
            SELECT 
                s.student_id,
                s.student_name,
                s.student_status,
                s.grade_level,
                s.gender,
                s.archive_date,
                s.archive_reason,
                e.school_year,
                sec.section_name
            FROM student s
            LEFT JOIN enrollment e ON s.student_id = e.student_id
            LEFT JOIN section sec ON e.section_id = sec.section_id
            WHERE s.student_status != 'active'
        ";
        
        $params = [];
        
        if ($schoolYear !== 'all') {
            $query .= " AND e.school_year = ?";
            $params[] = $schoolYear;
        }
        
        if ($status !== 'all') {
            $query .= " AND s.student_status = ?";
            $params[] = $status;
        }
        
        $query .= " ORDER BY s.archive_date DESC";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'students' => $students]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>