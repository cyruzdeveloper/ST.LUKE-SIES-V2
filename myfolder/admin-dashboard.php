<?php
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Dashboard - SIES</title>
  <link rel="shortcut icon" href="photo/logo.png" type="image/x-icon">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/ea9fdbb77c.js" crossorigin="anonymous"></script>
</head>

<body class="min-h-screen font-sans bg-gray-50">
  <div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar-expanded bg-blue-600 text-white flex flex-col fixed h-screen overflow-y-auto transition-all duration-300">
      <div class="py-8 px-6">
        <div class="w-20 h-20 bg-white rounded-full mx-auto flex items-center justify-center shadow-lg">
          <img src="photo/logo.png" alt="ST.LUKE LOGO">
        </div>
      </div>
      <div class="pb-8 px-6 text-center">
        <h1 class="text-lg font-bold sidebar-text">Admin Panel</h1>
      </div>
      <hr class="border-0 h-px bg-gray-300 w-2/3 mx-auto my-6" />

      <nav class="flex-1 px-3 space-y-2">
        <button id="dashboardBtn" onclick="showSection('dashboard')" class="nav-btn active w-full text-left px-4 py-4 rounded-lg flex items-center gap-4 transition-all" title="Dashboard">
          <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span class="sidebar-text font-medium text-sm">Dashboard</span>
        </button>

        <!-- MANAGE ACCOUNTS -->
        <button id="accountsToggle" class="w-full text-left px-4 py-4 rounded-lg hover:bg-blue-500 flex items-center justify-between transition-all" title="Manage Accounts">
          <span class="flex items-center gap-4">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="sidebar-text font-medium text-sm">Manage Accounts</span>
          </span>
          <span id="accountsArrow" class="sidebar-text">▾</span>
        </button>
        <div id="accountsSub" class="collapsible max-h-0 pl-6 overflow-hidden transition-all duration-300">
          <button id="teacherAccountsBtn" onclick="showSection('teacherAcc')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-blue-500 flex items-center gap-3 transition-all" title="Teacher Accounts">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="sidebar-text text-xs">Teacher Accounts</span>
          </button>
          <button id="studentAccountsBtn" onclick="showSection('studentAcc')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-blue-500 flex items-center gap-3 transition-all" title="Student Accounts">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="sidebar-text text-xs">Student Accounts</span>
          </button>
        </div>

        <!-- SCHEDULES -->
        <button id="schedulesToggle" class="w-full text-left px-4 py-4 rounded-lg hover:bg-blue-500 flex items-center justify-between transition-all" title="Schedules">
          <span class="flex items-center gap-4">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="sidebar-text font-medium text-sm">Schedules</span>
          </span>
          <span id="schedulesArrow" class="sidebar-text">▾</span>
        </button>
        <div id="schedulesSub" class="collapsible max-h-0 pl-6 overflow-hidden transition-all duration-300">
          <button id="teacherSchedBtn" onclick="showSection('teacherSched')" class="nav-btn w-full text-left px-4 py-3 rounded-lg hover:bg-blue-500 flex items-center gap-3 transition-all" title="Manage Schedules">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="sidebar-text text-xs">Manage Schedules</span>
          </button>
        </div>

        <!-- ENROLLMENTS -->
        <button id="enrollmentsBtn" onclick="showSection('enrollments')" class="nav-btn w-full text-left px-4 py-4 rounded-lg hover:bg-blue-500 flex items-center gap-4 transition-all" title="Enrollments">
          <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <span class="sidebar-text font-medium text-sm">Enrollments</span>
        </button>
      </nav>

      <div class="sticky bottom-0 bg-blue-600 p-4 mt-auto">
        <a href="admin-logout.php" class="logout-btn block w-full bg-orange-500 hover:bg-orange-600 py-4 rounded-lg text-center flex items-center justify-center gap-4 transition-all font-semibold shadow-lg" title="Logout">
          <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span class="sidebar-text text-sm">Logout</span>
        </a>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8 overflow-y-auto ml-64">
      <h2 class="text-3xl font-bold text-blue-800 mb-6">
        <i class="fa-solid fa-user-shield"></i> Welcome, Admin
      </h2>

      <!-- DASHBOARD SECTION -->
      <section id="dashboardSection">
        <h3 class="text-xl font-semibold text-blue-700 border-b-2 border-orange-500 pb-1 mb-4">Dashboard Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white border-l-4 border-blue-500 shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <p class="text-sm text-gray-500 uppercase">Total Teachers</p>
            <p id="totalTeachers" class="text-3xl font-bold text-blue-700">-</p>
            <p class="text-xs text-gray-400 mt-1">Registered teachers</p>
          </div>
          <div class="bg-white border-l-4 border-orange-500 shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <p class="text-sm text-gray-500 uppercase">Total Students</p>
            <p id="totalStudents" class="text-3xl font-bold text-orange-600">-</p>
            <p class="text-xs text-gray-400 mt-1">Registered students</p>
          </div>
          <div class="bg-white border-l-4 border-green-500 shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <p class="text-sm text-gray-500 uppercase">Active Schedules</p>
            <p id="totalSchedules" class="text-3xl font-bold text-green-600">-</p>
            <p class="text-xs text-gray-400 mt-1">Current schedules</p>
          </div>
          <div class="bg-white border-l-4 border-red-500 shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
            <p class="text-sm text-gray-500 uppercase">Pending Enrollments</p>
            <p id="pendingEnrollments" class="text-3xl font-bold text-red-600">-</p>
            <p class="text-xs text-gray-400 mt-1">Awaiting approval</p>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div class="bg-white shadow rounded-lg p-6">
            <h4 class="text-lg font-semibold mb-3 text-blue-700 flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Active Teacher Accounts
            </h4>
            <div class="space-y-2">
              <div class="flex justify-between items-center">
                <span class="text-gray-600">Total Accounts:</span>
                <span id="activeTeachers" class="font-bold text-blue-600 text-xl">-</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-600">With Login Access:</span>
                <span id="teachersWithAccounts" class="font-bold text-green-600 text-xl">-</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-600">Without Login:</span>
                <span id="teachersWithoutAccounts" class="font-bold text-red-600 text-xl">-</span>
              </div>
            </div>
          </div>
          <div class="bg-white shadow rounded-lg p-6">
            <h4 class="text-lg font-semibold mb-3 text-orange-700 flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Active Student Accounts
            </h4>
            <div class="space-y-2">
              <div class="flex justify-between items-center">
                <span class="text-gray-600">Total Students:</span>
                <span id="activeStudents" class="font-bold text-orange-600 text-xl">-</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-600">Enrolled:</span>
                <span id="enrolledStudents" class="font-bold text-green-600 text-xl">-</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-600">Pending Enrollment:</span>
                <span id="pendingStudents" class="font-bold text-yellow-600 text-xl">-</span>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
          <h4 class="text-lg font-semibold mb-3 text-blue-700 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Recent Activity Logs
          </h4>
          <div class="max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <ul id="activityLogs" class="divide-y divide-gray-200 text-sm text-gray-700 h-40">
              <li class="py-2 text-gray-400">Loading...</li>
            </ul>
          </div>
        </div>
      </section>

      <!-- TEACHER ACCOUNTS -->
      <section id="teacherAccSection" class="hidden">
        <h3 class="text-xl font-semibold text-blue-700 border-b-2 border-orange-500 pb-1 mb-4">Teacher Account Management</h3>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h4 class="font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Teacher
          </h4>
          <form id="addTeacherForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block mb-1 text-sm font-medium text-gray-700">Teacher Name *</label>
              <input name="teacher_name" class="border border-gray-300 rounded p-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Maria Santos" required>
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium text-gray-700">Username *</label>
              <input name="username" class="border border-gray-300 rounded p-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. msantos" required>
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium text-gray-700">Password *</label>
              <input name="password" type="password" class="border border-gray-300 rounded p-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••" required>
            </div>
            <div class="flex items-end">
              <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded w-full font-semibold transition-colors">
                <span class="flex items-center justify-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Add Teacher
                </span>
              </button>
            </div>
          </form>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h4 class="text-lg font-semibold mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            All Teacher Accounts
          </h4>
          <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
              <thead class="bg-gradient-to-r from-blue-100 to-blue-50">
                <tr>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Teacher ID</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Name</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Username</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Status</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800 text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="teacherAccountsList">
                <tr><td colspan="5" class="px-4 py-3 text-center text-gray-400">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- STUDENT ACCOUNTS -->
      <section id="studentAccSection" class="hidden">
        <h3 class="text-xl font-semibold text-blue-700 border-b-2 border-orange-500 pb-1 mb-4">Student Account Management</h3>
        <div class="bg-white rounded-lg shadow p-6">
          <h4 class="text-lg font-semibold mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            All Student Accounts
          </h4>
          <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
              <thead class="bg-gradient-to-r from-blue-100 to-blue-50">
                <tr>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Student ID</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Name</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Grade Level</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Section</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800">Status</th>
                  <th class="px-4 py-3 border-b-2 border-blue-200 font-semibold text-blue-800 text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="studentAccountsList">
                <tr><td colspan="6" class="px-4 py-3 text-center text-gray-400">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- SCHEDULES -->
      <section id="teacherSchedSection" class="hidden">
        <h3 class="text-xl font-semibold text-blue-700 border-b-2 border-orange-500 pb-1 mb-4">Manage Schedules</h3>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h4 class="font-semibold mb-4">Add New Schedule</h4>
          <form id="addScheduleForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block mb-1 text-sm font-medium">Teacher *</label>
              <select name="teacher_id" id="scheduleTeacher" class="border rounded p-2 w-full" required>
                <option value="">Select Teacher</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium">Subject *</label>
              <select name="subject_code" id="scheduleSubject" class="border rounded p-2 w-full" required>
                <option value="">Select Subject</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium">Section *</label>
              <select name="section_id" id="scheduleSection" class="border rounded p-2 w-full" required>
                <option value="">Select Section</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium">Day *</label>
              <select name="day" id="scheduleDay" class="border rounded p-2 w-full" required>
                <option value="">Select Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium">Time *</label>
              <input type="time" name="time" id="scheduleTime" class="border rounded p-2 w-full" required>
            </div>
            <div>
              <label class="block mb-1 text-sm font-medium">Room Number *</label>
              <input type="number" name="room_number" class="border rounded p-2 w-full" placeholder="101" required>
            </div>
            <div class="flex items-end">
              <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded w-full">Add Schedule</button>
            </div>
          </form>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h4 class="text-lg font-semibold mb-3">Existing Schedules</h4>
          <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
              <thead class="bg-blue-100">
                <tr>
                  <th class="px-3 py-2">Teacher</th>
                  <th class="px-3 py-2">Subject</th>
                  <th class="px-3 py-2">Section</th>
                  <th class="px-3 py-2">Day & Time</th>
                  <th class="px-3 py-2">Room</th>
                  <th class="px-3 py-2 text-center">Action</th>
                </tr>
              </thead>
              <tbody id="schedulesList">
                <tr><td colspan="6" class="px-3 py-2 text-center text-gray-400">Loading schedules...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ENROLLMENTS SECTION (UPDATED WITH TABS) -->
      <section id="enrollmentsSection" class="hidden">
        <h3 class="text-xl font-semibold text-blue-700 border-b-2 border-orange-500 pb-1 mb-4">Enrollment Management</h3>
        
        <!-- Tab Navigation -->
        <div class="mb-6">
          <div class="border-b border-gray-200">
            <nav class="flex space-x-4" aria-label="Tabs">
              <button id="pendingTab" onclick="showEnrollmentTab('pending')" class="enrollment-tab active-tab px-4 py-2 font-medium text-sm rounded-t-lg border-b-2 border-yellow-500 text-yellow-600 bg-yellow-50">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pending Enrollments
                <span id="pendingBadge" class="ml-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">0</span>
              </button>
              <button id="enrolledTab" onclick="showEnrollmentTab('enrolled')" class="enrollment-tab px-4 py-2 font-medium text-sm rounded-t-lg border-b-2 border-transparent text-gray-500 hover:text-green-600 hover:border-green-300">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Enrolled Students
                <span id="enrolledBadge" class="ml-2 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">0</span>
              </button>
            </nav>
          </div>
        </div>

        <!-- Pending Enrollments Tab Content -->
        <div id="pendingEnrollmentsTab" class="enrollment-tab-content">
          <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold mb-3 flex items-center text-yellow-700">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Pending Enrollments
            </h4>
            <div class="overflow-x-auto">
              <table class="min-w-full text-left">
                <thead class="bg-yellow-100">
                  <tr>
                    <th class="px-4 py-3 border-b-2 border-yellow-200">Student ID</th>
                    <th class="px-4 py-3 border-b-2 border-yellow-200">Student Name</th>
                    <th class="px-4 py-3 border-b-2 border-yellow-200">Grade Level</th>
                    <th class="px-4 py-3 border-b-2 border-yellow-200">Date Applied</th>
                    <th class="px-4 py-3 border-b-2 border-yellow-200">Assign Section</th>
                    <th class="px-4 py-3 border-b-2 border-yellow-200 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody id="pendingEnrollmentsList">
                  <tr><td colspan="6" class="px-4 py-3 text-center text-gray-400">Loading...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Enrolled Students Tab Content -->
        <div id="enrolledStudentsTab" class="enrollment-tab-content hidden">
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
              <h4 class="text-lg font-semibold flex items-center text-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Enrolled Students
              </h4>
              
              <!-- Filter Controls -->
              <div class="flex flex-wrap gap-3">
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Filter by Grade</label>
                  <select id="filterGradeLevel" onchange="filterEnrolledStudents()" class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-green-500">
                    <option value="">All Grades</option>
                    <option value="Kindergarten">Kindergarten</option>
                    <option value="Grade 1">Grade 1</option>
                    <option value="Grade 2">Grade 2</option>
                    <option value="Grade 3">Grade 3</option>
                    <option value="Grade 4">Grade 4</option>
                    <option value="Grade 5">Grade 5</option>
                    <option value="Grade 6">Grade 6</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Filter by Section</label>
                  <select id="filterSection" onchange="filterEnrolledStudents()" class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-green-500">
                    <option value="">All Sections</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs text-gray-500 mb-1">Search Student</label>
                  <input type="text" id="searchEnrolled" onkeyup="filterEnrolledStudents()" placeholder="Name or ID..." class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-green-500 w-40">
                </div>
                <div class="flex items-end">
                  <button onclick="exportEnrolledStudents()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                  </button>
                </div>
              </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
              <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                <p class="text-xs text-green-600 uppercase">Total Enrolled</p>
                <p id="totalEnrolledCount" class="text-2xl font-bold text-green-700">0</p>
              </div>
              <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                <p class="text-xs text-blue-600 uppercase">This Month</p>
                <p id="enrolledThisMonth" class="text-2xl font-bold text-blue-700">0</p>
              </div>
              <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 text-center">
                <p class="text-xs text-purple-600 uppercase">Sections</p>
                <p id="totalSections" class="text-2xl font-bold text-purple-700">0</p>
              </div>
              <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-center">
                <p class="text-xs text-orange-600 uppercase">Grade Levels</p>
                <p id="totalGradeLevels" class="text-2xl font-bold text-orange-700">0</p>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full text-left border-collapse">
                <thead class="bg-gradient-to-r from-green-100 to-green-50">
                  <tr>
                    <th class="px-4 py-3 border-b-2 border-green-200 font-semibold text-green-800">Student ID</th>
                    <th class="px-4 py-3 border-b-2 border-green-200 font-semibold text-green-800">Student Name</th>
                    <th class="px-4 py-3 border-b-2 border-green-200 font-semibold text-green-800">Grade Level</th>
                    <th class="px-4 py-3 border-b-2 border-green-200 font-semibold text-green-800">Section</th>
                    <th class="px-4 py-3 border-b-2 border-green-200 font-semibold text-green-800">Date Enrolled</th>
                    <th class="px-4 py-3 border-b-2 border-green-200 font-semibold text-green-800">School Year</th>
                    <th class="px-4 py-3 border-b-2 border-green-200 font-semibold text-green-800 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody id="enrolledStudentsList">
                  <tr><td colspan="7" class="px-4 py-3 text-center text-gray-400">Loading enrolled students...</td></tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4 pt-4 border-t">
              <p id="enrolledPaginationInfo" class="text-sm text-gray-500">Showing 0 of 0 students</p>
              <div class="flex gap-2">
                <button onclick="prevEnrolledPage()" id="prevEnrolledBtn" class="px-3 py-1 border rounded text-sm hover:bg-gray-100 disabled:opacity-50" disabled>Previous</button>
                <span id="enrolledPageNumbers" class="flex gap-1"></span>
                <button onclick="nextEnrolledPage()" id="nextEnrolledBtn" class="px-3 py-1 border rounded text-sm hover:bg-gray-100 disabled:opacity-50" disabled>Next</button>
              </div>
            </div>
          </div>
        </div>
      </section>

    </main>
  </div>

  <!-- View Student Modal -->
  <div id="viewStudentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
      <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-t-lg">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Student Details
          </h3>
          <button onclick="closeViewStudentModal()" class="text-white hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
      <div class="p-6">
        <div id="studentDetailsContent" class="space-y-4">
          <!-- Student details will be loaded here -->
        </div>
      </div>
      <div class="bg-gray-50 px-6 py-3 rounded-b-lg flex justify-end">
        <button onclick="closeViewStudentModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Close</button>
      </div>
    </div>
  </div>

  <script src="js/admin-dashboard.js"></script>

  <script>
    function showEnrollmentTab(tab) {
  console.log('Switching to tab:', tab);
  
  // Hide all tab contents
  document.querySelectorAll('.enrollment-tab-content').forEach(el => {
    el.classList.add('hidden');
  });
  
  // Remove active styling from all tabs
  document.querySelectorAll('.enrollment-tab').forEach(el => {
    el.classList.remove('active-tab', 'border-yellow-500', 'text-yellow-600', 'bg-yellow-50', 'border-green-500', 'text-green-600', 'bg-green-50');
    el.classList.add('border-transparent', 'text-gray-500');
  });
  
  // Show selected tab content and style active tab
  if (tab === 'pending') {
    document.getElementById('pendingEnrollmentsTab').classList.remove('hidden');
    document.getElementById('pendingTab').classList.add('active-tab', 'border-yellow-500', 'text-yellow-600', 'bg-yellow-50');
    document.getElementById('pendingTab').classList.remove('border-transparent', 'text-gray-500');
    loadPendingEnrollments();
  } else if (tab === 'enrolled') {
    document.getElementById('enrolledStudentsTab').classList.remove('hidden');
    document.getElementById('enrolledTab').classList.add('active-tab', 'border-green-500', 'text-green-600', 'bg-green-50');
    document.getElementById('enrolledTab').classList.remove('border-transparent', 'text-gray-500');
    loadEnrolledStudents();
  }
}

