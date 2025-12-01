<?php
require_once 'config.php';

// If already logged in, redirect
if (isset($_SESSION['admin_id'])) {
    header('Location: admin-dashboard.php');
    exit();
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getDBConnection();
        
        $stmt = $pdo->prepare("SELECT * FROM user_account WHERE username = ? AND role = 'admin'");
        $stmt->execute([$_POST['username']]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($_POST['password'], $admin['password'])) {
            // Set session
            $_SESSION['admin_id'] = $admin['user_id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['username'] = $admin['username'];
            
            // Log activity using the correct function
            logActivity($admin['username'], 'admin', 'login', 'Administrator logged in');
            
            header('Location: admin-dashboard.php');
            exit();
        } else {
            $error = 'Invalid username or password';
            
            // Log failed attempt
            if ($admin) {
                logActivity($_POST['username'], 'admin', 'failed_login', 'Failed admin login attempt');
            }
        }
        
    } catch(PDOException $e) {
        $error = 'Login error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="photo/logo.png" type="image/x-icon">
    <title>Admin Login - SIES</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center px-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8">
        
        <div class="text-center mb-8"> 
            <div class="w-20 h-20 bg-white-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <img src="photo/logo.png" alt="">
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Portal</h1>
            <p class="text-gray-600">ST. LUKE CHRISTIAN SCHOOL & LEARNING CENTER</p>
        </div>

        <?php if ($error): ?>
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Username
                </label>
                <input type="text" name="username" required 
                       placeholder="Enter admin username"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Password
                </label>
                <input type="password" name="password" required 
                       placeholder="Enter your password"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition">
            </div>

            <button type="submit" 
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                Login as Admin
            </button>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    <a href="teacher-login.php" class="text-blue-600 font-semibold hover:underline">Teacher Login</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>