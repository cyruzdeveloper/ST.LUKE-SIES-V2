<?php
require_once 'config.php';

// This file handles enrollment form submissions
// Include this in your enrollment form processing

function handleEnrollmentSubmission($studentData) {
    try {
        $pdo = getDBConnection();
        
        // Generate student ID
        $stmt = $pdo->query("SELECT student_id FROM student ORDER BY student_id DESC LIMIT 1");
        $lastStudent = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($lastStudent) {
            $lastNum = (int)substr($lastStudent['student_id'], 5);
            $newNum = $lastNum + 1;
        } else {
            $newNum = 1;
        }
        $studentId = date('Y') . '-' . str_pad($newNum, 4, '0', STR_PAD_LEFT);
        
        // Insert student record
        $stmt = $pdo->prepare("
            INSERT INTO student (
                student_id, student_name, grade_level, gender, birthdate, 
                religion, address, contact_number, father_name, father_occupation,
                mother_name, mother_occupation, guardian_name, guardian_relationship,
                previous_school, last_school_year
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $studentId,
            $studentData['student_name'],
            $studentData['grade_level'],
            $studentData['gender'],
            $studentData['birthdate'],
            $studentData['religion'],
            $studentData['address'],
            $studentData['contact_number'],
            $studentData['father_name'],
            $studentData['father_occupation'],
            $studentData['mother_name'],
            $studentData['mother_occupation'],
            $studentData['guardian_name'] ?? null,
            $studentData['guardian_relationship'] ?? null,
            $studentData['previous_school'],
            $studentData['last_school_year']
        ]);
        
        // Create default password (student_id)
        $defaultPassword = password_hash($studentId, PASSWORD_DEFAULT);
        
        // Create user account
        $stmt = $pdo->prepare("
            INSERT INTO user_account (username, password, role, student_id)
            VALUES (?, ?, 'student', ?)
        ");
        $stmt->execute([$studentId, $defaultPassword, $studentId]);
        
        // Create pending enrollment
        $stmt = $pdo->prepare("
            INSERT INTO enrollment (student_id, enrollment_status, date_enrolled)
            VALUES (?, 'pending', CURDATE())
        ");
        $stmt->execute([$studentId]);
        
        // Log the enrollment submission activity
        logActivity(
            $studentData['student_name'], 
            'student', 
            'enrollment_submitted', 
            "Enrollment submitted for " . $studentData['student_name'] . " - Grade " . $studentData['grade_level']
        );
        
        return [
            'success' => true, 
            'student_id' => $studentId,
            'message' => 'Enrollment submitted successfully! Your Student ID is: ' . $studentId
        ];
        
    } catch (PDOException $e) {
        error_log("Enrollment error: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Failed to process enrollment. Please try again.'
        ];
    }
}

// Example usage in your enrollment form:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_enrollment'])) {
    $studentData = [
        'student_name' => $_POST['student_name'],
        'grade_level' => $_POST['grade_level'],
        'gender' => $_POST['gender'],
        'birthdate' => $_POST['birthdate'],
        'religion' => $_POST['religion'],
        'address' => $_POST['address'],
        'contact_number' => $_POST['contact_number'],
        'father_name' => $_POST['father_name'],
        'father_occupation' => $_POST['father_occupation'],
        'mother_name' => $_POST['mother_name'],
        'mother_occupation' => $_POST['mother_occupation'],
        'guardian_name' => $_POST['guardian_name'] ?? null,
        'guardian_relationship' => $_POST['guardian_relationship'] ?? null,
        'previous_school' => $_POST['previous_school'],
        'last_school_year' => $_POST['last_school_year']
    ];
    
    $result = handleEnrollmentSubmission($studentData);
    
    if ($result['success']) {
        // Redirect to success page or show success message
        $_SESSION['enrollment_message'] = $result['message'];
        header('Location: enrollment-success.php');
        exit();
    } else {
        // Show error message
        $error = $result['message'];
    }
}
?>