// Global variables for enrollment management
let allSections = [];
let enrolledStudentsData = [];
let filteredEnrolledStudents = [];
let currentEnrolledPage = 1;
const enrolledPerPage = 10;

// Load pending enrollments
function loadPendingEnrollments() {
  console.log('Loading pending enrollments...');
  fetch('api/admin-api.php?action=getPendingEnrollments')
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        console.log('Pending enrollments loaded:', data.enrollments.length);
        
        // Store sections globally
        allSections = data.sections || [];
        
        // Update badge count
        document.getElementById('pendingBadge').textContent = data.enrollments.length;
        
        // Render the table
        renderPendingEnrollments(data.enrollments);
      } else {
        console.error('Failed to load pending enrollments:', data.message);
        document.getElementById('pendingEnrollmentsList').innerHTML = 
          '<tr><td colspan="6" class="px-4 py-3 text-center text-red-500">Error: ' + data.message + '</td></tr>';
      }
    })
    .catch(err => {
      console.error('Error loading pending enrollments:', err);
      document.getElementById('pendingEnrollmentsList').innerHTML = 
        '<tr><td colspan="6" class="px-4 py-3 text-center text-red-500">Error loading data</td></tr>';
    });
}

// Render pending enrollments table
function renderPendingEnrollments(enrollments) {
  const tbody = document.getElementById('pendingEnrollmentsList');
  
  if (enrollments.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="6" class="px-4 py-12 text-center">
          <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <p class="text-gray-400 font-medium">No pending enrollments</p>
          <p class="text-gray-400 text-sm mt-1">All enrollments have been processed</p>
        </td>
      </tr>
    `;
    return;
  }
  
  tbody.innerHTML = enrollments.map(enrollment => {
    // Filter sections by grade level
    const sectionsForGrade = allSections.filter(s => s.grade_level === enrollment.grade_level);
    
    const sectionOptions = sectionsForGrade.map(s => 
      `<option value="${s.section_id}">${s.section_name}</option>`
    ).join('');
    
    return `
      <tr class="hover:bg-yellow-50 border-b transition-colors">
        <td class="px-4 py-3 font-medium text-gray-900">${enrollment.student_id}</td>
        <td class="px-4 py-3 text-gray-700">${enrollment.student_name}</td>
        <td class="px-4 py-3">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            ${enrollment.grade_level}
          </span>
        </td>
        <td class="px-4 py-3 text-sm text-gray-600">${new Date(enrollment.date_enrolled).toLocaleDateString()}</td>
        <td class="px-4 py-3">
          <select id="section_${enrollment.student_id}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
            <option value="">Select Section</option>
            ${sectionOptions}
          </select>
        </td>
        <td class="px-4 py-3 text-center">
          <div class="flex items-center justify-center gap-2">
            <button onclick="viewStudentPending('${enrollment.student_id}')" 
                    class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors" 
                    title="View Details">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </button>
            <button onclick="approveEnrollment('${enrollment.student_id}', '${enrollment.enrollment_id}')" 
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
              <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Approve
            </button>
            <button onclick="rejectEnrollment('${enrollment.enrollment_id}')" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
              <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
              Reject
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

// View student details for pending enrollment
function viewStudentPending(studentId) {
  fetch(`api/admin-api.php?action=getStudentDetails&student_id=${encodeURIComponent(studentId)}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const s = data.student;
        // Use the modern modal if available, otherwise create a basic one
        if (typeof showModernModal === 'function' && typeof createStudentDetailsHTML === 'function') {
          showModernModal('Student Details', createStudentDetailsHTML(s, 'pending'));
        } else {
          // Fallback basic modal
          alert('Student: ' + s.student_name + '\nID: ' + s.student_id + '\nGrade: ' + s.grade_level);
        }
      } else {
        alert('❌ Error loading student details: ' + data.message);
      }
    })
    .catch(err => {
      console.error('Error:', err);
      alert('❌ Failed to load student details');
    });
}

// Approve enrollment
function approveEnrollment(studentId, enrollmentId) {
  const sectionSelect = document.getElementById('section_' + studentId);
  const sectionId = sectionSelect.value;
  
  if (!sectionId) {
    alert('⚠️ Please select a section first');
    return;
  }
  
  fetch('api/admin-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=approveEnrollment&enrollment_id=${enrollmentId}&section_id=${sectionId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('✅ Enrollment approved successfully!');
      loadPendingEnrollments();
      loadEnrolledStudents();
      if (typeof loadDashboardStats === 'function') {
        loadDashboardStats();
      }
    } else {
      alert('❌ Error: ' + data.message);
    }
  })
  .catch(err => {
    console.error('Error:', err);
    alert('❌ Failed to approve enrollment');
  });
}

// Reject enrollment
function rejectEnrollment(enrollmentId) {
  if (!confirm('⚠️ Are you sure you want to reject this enrollment?')) return;
  
  fetch('api/admin-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=rejectEnrollment&enrollment_id=${enrollmentId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('✅ Enrollment rejected');
      loadPendingEnrollments();
      if (typeof loadDashboardStats === 'function') {
        loadDashboardStats();
      }
    } else {
      alert('❌ Error: ' + data.message);
    }
  })
  .catch(err => {
    console.error('Error:', err);
    alert('❌ Failed to reject enrollment');
  });
}

// Load enrolled students
function loadEnrolledStudents() {
  fetch('api/admin-api.php?action=getStudentAccounts')
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Filter only enrolled students
        enrolledStudentsData = data.students.filter(s => s.enrollment_status === 'enrolled');
        filteredEnrolledStudents = [...enrolledStudentsData];
        
        // Extract unique sections for filter
        const sections = [...new Map(enrolledStudentsData.filter(s => s.section_name).map(s => [s.section_name, {section_name: s.section_name}])).values()];
        populateSectionFilter(sections);
        
        // Calculate stats
        const uniqueGrades = [...new Set(enrolledStudentsData.map(s => s.grade_level))];
        const uniqueSections = [...new Set(enrolledStudentsData.map(s => s.section_name).filter(Boolean))];
        const thisMonth = enrolledStudentsData.length;
        
        updateEnrolledStats({
          total: enrolledStudentsData.length,
          thisMonth: thisMonth,
          sectionsCount: uniqueSections.length,
          gradeLevelsCount: uniqueGrades.length
        });
        
        renderEnrolledStudents();
      }
    })
    .catch(err => {
      console.error('Error loading enrolled students:', err);
      document.getElementById('enrolledStudentsList').innerHTML = 
        '<tr><td colspan="7" class="px-4 py-3 text-center text-red-500">Error loading data</td></tr>';
    });
}

// Update statistics
function updateEnrolledStats(data) {
  document.getElementById('totalEnrolledCount').textContent = data.total || 0;
  document.getElementById('enrolledThisMonth').textContent = data.thisMonth || 0;
  document.getElementById('totalSections').textContent = data.sectionsCount || 0;
  document.getElementById('totalGradeLevels').textContent = data.gradeLevelsCount || 0;
  document.getElementById('enrolledBadge').textContent = data.total || 0;
}

// Populate section filter
function populateSectionFilter(sections) {
  const select = document.getElementById('filterSection');
  if (!select) return;
  
  select.innerHTML = '<option value="">All Sections</option>';
  sections.forEach(sec => {
    select.innerHTML += `<option value="${sec.section_name}">${sec.section_name}</option>`;
  });
}

// Filter enrolled students
function filterEnrolledStudents() {
  const grade = document.getElementById('filterGradeLevel')?.value || '';
  const section = document.getElementById('filterSection')?.value || '';
  const search = document.getElementById('searchEnrolled')?.value.toLowerCase() || '';

  filteredEnrolledStudents = enrolledStudentsData.filter(s => {
    const matchGrade = !grade || s.grade_level == grade;
    const matchSection = !section || s.section_name == section;
    const matchSearch = !search || 
      s.student_name.toLowerCase().includes(search) || 
      s.student_id.toString().includes(search);
    return matchGrade && matchSection && matchSearch;
  });

  currentEnrolledPage = 1;
  renderEnrolledStudents();
}

// Render enrolled students
function renderEnrolledStudents() {
  const tbody = document.getElementById('enrolledStudentsList');
  if (!tbody) return;
  
  const start = (currentEnrolledPage - 1) * enrolledPerPage;
  const end = start + enrolledPerPage;
  const pageData = filteredEnrolledStudents.slice(start, end);

  if (pageData.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="7" class="px-4 py-12 text-center">
          <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
          <p class="text-gray-400 font-medium">No enrolled students found</p>
        </td>
      </tr>
    `;
  } else {
    tbody.innerHTML = pageData.map(s => `
      <tr class="hover:bg-green-50 border-b transition-colors">
        <td class="px-4 py-3 font-medium text-gray-900">${s.student_id}</td>
        <td class="px-4 py-3 text-gray-700">${s.student_name}</td>
        <td class="px-4 py-3">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            ${s.grade_level}
          </span>
        </td>
        <td class="px-4 py-3">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
            ${s.section_name || 'Not Assigned'}
          </span>
        </td>
        <td class="px-4 py-3 text-sm text-gray-600">${s.date_enrolled || 'N/A'}</td>
        <td class="px-4 py-3 text-sm text-gray-600">${getCurrentSchoolYear()}</td>
        <td class="px-4 py-3 text-center">
          <button onclick="viewStudentEnrolled('${s.student_id}')" class="text-blue-600 hover:text-blue-800 p-1" title="View Details">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
          </button>
        </td>
      </tr>
    `).join('');
  }
  updatePagination();
}

// Get current school year
function getCurrentSchoolYear() {
  const now = new Date();
  const year = now.getFullYear();
  const month = now.getMonth();
  return month >= 5 ? `${year}-${year+1}` : `${year-1}-${year}`;
}

// Pagination functions
function updatePagination() {
  const total = filteredEnrolledStudents.length;
  const totalPages = Math.ceil(total / enrolledPerPage);
  const start = total > 0 ? (currentEnrolledPage - 1) * enrolledPerPage + 1 : 0;
  const end = Math.min(currentEnrolledPage * enrolledPerPage, total);

  const paginationInfo = document.getElementById('enrolledPaginationInfo');
  if (paginationInfo) {
    paginationInfo.textContent = total > 0 ? `Showing ${start}-${end} of ${total} students` : 'No students found';
  }
  
  const prevBtn = document.getElementById('prevEnrolledBtn');
  const nextBtn = document.getElementById('nextEnrolledBtn');
  if (prevBtn) prevBtn.disabled = currentEnrolledPage === 1;
  if (nextBtn) nextBtn.disabled = currentEnrolledPage >= totalPages;

  const pageNumbers = document.getElementById('enrolledPageNumbers');
  if (pageNumbers) {
    pageNumbers.innerHTML = '';
    for (let i = 1; i <= Math.min(totalPages, 5); i++) {
      pageNumbers.innerHTML += `
        <button onclick="goToEnrolledPage(${i})" 
                class="px-3 py-2 border rounded-lg text-sm font-medium ${i === currentEnrolledPage ? 'bg-green-600 text-white border-green-600' : 'border-gray-300 hover:bg-gray-50'}">
          ${i}
        </button>
      `;
    }
  }
}

function prevEnrolledPage() { 
  if (currentEnrolledPage > 1) { 
    currentEnrolledPage--; 
    renderEnrolledStudents(); 
  } 
}

function nextEnrolledPage() { 
  const totalPages = Math.ceil(filteredEnrolledStudents.length / enrolledPerPage); 
  if (currentEnrolledPage < totalPages) { 
    currentEnrolledPage++; 
    renderEnrolledStudents(); 
  } 
}

function goToEnrolledPage(page) { 
  currentEnrolledPage = page; 
  renderEnrolledStudents(); 
}

// View student details (enrolled)
function viewStudentEnrolled(studentId) {
  fetch(`api/admin-api.php?action=getStudentDetails&student_id=${encodeURIComponent(studentId)}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const s = data.student;
        // Use modern modal if available
        if (typeof showModernModal === 'function' && typeof createStudentDetailsHTML === 'function') {
          showModernModal('Student Details', createStudentDetailsHTML(s, 'enrolled'));
        } else {
          // Fallback
          alert('Student: ' + s.student_name + '\nID: ' + s.student_id + '\nGrade: ' + s.grade_level);
        }
      } else {
        alert('Error loading student details: ' + data.message);
      }
    })
    .catch(err => {
      console.error('Error:', err);
      alert('Failed to load student details');
    });
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
  console.log('Admin dashboard enrollment functions initialized');
});
// Updated View Student Modal - Wider and Cleaner
function viewStudentPending(studentId) {
  fetch(`api/admin-api.php?action=getStudentDetails&student_id=${encodeURIComponent(studentId)}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const s = data.student;
        showModernModal('Student Details', createStudentDetailsHTML(s, 'pending'));
      } else {
        showErrorModal('Error', data.message);
      }
    })
    .catch(err => {
      console.error('Error:', err);
      showErrorModal('Error', 'Failed to load student details');
    });
}

