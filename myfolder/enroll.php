<?php
include_once 'config.php';

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proceed with enrollment processing
    try {
        // Use shared `$pdo` from `config.php` (initialized there)
        // `$pdo` is available because `config.php` creates it on include
        
        // Generate student ID
        $stmt = $pdo->query("SELECT student_id FROM student ORDER BY student_id DESC LIMIT 1");
        $lastStudent = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($lastStudent) {
            $lastNumber = intval(substr($lastStudent['student_id'], 5));
            $newNumber = $lastNumber + 1;
            $studentId = '2024-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $studentId = '2024-0001';
        }
        
        // Combine full name
        $fullName = $_POST['first_name'] . ' ';
        if (!empty($_POST['middle_name'])) {
            $fullName .= $_POST['middle_name'] . ' ';
        }
        $fullName .= $_POST['last_name'];

        // Capture, trim and sanitize optional email (do not modify DB schema here)
        $emailRaw = isset($_POST['email']) ? trim($_POST['email']) : '';
        $email = $emailRaw !== '' ? filter_var($emailRaw, FILTER_SANITIZE_EMAIL) : null;

        // Server-side validation: if email provided, ensure it's a valid email format
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address provided. Please enter a valid email.');
        }
        
        // Insert student record into the student table
        $stmt = $pdo->prepare("
            INSERT INTO student 
            (student_id, student_name, grade_level, gender, birthdate, religion, address, 
             contact_number, father_name, father_occupation, mother_name, mother_occupation, 
             guardian_name, guardian_relationship, previous_school, last_school_year) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $studentId,
            $fullName,
            $_POST['grade_level'],
            $_POST['gender'],
            $_POST['birthdate'],
            $_POST['religion'],
            $_POST['address'],
            $_POST['contact_no'],
            $_POST['father_name'],
            $_POST['father_occupation'],
            $_POST['mother_name'],
            $_POST['mother_occupation'],
            !empty($_POST['guardian']) ? $_POST['guardian'] : null,
            !empty($_POST['relationship']) ? $_POST['relationship'] : null,
            $_POST['previous_school'],
            $_POST['last_school_year']]
        );
        
        // Generate random 6-digit password
        $randomPassword = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Hash the password
        $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);
        
        // Create user account
        $stmt = $pdo->prepare("
            INSERT INTO user_account (username, password, role, student_id) 
            VALUES (?, ?, 'student', ?)
        ");
        $stmt->execute([$studentId, $hashedPassword, $studentId]);
        
        // Create enrollment record with status 'pending' (awaiting section assignment)
        $stmt = $pdo->prepare("
            INSERT INTO enrollment (student_id, section_id, enrollment_status, date_enrolled) 
            VALUES (?, NULL, 'pending', CURDATE())
        ");
        $stmt->execute([$studentId]);
        
        // Send confirmation email if provided (use mailer helper)
        $emailStatusNote = '';
        if (!empty($email)) {
            include_once __DIR__ . '/mailer.php';
            $subject = "[St. Luke] Enrollment Confirmation - " . $studentId;
            $schoolName = defined('MAIL_NAME') ? MAIL_NAME : 'St. Luke Enrollment';
            $emailBody = "<html><body>" .
                "<p>Dear Parent / Guardian,</p>" .
                "<p>Thank you for enrolling at <strong>St. Luke Christian School &amp; Learning Center</strong>. Below are the enrollment details:</p>" .
                "<ul>" .
                "<li><strong>Student ID:</strong> " . htmlspecialchars($studentId) . "</li>" .
                "<li><strong>Student Name:</strong> " . htmlspecialchars($fullName) . "</li>" .
                "<li><strong>Grade Level:</strong> " . htmlspecialchars($_POST['grade_level']) . "</li>" .
                "</ul>" .
                "<p>Student portal credentials (save these):</p>" .
                "<p><strong>Username:</strong> " . htmlspecialchars($studentId) . "<br>" .
                "<strong>Password:</strong> " . htmlspecialchars($randomPassword) . "</p>" .
                "<p>Your section will be assigned by the administrator. Please bring required documents to the school office within 3 days.</p>" .
                "<p>Regards,<br>" . htmlspecialchars($schoolName) . "</p>" .
                "</body></html>";

            $result = sendEnrollmentEmail($email, $subject, $emailBody);
            if (!empty($result) && !empty($result['success']) && $result['success'] === true) {
                $emailStatusNote = "<p class='text-sm text-green-700 mt-2'>A confirmation email was sent to " . htmlspecialchars($email) . ".</p>";
            } else {
                $err = isset($result['error']) ? $result['error'] : 'Unknown error';
                $emailStatusNote = "<p class='text-sm text-yellow-800 mt-2'>We could not send an email to " . htmlspecialchars($email) . ". Error: " . htmlspecialchars($err) . ".</p>";
            }
        }

        $message = "Enrollment successful!<br><br>" .
                   "<strong>Student ID: $studentId</strong><br>" .
                   "<strong>Student Name:</strong> $fullName<br>" .
                   "<strong>Grade Level:</strong> " . $_POST['grade_level'] . "<br>" .
                   (!empty($email) ? "<strong>Email:</strong> " . htmlspecialchars($email) . "<br><br>" : "<br>") .
                   "<div class='bg-yellow-50 border-l-4 border-yellow-500 p-4 my-4'>" .
                       "<p class='font-bold text-yellow-800'>Important Login Credentials:</p>" .
                       "<p class='text-yellow-700 mt-2'>" .
                           "<strong>Username:</strong> $studentId<br>" .
                           "<strong>Password:</strong> <span class='text-2xl font-mono bg-yellow-100 px-3 py-1 rounded'>$randomPassword</span>" .
                       "</p>" .
                       "<p class='text-sm text-yellow-600 mt-2'>⚠️ Please save or write down this password. You will need it to login.</p>" .
                   "</div>" .
                   "Your section will be assigned by the administrator.";
        $messageType = 'success';
        
    } catch(PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Form - St. Luke Christian School</title>
    <link rel="shortcut icon" href="photo/logo.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
            .print-optimize {
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4 bg-gray-50">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-xl p-8">
        
        <!-- Header -->
        <div class="text-center border-b-4 border-blue-500 pb-6 mb-8">
            <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center">
                <img src="photo/logo.png" alt="ST.LUKE LOGO">
            </div>
            <h1 class="text-xl font-bold text-gray-800 mb-2">ST. LUKE CHRISTIAN SCHOOL & LEARNING CENTER</h1>
            <h2 class="text-2xl font-bold text-blue-600 uppercase">Enrollment Form (NEW STUDENT)</h2>
        </div>
<div class="mb-8 bg-blue-50 border-2 border-blue-200 rounded-lg overflow-hidden">
    <button type="button" onclick="toggleRequirements()" class="w-full p-6 flex items-center justify-between hover:bg-blue-100 transition-colors">
        <h3 class="text-xl font-bold text-blue-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            What are the enrollment requirements?
        </h3>
        <svg id="requirementsArrow" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-800 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <div id="requirementsContent" class="hidden px-6 pb-6">
    
    <p class="text-gray-700 mb-4">Before filling out the enrollment form, please prepare the following documents and information:</p>
    
    <div class="bg-white rounded-lg p-5 shadow-sm">
        <h4 class="font-semibold text-gray-800 mb-3">Required Documents:</h4>
        <ul class="space-y-2 mb-5">
            <li class="flex items-start gap-2">
                <span class="text-green-600 font-bold mt-1">✓</span>
                <span class="text-gray-700"><strong>Original Birth Certificate</strong> (PSA/NSO)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-green-600 font-bold mt-1">✓</span>
                <span class="text-gray-700"><strong>Report Card/Form 138</strong> (Original or Certified True Copy from previous school)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-green-600 font-bold mt-1">✓</span>
                <span class="text-gray-700"><strong>Certificate of Good Moral Character</strong> from previous school</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-green-600 font-bold mt-1">✓</span>
                <span class="text-gray-700"><strong>Two (2) recent passport-sized photos</strong> (2x2 with white background)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-green-600 font-bold mt-1">✓</span>
                <span class="text-gray-700"><strong>Photocopy of parent/guardian's valid ID</strong></span>
            </li>
        </ul>

        <h4 class="font-semibold text-gray-800 mb-3">For Kindergarten Applicants Only:</h4>
        <ul class="space-y-2 mb-5">
            <li class="flex items-start gap-2">
                <span class="text-blue-600 font-bold mt-1">✓</span>
                <span class="text-gray-700"><strong>Baptismal Certificate</strong> (if applicable)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-blue-600 font-bold mt-1">✓</span>
                <span class="text-gray-700">Applicant must be at least <strong>5 years old</strong> upon enrollment</span>
            </li>
        </ul>

        <h4 class="font-semibold text-gray-800 mb-3">Information You'll Need:</h4>
        <ul class="space-y-2">
            <li class="flex items-start gap-2">
                <span class="text-amber-600 font-bold mt-1">ⓘ</span>
                <span class="text-gray-700">Complete student information (full name, birthdate, address, contact number)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-amber-600 font-bold mt-1">ⓘ</span>
                <span class="text-gray-700">Parents' full names and occupations</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-amber-600 font-bold mt-1">ⓘ</span>
                <span class="text-gray-700">Guardian information (if applicable)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="text-amber-600 font-bold mt-1">ⓘ</span>
                <span class="text-gray-700">Previous school details and last school year attended</span>
            </li>
        </ul>
    </div>

    <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
        <p class="text-sm text-yellow-800">
            <strong>⚠️ Important Note:</strong> Physical submission of required documents must be done at the school office within 3 days after online enrollment submission.
        </p>
    </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">For inquiries, please contact the school office at:</p>
            <p class="font-semibold text-blue-700">📞 Contact Number | 📧 Email Address</p>
        </div>
    </div>
</div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 'bg-red-100 border-l-4 border-red-500 text-red-700'; ?>">
            <?php echo $message; ?>
            <?php if ($messageType === 'success'): ?>
            <div class="mt-4">
                <a href="login.php" class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Go to Student Portal Login
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-8">
            
            <!-- Section A: Admission Seeking In -->
            <div>
                <h3 class="text-lg font-bold text-blue-600 uppercase mb-4 pb-2 border-b-2 border-blue-600">Admission Seeking In</h3>
                <div>
                    <label for="grade_level" class="block text-sm font-semibold text-gray-700 mb-2">Grade Level: <span class="text-red-500">*</span></label>
                    <select id="grade_level" name="grade_level" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        <option value="">-- Select Grade Level --</option>
                        <option value="Kindergarten">Kindergarten</option>
                        <option value="Grade 1">Grade 1</option>
                        <option value="Grade 2">Grade 2</option>
                        <option value="Grade 3">Grade 3</option>
                        <option value="Grade 4">Grade 4</option>
                        <option value="Grade 5">Grade 5</option>
                        <option value="Grade 6">Grade 6</option>
                    </select>
                </div>
            </div>

            <!-- Section B: Student's Personal Information -->
            <div>
                <h3 class="text-lg font-bold text-blue-600 uppercase mb-4 pb-2 border-b-2 border-blue-600">Student's Personal Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name: <span class="text-red-500">*</span></label>
                        <input type="text" id="last_name" name="last_name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name: <span class="text-red-500">*</span></label>
                        <input type="text" id="first_name" name="first_name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-semibold text-gray-700 mb-2">Middle Name: </label>
                        <input type="text" id="middle_name" name="middle_name" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gender: <span class="text-red-500">*</span></label>
                    <div class="flex gap-6">
                        <div class="flex items-center gap-2">
                            <input type="radio" id="male" name="gender" value="Male" required class="w-4 h-4 cursor-pointer">
                            <label for="male" class="text-sm font-normal cursor-pointer">Male</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="radio" id="female" name="gender" value="Female" required class="w-4 h-4 cursor-pointer">
                            <label for="female" class="text-sm font-normal cursor-pointer">Female</label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="birthdate" class="block text-sm font-semibold text-gray-700 mb-2">Birthdate: <span class="text-red-500">*</span></label>
                        <input type="date" 
                               id="birthdate" 
                               name="birthdate" 
                               required 
                               max="<?php echo date('Y-m-d'); ?>"
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        <p id="birthdateError" class="text-xs text-red-500 mt-1 hidden">Birthdate cannot be in the future.</p>
                    </div>
                    <div>
                        <label for="age" class="block text-sm font-semibold text-gray-700 mb-2">Age:</label>
                        <input type="text" id="age" name="age" readonly class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="religion" class="block text-sm font-semibold text-gray-700 mb-2">Religion: <span class="text-red-500">*</span></label>
                    <select id="religion" name="religion" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        <option value="">-- Select Religion --</option>
                        <option value="Roman Catholic">Roman Catholic</option>
                        <option value="Protestant">Protestant</option>
                        <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                        <option value="Born Again Christian">Born Again Christian</option>
                        <option value="Seventh-day Adventist">Seventh-day Adventist</option>
                        <option value="Jehovah's Witness">Jehovah's Witness</option>
                        <option value="Islam">Islam</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Hinduism">Hinduism</option>
                        <option value="Other">Other</option>
                        <option value="Prefer not to say">Prefer not to say</option>
                    </select>
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address: <span class="text-red-500">*</span></label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label for="region" class="block text-xs font-medium text-gray-600 mb-1">Region <span class="text-red-500">*</span></label>
                            <select id="region" name="region" required class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                                <option value="">-- Select Region --</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="province" class="block text-xs font-medium text-gray-600 mb-1">Province <span class="text-red-500">*</span></label>
                            <select id="province" name="province" required disabled class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition bg-gray-100">
                                <option value="">-- Select Region First --</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label for="city" class="block text-xs font-medium text-gray-600 mb-1">City/Municipality <span class="text-red-500">*</span></label>
                            <select id="city" name="city" required disabled class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition bg-gray-100">
                                <option value="">-- Select Province First --</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="barangay" class="block text-xs font-medium text-gray-600 mb-1">Barangay <span class="text-red-500">*</span></label>
                            <select id="barangay" name="barangay" required disabled class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition bg-gray-100">
                                <option value="">-- Select City First --</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label for="zipcode" class="block text-xs font-medium text-gray-600 mb-1">Zip Code</label>
                            <input type="text" 
                                   id="zipcode" 
                                   name="zipcode" 
                                   readonly 
                                   placeholder="Auto-filled based on city"
                                   class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                        </div>
                        
                        <div>
                            <label for="street" class="block text-xs font-medium text-gray-600 mb-1">Street/House No. (Optional)</label>
                            <input type="text" 
                                   id="street" 
                                   name="street" 
                                   placeholder="e.g., Block 1 Lot 2, St. Mary Street" 
                                   class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        </div>
                    </div>
                    
                    <input type="hidden" id="address" name="address">
                </div>
            </div>

            <!-- Section C: Family and Contact Information -->
            <div>
                <h3 class="text-lg font-bold text-blue-600 uppercase mb-4 pb-2 border-b-2 border-blue-600">Family and Contact Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="father_name" class="block text-sm font-semibold text-gray-700 mb-2">Father's Name: <span class="text-red-500">*</span></label>
                        <input type="text" id="father_name" name="father_name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label for="father_occupation" class="block text-sm font-semibold text-gray-700 mb-2">Occupation: <span class="text-red-500">*</span></label>
                        <input type="text" id="father_occupation" name="father_occupation" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="mother_name" class="block text-sm font-semibold text-gray-700 mb-2">Mother's Name: <span class="text-red-500">*</span></label>
                        <input type="text" id="mother_name" name="mother_name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label for="mother_occupation" class="block text-sm font-semibold text-gray-700 mb-2">Occupation: <span class="text-red-500">*</span></label>
                        <input type="text" id="mother_occupation" name="mother_occupation" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="guardian" class="block text-sm font-semibold text-gray-700 mb-2">Guardian (if not parent):</label>
                        <input type="text" id="guardian" name="guardian" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label for="relationship" class="block text-sm font-semibold text-gray-700 mb-2">Relationship:</label>
                        <input type="text" id="relationship" name="relationship" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address:</label>
                    <input type="email" id="email" name="email" placeholder="example@domain.com" maxlength="254" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address" aria-describedby="emailError" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    <p id="emailError" class="text-xs text-red-500 mt-1 hidden">Please enter a valid email address (example@domain.com).</p>
                    <p class="text-xs text-gray-500 mt-1">we'll send important enrollment info here.</p>
                </div>

                <div>
                    <label for="contact_no" class="block text-sm font-semibold text-gray-700 mb-2">Contact No/s: <span class="text-red-500">*</span></label>
                    <input type="text" 
                           id="contact_no" 
                           name="contact_no" 
                           required 
                           placeholder="09XXXXXXXXX" 
                           maxlength="11"
                           pattern="\d*"
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    <p id="contactError" class="text-xs text-red-500 mt-1 hidden">Contact number must contain only digits.</p>
                    <p class="text-xs text-gray-500 mt-1">Format: 09XXXXXXXXX (11 digits starting with 09)</p>
                </div>
            </div>

            <!-- Section D: Previous School Details -->
            <div>
                <h3 class="text-lg font-bold text-blue-600 uppercase mb-4 pb-2 border-b-2 border-blue-600">Previous School Details</h3>
                
                <div class="mb-4">
                    <label for="previous_school" class="block text-sm font-semibold text-gray-700 mb-2">Previous School Attended: <span class="text-red-500">*</span></label>
                    <input type="text" id="previous_school" name="previous_school" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                </div>

                <div>
                    <label for="last_school_year" class="block text-sm font-semibold text-gray-700 mb-2">Last School Year Attended: <span class="text-red-500">*</span></label>
                    <select id="last_school_year" name="last_school_year" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        <option value="">-- Select School Year --</option>
                    </select>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4 no-print">
                <button type="reset" class="px-8 py-3 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-700 transition-all transform hover:scale-105">Reset Form</button>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">Submit Form</button>
            </div>
        </form>

        <div class="mt-8 text-center no-print">
            <p class="text-gray-600">Already enrolled? 
                <a href="login.php" class="text-blue-600 font-semibold hover:underline">Login to Student Portal</a>
            </p>
        </div>
    </div>

    <script src="js/enroll.js"></script>
    <script>
        // Client-side email validation UX: trims input, shows error message, blocks submit if invalid
        document.addEventListener('DOMContentLoaded', function() {
            var emailInput = document.getElementById('email');
            var emailError = document.getElementById('emailError');
            var form = document.querySelector('form');

            if (!emailInput || !form) return;

            emailInput.addEventListener('input', function() {
                // Trim whitespace as user types
                var val = this.value.replace(/\s+/g, '');
                if (this.value !== val) this.value = val;

                if (this.value === '') {
                    emailError.classList.add('hidden');
                    return;
                }

                if (this.checkValidity()) {
                    emailError.classList.add('hidden');
                } else {
                    emailError.classList.remove('hidden');
                }
            });

            form.addEventListener('submit', function(e) {
                if (emailInput.value && !emailInput.checkValidity()) {
                    e.preventDefault();
                    emailError.classList.remove('hidden');
                    emailInput.focus();
                }
            });
        });
    </script>
</body>
</html>