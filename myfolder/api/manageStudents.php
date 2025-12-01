<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$host = 'localhost';
$dbname = 'enrollment_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    
    // GET - Fetch all students
    if ($action === 'getAll') {
        $stmt = $pdo->query("
            SELECT s.student_id, s.student_name, s.grade_level, s.gender,
                   e.enrollment_id, e.enrollment_status, e.section_id, e.date_enrolled,
                   sec.section_name,
                   u.username
            FROM student s
            LEFT JOIN enrollment e ON s.student_id = e.student_id
            LEFT JOIN section sec ON e.section_id = sec.section_id
            LEFT JOIN user_account u ON s.student_id = u.student_id
            ORDER BY s.student_name
        ");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $students]);
    }
    
    // GET - Fetch all sections
    elseif ($action === 'getSections') {
        $stmt = $pdo->query("
            SELECT section_id, section_name, grade_level, adviser
            FROM section
            ORDER BY grade_level, section_name
        ");
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $sections]);
    }
    
    // GET - Fetch pending enrollments
    elseif ($action === 'getPending') {
        $stmt = $pdo->query("
            SELECT e.enrollment_id, s.student_id, s.student_name, s.grade_level,
                   e.enrollment_status, e.date_enrolled, e.section_id
            FROM enrollment e
            INNER JOIN student s ON e.student_id = s.student_id
            WHERE e.enrollment_status = 'pending'
            ORDER BY e.date_enrolled DESC
        ");
        $pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $pending]);
    }
    
    // GET - Fetch enrolled students with stats
    elseif ($action === 'getEnrolled') {
        // Get enrolled students
        $stmt = $pdo->query("
            SELECT s.student_id, s.student_name, s.grade_level, s.gender,
                   e.enrollment_id, e.enrollment_status, e.section_id, e.date_enrolled,
                   sec.section_name,
                   u.username
            FROM student s
            INNER JOIN enrollment e ON s.student_id = e.student_id
            LEFT JOIN section sec ON e.section_id = sec.section_id
            LEFT JOIN user_account u ON s.student_id = u.student_id
            WHERE e.enrollment_status = 'enrolled'
            ORDER BY s.student_name
        ");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get sections for filter
        $sectionsStmt = $pdo->query("
            SELECT DISTINCT sec.section_id, sec.section_name
            FROM section sec
            INNER JOIN enrollment e ON sec.section_id = e.section_id
            WHERE e.enrollment_status = 'enrolled'
            ORDER BY sec.section_name
        ");
        $sections = $sectionsStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get stats
        $totalEnrolled = count($students);
        
        // Enrolled this month
        $monthStmt = $pdo->query("
            SELECT COUNT(*) as count FROM enrollment 
            WHERE enrollment_status = 'enrolled' 
            AND MONTH(date_enrolled) = MONTH(CURRENT_DATE())
            AND YEAR(date_enrolled) = YEAR(CURRENT_DATE())
        ");
        $thisMonth = $monthStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Unique sections count
        $sectionsCount = count($sections);
        
        // Unique grade levels
        $gradesStmt = $pdo->query("
            SELECT COUNT(DISTINCT s.grade_level) as count
            FROM student s
            INNER JOIN enrollment e ON s.student_id = e.student_id
            WHERE e.enrollment_status = 'enrolled'
        ");
        $gradeLevelsCount = $gradesStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        echo json_encode([
            'success' => true, 
            'data' => $students,
            'sections' => $sections,
            'stats' => [
                'total' => $totalEnrolled,
                'thisMonth' => $thisMonth,
                'sectionsCount' => $sectionsCount,
                'gradeLevelsCount' => $gradeLevelsCount
            ]
        ]);
    }
    
    // GET - Fetch single student details
    elseif ($action === 'getStudent') {
        $studentId = $_GET['student_id'] ?? '';
        
        if (empty($studentId)) {
            echo json_encode(['success' => false, 'message' => 'Student ID required']);
            exit();
        }
        
        $stmt = $pdo->prepare("
            SELECT s.student_id, s.student_name, s.grade_level, s.gender, s.birthdate,
                   s.religion, s.address, s.contact_number,
                   s.father_name, s.father_occupation, s.mother_name, s.mother_occupation,
                   s.guardian_name, s.guardian_relationship,
                   s.previous_school, s.last_school_year,
                   e.enrollment_id, e.enrollment_status, e.section_id, e.date_enrolled,
                   sec.section_name, 
                   u.username
            FROM student s
            LEFT JOIN enrollment e ON s.student_id = e.student_id
            LEFT JOIN section sec ON e.section_id = sec.section_id
            LEFT JOIN user_account u ON s.student_id = u.student_id
            WHERE s.student_id = ?
        ");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($student) {
            echo json_encode(['success' => true, 'data' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }
    }
    
    // APPROVE - Approve enrollment
    elseif ($action === 'approve') {
        $enrollmentId = $_POST['enrollment_id'];
        $sectionId = $_POST['section_id'];
        
        $stmt = $pdo->prepare("
            UPDATE enrollment 
            SET enrollment_status = 'enrolled', section_id = ? 
            WHERE enrollment_id = ?
        ");
        $stmt->execute([$sectionId, $enrollmentId]);
        
        // Get student name for logging
        $stmt = $pdo->prepare("
            SELECT s.student_name, s.student_id 
            FROM enrollment e
            INNER JOIN student s ON e.student_id = s.student_id
            WHERE e.enrollment_id = ?
        ");
        $stmt->execute([$enrollmentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Log activity
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (username, role, activity_type, activity_description) 
            VALUES (?, 'admin', 'enrollment_approved', ?)
        ");
        $stmt->execute([
            $_SESSION['admin_username'] ?? 'admin',
            "Admin approved enrollment for {$student['student_name']} ({$student['student_id']})"
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Enrollment approved']);
    }
    
    // REJECT - Reject enrollment
    elseif ($action === 'reject') {
        $enrollmentId = $_POST['enrollment_id'];
        
        // Get student info
        $stmt = $pdo->prepare("
            SELECT s.student_name, s.student_id 
            FROM enrollment e
            INNER JOIN student s ON e.student_id = s.student_id
            WHERE e.enrollment_id = ?
        ");
        $stmt->execute([$enrollmentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("
            UPDATE enrollment 
            SET enrollment_status = 'rejected' 
            WHERE enrollment_id = ?
        ");
        $stmt->execute([$enrollmentId]);
        
        // Log activity
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (username, role, activity_type, activity_description) 
            VALUES (?, 'admin', 'enrollment_rejected', ?)
        ");
        $stmt->execute([
            $_SESSION['admin_username'] ?? 'admin',
            "Admin rejected enrollment for {$student['student_name']} ({$student['student_id']})"
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Enrollment rejected']);
    }
    
    // UPDATE SECTION - Change student section
    elseif ($action === 'updateSection') {
        $enrollmentId = $_POST['enrollment_id'];
        $sectionId = $_POST['section_id'];
        
        // Get student info for logging
        $stmt = $pdo->prepare("
            SELECT s.student_name, s.student_id, sec.section_name as old_section
            FROM enrollment e
            INNER JOIN student s ON e.student_id = s.student_id
            LEFT JOIN section sec ON e.section_id = sec.section_id
            WHERE e.enrollment_id = ?
        ");
        $stmt->execute([$enrollmentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get new section name
        $stmt = $pdo->prepare("SELECT section_name FROM section WHERE section_id = ?");
        $stmt->execute([$sectionId]);
        $newSection = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("
            UPDATE enrollment 
            SET section_id = ? 
            WHERE enrollment_id = ?
        ");
        $stmt->execute([$sectionId, $enrollmentId]);
        
        // Log activity
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (username, role, activity_type, activity_description) 
            VALUES (?, 'admin', 'section_updated', ?)
        ");
        $stmt->execute([
            $_SESSION['admin_username'] ?? 'admin',
            "Admin changed section for {$student['student_name']} ({$student['student_id']}) from {$student['old_section']} to {$newSection['section_name']}"
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Section updated']);
    }
    
    // DELETE - Delete student
    elseif ($action === 'delete') {
        $studentId = $_POST['student_id'];
        
        // Get student name
        $stmt = $pdo->prepare("SELECT student_name FROM student WHERE student_id = ?");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
            exit();
        }
        
        // Delete user account
        $stmt = $pdo->prepare("DELETE FROM user_account WHERE student_id = ?");
        $stmt->execute([$studentId]);
        
        // Delete enrollment records
        $stmt = $pdo->prepare("DELETE FROM enrollment WHERE student_id = ?");
        $stmt->execute([$studentId]);
        
        // Delete grades
        $stmt = $pdo->prepare("DELETE FROM grade WHERE student_id = ?");
        $stmt->execute([$studentId]);
        
        // Delete student
        $stmt = $pdo->prepare("DELETE FROM student WHERE student_id = ?");
        $stmt->execute([$studentId]);
        
        // Log activity
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (username, role, activity_type, activity_description) 
            VALUES (?, 'admin', 'student_deleted', ?)
        ");
        $stmt->execute([
            $_SESSION['admin_username'] ?? 'admin',
            "Admin deleted student: {$student['student_name']} ($studentId)"
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Student deleted']);
    }
    
    // Invalid action
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>