function viewStudentEnrolled(studentId) {
  fetch(`api/admin-api.php?action=getStudentDetails&student_id=${encodeURIComponent(studentId)}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const s = data.student;
        showModernModal('Student Details', createStudentDetailsHTML(s, 'enrolled'));
      } else {
        showErrorModal('Error', data.message);
      }
    })
    .catch(err => {
      console.error('Error:', err);
      showErrorModal('Error', 'Failed to load student details');
    });
}

// Create Student Details HTML
function createStudentDetailsHTML(s, status) {
  const statusBadge = status === 'pending' 
    ? '<span class="px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">⏳ Pending Enrollment</span>'
    : '<span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">✓ Enrolled</span>';
  
  return `
    <div class="space-y-6">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
              ${s.student_name.charAt(0).toUpperCase()}
            </div>
            <div>
              <h3 class="text-2xl font-bold text-gray-900">${s.student_name}</h3>
              <p class="text-gray-600 text-sm">Student ID: <span class="font-semibold">${s.student_id}</span></p>
              <div class="mt-2">${statusBadge}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Student Information Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Academic Information -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
          <h4 class="text-lg font-semibold text-blue-700 mb-4 flex items-center border-b pb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Academic Information
          </h4>
          <div class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-100">
              <span class="text-gray-600 text-sm">Grade Level</span>
              <span class="font-semibold text-gray-900">${s.grade_level}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
              <span class="text-gray-600 text-sm">Section</span>
              <span class="font-semibold text-gray-900">${s.section_name || 'Not Assigned'}</span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-gray-600 text-sm">Date Enrolled</span>
              <span class="font-semibold text-gray-900">${s.date_enrolled ? new Date(s.date_enrolled).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</span>
            </div>
          </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
          <h4 class="text-lg font-semibold text-blue-700 mb-4 flex items-center border-b pb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Personal Information
          </h4>
          <div class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-100">
              <span class="text-gray-600 text-sm">Gender</span>
              <span class="font-semibold text-gray-900">${s.gender || 'N/A'}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
              <span class="text-gray-600 text-sm">Birthdate</span>
              <span class="font-semibold text-gray-900">${s.birthdate || 'N/A'}</span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-gray-600 text-sm">Religion</span>
              <span class="font-semibold text-gray-900">${s.religion || 'N/A'}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
        <h4 class="text-lg font-semibold text-blue-700 mb-4 flex items-center border-b pb-2">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
          </svg>
          Contact Information
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-gray-600 text-sm mb-1">Contact Number</p>
            <p class="font-semibold text-gray-900">${s.contact_number || 'N/A'}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Address</p>
            <p class="font-semibold text-gray-900">${s.address || 'N/A'}</p>
          </div>
        </div>
      </div>

      <!-- Parent/Guardian Information -->
      <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
        <h4 class="text-lg font-semibold text-blue-700 mb-4 flex items-center border-b pb-2">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
          Parent/Guardian Information
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <p class="text-gray-600 text-sm mb-1">Father's Name</p>
            <p class="font-semibold text-gray-900 mb-3">${s.father_name || 'N/A'}</p>
            <p class="text-gray-600 text-sm mb-1">Occupation</p>
            <p class="font-medium text-gray-700">${s.father_occupation || 'N/A'}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm mb-1">Mother's Name</p>
            <p class="font-semibold text-gray-900 mb-3">${s.mother_name || 'N/A'}</p>
            <p class="text-gray-600 text-sm mb-1">Occupation</p>
            <p class="font-medium text-gray-700">${s.mother_occupation || 'N/A'}</p>
          </div>
        </div>
      </div>
    </div>
  `;
}

// Modern Modal Function
function showModernModal(title, content, size = 'large') {
  const sizeClasses = {
    small: 'max-w-md',
    medium: 'max-w-2xl',
    large: 'max-w-5xl',
    xlarge: 'max-w-7xl'
  };

  const modalHTML = `
    <div id="modernModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
      <div class="bg-white rounded-2xl shadow-2xl ${sizeClasses[size]} w-full my-8 max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6 rounded-t-2xl flex justify-between items-center flex-shrink-0">
          <h2 class="text-2xl font-bold">${title}</h2>
          <button onclick="closeModernModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <!-- Content -->
        <div class="p-8 overflow-y-auto flex-1">
          ${content}
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 rounded-b-2xl flex justify-end border-t flex-shrink-0">
          <button onclick="closeModernModal()" 
                  class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-colors">
            Close
          </button>
        </div>
      </div>
    </div>
  `;
  
  document.body.insertAdjacentHTML('beforeend', modalHTML);
  document.body.style.overflow = 'hidden';
}

// Error Modal
function showErrorModal(title, message) {
  const content = `
    <div class="text-center py-8">
      <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
        <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-gray-900 mb-2">${title}</h3>
      <p class="text-gray-600">${message}</p>
    </div>
  `;
  showModernModal('Error', content, 'small');
}

// Success Modal
function showSuccessModal(title, message) {
  const content = `
    <div class="text-center py-8">
      <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
        <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-gray-900 mb-2">${title}</h3>
      <p class="text-gray-600">${message}</p>
    </div>
  `;
  showModernModal('Success', content, 'small');
  setTimeout(() => closeModernModal(), 2000);
}

// Close Modal
function closeModernModal() {
  const modal = document.getElementById('modernModal');
  if (modal) {
    modal.remove();
    document.body.style.overflow = 'auto';
  }
}

// Also update the existing closeViewStudentModal function
function closeViewStudentModal() {
  closeModernModal();
}

// ============================================
// TEACHER STUDENT ASSIGNMENT MODAL (UPDATED)
// ============================================

function showAssignStudentsModal(teacherId, teacherName, sections, students, assignedStudents) {
  const assignedIds = assignedStudents.map(s => s.student_id);
  
  // Group students by section
  const studentsBySection = {};
  students.forEach(student => {
    if (!studentsBySection[student.section_name]) {
      studentsBySection[student.section_name] = [];
    }
    studentsBySection[student.section_name].push(student);
  });
  
  const content = `
    <div class="space-y-6">
      <!-- Teacher Info Card -->
      <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-6">
        <div class="flex items-center space-x-4 mb-4">
          <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
            ${teacherName.charAt(0).toUpperCase()}
          </div>
          <div>
            <h3 class="text-xl font-bold text-gray-900">${teacherName}</h3>
            <p class="text-gray-600 text-sm">Teacher ID: ${teacherId}</p>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4 text-center">
          <div class="bg-white rounded-lg p-3">
            <p class="text-2xl font-bold text-blue-600">${sections.length}</p>
            <p class="text-xs text-gray-600">Available Sections</p>
          </div>
          <div class="bg-white rounded-lg p-3">
            <p class="text-2xl font-bold text-green-600">${assignedStudents.length}</p>
            <p class="text-xs text-gray-600">Assigned Students</p>
          </div>
          <div class="bg-white rounded-lg p-3">
            <p class="text-2xl font-bold text-orange-600">${students.length}</p>
            <p class="text-xs text-gray-600">Total Available</p>
          </div>
        </div>
      </div>

      <!-- Info Message -->
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
          <p class="text-sm font-semibold text-blue-900">Assignment Information</p>
          <p class="text-sm text-blue-700 mt-1">Assign students from any enrolled section. Schedules can be created after student assignment.</p>
        </div>
      </div>

      <!-- Currently Assigned Students -->
      ${assignedStudents.length > 0 ? `
        <div class="bg-white border border-gray-200 rounded-lg">
          <div class="bg-green-50 px-6 py-4 border-b flex justify-between items-center">
            <h4 class="font-semibold text-green-800 flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Currently Assigned (${assignedStudents.length})
            </h4>
            <button onclick="removeAllTeacherStudents('${teacherId}', '${teacherName}')" 
                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg font-medium">
              Remove All
            </button>
          </div>
          <div class="overflow-x-auto max-h-64">
            <table class="w-full text-sm">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-4 py-3 text-left font-semibold text-gray-700">Student ID</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-700">Section</th>
                  <th class="px-4 py-3 text-center font-semibold text-gray-700">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                ${assignedStudents.map(s => `
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">${s.student_id}</td>
                    <td class="px-4 py-3 font-medium">${s.student_name}</td>
                    <td class="px-4 py-3">
                      <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                        ${s.section_name || 'N/A'}
                      </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <button onclick="removeStudentFromTeacher('${teacherId}', '${s.student_id}', '${teacherName}')" 
                              class="text-red-600 hover:text-red-800 p-1" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                      </button>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        </div>
      ` : `
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
          <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
          <p class="text-gray-500 font-medium">No students assigned yet</p>
        </div>
      `}

      <!-- Available Students by Section -->
      ${sections.length > 0 && students.length > 0 ? `
        <div>
          <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Assign Students from Available Sections
          </h4>
          
          <div class="space-y-4 max-h-96 overflow-y-auto">
            ${Object.keys(studentsBySection).map(sectionName => {
              const sectionStudents = studentsBySection[sectionName];
              const unassignedCount = sectionStudents.filter(s => !assignedIds.includes(s.student_id)).length;
              
              return `
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                  <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-5 py-3 flex justify-between items-center">
                    <span class="text-white font-semibold">${sectionName}</span>
                    <div class="flex items-center gap-3">
                      <span class="text-white text-xs bg-white bg-opacity-25 px-3 py-1 rounded-full font-medium">
                        ${unassignedCount} available
                      </span>
                      ${unassignedCount > 0 ? `
                        <button onclick="assignAllStudentsFromSection('${teacherId}', '${sectionName}')" 
                                class="text-xs bg-white text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg font-semibold">
                          Assign All
                        </button>
                      ` : ''}
                    </div>
                  </div>
                  <div class="p-4 bg-gray-50">
                    ${sectionStudents.length > 0 ? `
                      <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto">
                        ${sectionStudents.map(student => {
                          const isAssigned = assignedIds.includes(student.student_id);
                          return `
                            <div class="flex items-center justify-between p-3 bg-white border rounded-lg ${isAssigned ? 'border-green-300 bg-green-50' : 'border-gray-200'}">
                              <div class="flex items-center gap-3">
                                <input type="checkbox" 
                                       id="student_${student.student_id}" 
                                       value="${student.student_id}"
                                       ${isAssigned ? 'checked disabled' : ''}
                                       class="w-4 h-4 text-blue-600 rounded">
                                <label for="student_${student.student_id}" class="text-sm ${isAssigned ? 'text-gray-500' : 'text-gray-700'}">
                                  <span class="font-semibold">${student.student_id}</span> - ${student.student_name}
                                </label>
                              </div>
                              ${isAssigned ? `
                                <span class="text-xs bg-green-200 text-green-800 px-3 py-1 rounded-full font-medium">Assigned</span>
                              ` : `
                                <button onclick="assignSingleStudent('${teacherId}', '${student.student_id}', '${teacherName}')" 
                                        class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-lg font-medium">
                                  Assign
                                </button>
                              `}
                            </div>
                          `;
                        }).join('')}
                      </div>
                    ` : '<p class="text-gray-500 text-sm text-center py-4">No students in this section</p>'}
                  </div>
                </div>
              `;
            }).join('')}
          </div>

          <!-- Bulk Assignment Button -->
          <div class="mt-4">
            <button onclick="assignSelectedStudents('${teacherId}', '${teacherName}')" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2 shadow-md">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Assign Selected Students
            </button>
          </div>
        </div>
      ` : `
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
          <svg class="w-12 h-12 mx-auto text-yellow-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
          <p class="text-yellow-800 font-medium">No enrolled students available</p>
          <p class="text-yellow-700 text-sm mt-1">Please enroll students first before assigning them to teachers.</p>
        </div>
      `}
    </div>
  `;
  
  showModernModal(`Assign Students to ${teacherName}`, content, 'xlarge');
}

// Update the close function
function closeAssignStudentsModal() {
  closeModernModal();
}
  </script>

  <style>
    .nav-btn.active { background-color: #2563eb; font-weight: 600; }
    .nav-btn:hover { background-color: #3b82f6; }
    .scrollbar-thin::-webkit-scrollbar { width: 8px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .scrollbar-thin { scrollbar-width: thin; scrollbar-color: #cbd5e1 #f1f5f9; }
    .sidebar-collapsed { width: 5rem; }
    .sidebar-expanded { width: 16rem; }
    .sidebar-text { transition: opacity 0.3s, width 0.3s; }
    .sidebar-collapsed .sidebar-text { opacity: 0; width: 0; overflow: hidden; }
    .main-content-expanded { margin-left: 16rem; }
    .main-content-collapsed { margin-left: 5rem; }
    .sidebar-collapsed nav button { justify-content: center; }
    .sidebar-collapsed .logout-btn { justify-content: center; }
  </style>
</body>
</html>