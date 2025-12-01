<?php
require_once 'config.php';

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header('Location: teacher-login.php');
    exit();
}

$teacherId = $_SESSION['teacher_id'];
$teacherName = $_SESSION['teacher_name'] ?? 'Teacher';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="photo/logo.png" type="image/x-icon">
  <title>Teacher Dashboard - SIES</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen font-sans bg-gray-50">
  <div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-600 text-white p-6 flex flex-col justify-between shadow-xl">
      <div>
        <!-- Logo -->
        <div class="w-16 h-16 bg-white rounded-full mx-auto mb-2 flex items-center justify-center">
          <img src="photo/logo.png" alt="ST.LUKE LOGO">
        </div>
        
        <!-- Header -->
        <div class="text-center mb-6 pb-4 border-b border-white border-opacity-20">
          <h1 class="text-2xl font-bold">Teacher Panel</h1>
        </div>
        
        <!-- Teacher Info Card -->
        <div class="text-center mb-6 bg-white bg-opacity-10 rounded-lg p-4">
          <p class="text-sm font-medium opacity-90 mb-1">Welcome,</p>
          <p class="text-lg font-bold" id="teacherName"><?php echo htmlspecialchars($teacherName); ?></p>
          <p class="text-xs opacity-75 mt-1"><?php echo htmlspecialchars($teacherId); ?></p>
        </div>
        
        <!-- Navigation Buttons -->
        <nav class="space-y-2">
          <button class="nav-btn w-full text-left px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 flex items-center" 
                  onclick="showSection('myStudents', this)">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span>My Students</span>
          </button>

          <button class="nav-btn w-full text-left px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 flex items-center" 
                  onclick="showSection('archivedStudents', this)">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
            <span>Archive</span>
          </button>

          <button class="nav-btn w-full text-left px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 bg-white bg-opacity-20 font-semibold flex items-center" 
                  onclick="showSection('gradeEncode', this)">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <span>Encode Grades</span>
          </button>
          
          <button class="nav-btn w-full text-left px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 flex items-center" 
                  onclick="showSection('viewGrades', this)">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>View Grades</span>
          </button>
          
          <button class="nav-btn w-full text-left px-4 py-3 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 flex items-center" 
                  onclick="showSection('mySchedule', this)">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>My Schedule</span>
          </button>
        </nav>
      </div>

      <!-- Logout Button -->
      <div class="mt-6">
        <a href="teacher-logout.php" 
           class="block w-full bg-orange-500 hover:bg-orange-600 py-3 rounded-lg text-center font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          Logout
        </a>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8 overflow-y-auto">
      <h2 class="text-4xl font-bold text-blue-700 mb-8">Grade Management</h2>

      <!-- MY STUDENTS SECTION (NEW) -->
      <section id="myStudents" class="section hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
          <div class="border-b-2 border-blue-600 pb-3 mb-6">
            <h3 class="text-2xl font-bold text-blue-700 flex items-center">
              <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
              </svg>
              My Assigned Students
            </h3>
            <p class="text-gray-600 text-sm mt-2">View all active students assigned to you by the admin</p>
          </div>

          <!-- Statistics Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-blue-100 text-sm font-medium mb-1">Total Students</p>
                  <p id="totalStudentsCount" class="text-4xl font-bold">0</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-green-100 text-sm font-medium mb-1">Total Sections</p>
                  <p id="totalSectionsCount" class="text-4xl font-bold">0</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                  </svg>
                </div>
              </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg p-6 shadow-lg">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-purple-100 text-sm font-medium mb-1">Grade Levels</p>
                  <p id="totalGradeLevelsCount" class="text-4xl font-bold">0</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Sections Grid -->
          <div id="sectionsGridContainer">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Assigned Sections</h4>
            <div id="sectionsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <!-- Sections will be loaded here -->
              <div class="col-span-full text-center py-8">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="text-gray-500 mt-3">Loading sections...</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ARCHIVED STUDENTS SECTION (NEW) -->
      <section id="archivedStudents" class="section hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
          <div class="border-b-2 border-gray-600 pb-3 mb-6">
            <h3 class="text-2xl font-bold text-gray-700 flex items-center">
              <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
              </svg>
              Student Archive
            </h3>
            <p class="text-gray-600 text-sm mt-2">View graduated and transferred students that were assigned to you</p>
          </div>

          <!-- Archive Statistics -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-lg p-5 shadow-lg">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-100 text-xs font-medium mb-1">Total Archived</p>
                  <p id="totalArchivedCount" class="text-3xl font-bold">0</p>
                </div>
                <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
              </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg p-5 shadow-lg">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-green-100 text-xs font-medium mb-1">Graduated</p>
                  <p id="graduatedCount" class="text-3xl font-bold">0</p>
                </div>
                <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                  <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                </svg>
              </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg p-5 shadow-lg">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-yellow-100 text-xs font-medium mb-1">Transferred</p>
                  <p id="transferredCount" class="text-3xl font-bold">0</p>
                </div>
                <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
              </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-5 shadow-lg">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-blue-100 text-xs font-medium mb-1">School Years</p>
                  <p id="schoolYearsCount" class="text-3xl font-bold">0</p>
                </div>
                <svg class="w-8 h-8 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Filter Controls -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6 border">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Filter by Status</label>
                <select id="archiveStatusFilter" onchange="loadArchivedStudents()" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 text-sm">
                  <option value="all">All Students</option>
                  <option value="graduated">Graduated</option>
                  <option value="transferred">Transferred</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Filter by School Year</label>
                <select id="archiveYearFilter" onchange="loadArchivedStudents()" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 text-sm">
                  <option value="all">All Years</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Search Student</label>
                <input type="text" id="archiveSearchInput" onkeyup="loadArchivedStudents()" 
                       placeholder="Name or ID..." 
                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 text-sm">
              </div>
              <div class="flex items-end">
                <button onclick="exportArchivedStudentsTeacher()" 
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center justify-center">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                  </svg>
                  Export
                </button>
              </div>
            </div>
          </div>

          <!-- Archived Students Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-left">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-4 py-3 border-b-2 border-gray-300 font-semibold text-gray-700">Student ID</th>
                  <th class="px-4 py-3 border-b-2 border-gray-300 font-semibold text-gray-700">Name</th>
                  <th class="px-4 py-3 border-b-2 border-gray-300 font-semibold text-gray-700">Grade Level</th>
                  <th class="px-4 py-3 border-b-2 border-gray-300 font-semibold text-gray-700">Section</th>
                  <th class="px-4 py-3 border-b-2 border-gray-300 font-semibold text-gray-700">Status</th>
                  <th class="px-4 py-3 border-b-2 border-gray-300 font-semibold text-gray-700">School Year</th>
                  <th class="px-4 py-3 border-b-2 border-gray-300 font-semibold text-gray-700">Archive Date</th>
                  <th class="px-4 py-3 border-b-2 border-gray-300 font-semibold text-gray-700 text-center">Action</th>
                </tr>
              </thead>
              <tbody id="archivedStudentsTable" class="text-gray-700">
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- GRADE ENCODING -->
      <section id="gradeEncode" class="section">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
          <div class="border-b-2 border-blue-600 pb-3 mb-6">
            <h3 class="text-xl font-bold text-blue-700">Grade Encoding</h3>
          </div>

          <!-- FILTERS -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Grade Level</label>
              <select id="gradeFilter" 
                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition-colors">
                <option value="">Select Grade Level</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
              <select id="sectionFilter" 
                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition-colors">
                <option value="">Select Section</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
              <select id="subjectFilter" 
                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition-colors">
                <option value="">Select Subject</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Grading Period</label>
              <select id="periodFilter" 
                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition-colors">
                <option value="1st">1st Quarter</option>
                <option value="2nd">2nd Quarter</option>
                <option value="3rd">3rd Quarter</option>
                <option value="4th">4th Quarter</option>
              </select>
            </div>

            <div class="flex items-end">
              <button onclick="filterStudents()" 
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md">
                Show Students
              </button>
            </div>
          </div>
        </div>

        <!-- STUDENT LIST -->
        <div id="studentTableContainer" class="hidden bg-white rounded-xl shadow-lg p-6">
          <h4 class="text-xl font-bold text-blue-700 mb-4">Student List</h4>
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-left">
              <thead class="bg-blue-100 text-blue-800">
                <tr>
                  <th class="px-4 py-3 border font-semibold">Student ID</th>
                  <th class="px-4 py-3 border font-semibold">Student Name</th>
                  <th class="px-4 py-3 border font-semibold text-center">Current Grade</th>
                  <th class="px-4 py-3 border font-semibold">New Grade</th>
                  <th class="px-4 py-3 border font-semibold text-center">Action</th>
                </tr>
              </thead>
              <tbody id="studentTable" class="text-gray-700"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- VIEW GRADES -->
      <section id="viewGrades" class="section hidden">
        <div class="bg-white rounded-xl shadow-lg p-6">
          <div class="border-b-2 border-blue-600 pb-3 mb-6">
            <h3 class="text-xl font-bold text-blue-700">View Student Grades</h3>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
              <select id="viewSectionFilter" 
                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition-colors">
                <option value="">Select Section</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
              <select id="viewSubjectFilter" 
                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition-colors">
                <option value="">Select Subject</option>
              </select>
            </div>
            <div class="flex items-end">
              <button onclick="viewGradesTable()" 
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md">
                View Grades
              </button>
            </div>
          </div>

          <div id="gradesTableContainer" class="hidden overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-left text-sm">
              <thead class="bg-blue-100 text-blue-800">
                <tr>
                  <th class="px-3 py-3 border font-semibold">Student Name</th>
                  <th class="px-3 py-3 border font-semibold text-center">1st Quarter</th>
                  <th class="px-3 py-3 border font-semibold text-center">2nd Quarter</th>
                  <th class="px-3 py-3 border font-semibold text-center">3rd Quarter</th>
                  <th class="px-3 py-3 border font-semibold text-center">4th Quarter</th>
                  <th class="px-3 py-3 border font-semibold text-center">Average</th>
                </tr>
              </thead>
              <tbody id="gradesTableBody" class="text-gray-700"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- MY SCHEDULE -->
      <section id="mySchedule" class="section hidden">
        <div class="bg-white rounded-xl shadow-lg p-6">
          <div class="border-b-2 border-blue-600 pb-3 mb-6">
            <h3 class="text-xl font-bold text-blue-700">My Teaching Schedule</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-left">
              <thead class="bg-blue-100 text-blue-800">
                <tr>
                  <th class="px-4 py-3 border font-semibold">Subject</th>
                  <th class="px-4 py-3 border font-semibold">Section</th>
                  <th class="px-4 py-3 border font-semibold">Day & Time</th>
                  <th class="px-4 py-3 border font-semibold">Room</th>
                </tr>
              </thead>
              <tbody id="scheduleTableBody" class="text-gray-700">
                <tr>
                  <td colspan="4" class="px-4 py-3 text-center text-gray-400">Loading...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

    </main>
  </div>

  <script>
    // Set teacher ID as global variable
    window.teacherId = '<?php echo $teacherId; ?>';
  </script>
  <script src="js/teacher-dashboard.js"></script>
</body>
</html>