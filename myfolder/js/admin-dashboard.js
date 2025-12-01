// ========================================
// ADMIN DASHBOARD - FULL CRUD OPERATIONS
// ========================================

// Section Navigation
function showSection(sectionName) {
  console.log('Switching to section:', sectionName);
  
  // Hide all sections
  document.querySelectorAll('main section').forEach(sec => sec.classList.add('hidden'));
  
  // Remove active state from all nav buttons
  document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  
  // Show selected section
  const section = document.getElementById(sectionName + 'Section');
  if (section) {
    section.classList.remove('hidden');
    console.log('Section shown:', sectionName + 'Section');
  } else {
    console.error('Section not found:', sectionName + 'Section');
  }
  
  // Set active button
  const activeBtn = document.getElementById(sectionName + 'Btn');
  if (activeBtn) {
    activeBtn.classList.add('active');
  }
  
  // Load data for specific sections
  switch(sectionName) {
    case 'dashboard':
      loadDashboardStats();
      break;
    case 'teacherAcc':
      loadTeacherAccounts();
      break;
    case 'studentAcc':
      loadStudentAccounts();
      break;
    case 'teacherSched':
      console.log('Loading schedules section...');
      loadSchedules();
      loadScheduleDropdowns();
      break;
    case 'enrollments':
      loadPendingEnrollments();
      break;
  }
}

// Collapsible Menu Toggle
document.getElementById('accountsToggle').addEventListener('click', function() {
  const sub = document.getElementById('accountsSub');
  const arrow = document.getElementById('accountsArrow');
  
  if (sub.style.maxHeight && sub.style.maxHeight !== '0px') {
    sub.style.maxHeight = '0px';
    arrow.textContent = '▾';
  } else {
    sub.style.maxHeight = sub.scrollHeight + 'px';
    arrow.textContent = '▴';
  }
});

document.getElementById('schedulesToggle').addEventListener('click', function() {
  const sub = document.getElementById('schedulesSub');
  const arrow = document.getElementById('schedulesArrow');
  
  if (sub.style.maxHeight && sub.style.maxHeight !== '0px') {
    sub.style.maxHeight = '0px';
    arrow.textContent = '▾';
  } else {
    sub.style.maxHeight = sub.scrollHeight + 'px';
    arrow.textContent = '▴';
  }
});

// ========================================
// DASHBOARD STATISTICS
// ========================================
function loadDashboardStats() {
  console.log('Loading dashboard stats...');
  
  fetch('api/admin-api.php?action=getDashboardStats')
    .then(res => {
      console.log('Response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Dashboard stats received:', data);
      
      if (data.success) {
        // Main stats
        document.getElementById('totalTeachers').textContent = data.stats.totalTeachers || '0';
        document.getElementById('totalStudents').textContent = data.stats.totalStudents || '0';
        document.getElementById('totalSchedules').textContent = data.stats.totalSchedules || '0';
        document.getElementById('pendingEnrollments').textContent = data.stats.pendingEnrollments || '0';
        
        // Active accounts - Teachers
        document.getElementById('activeTeachers').textContent = data.stats.totalTeachers || '0';
        document.getElementById('teachersWithAccounts').textContent = data.stats.teachersWithAccounts || '0';
        document.getElementById('teachersWithoutAccounts').textContent = data.stats.teachersWithoutAccounts || '0';
        
        // Active accounts - Students
        document.getElementById('activeStudents').textContent = data.stats.totalStudents || '0';
        document.getElementById('enrolledStudents').textContent = data.stats.enrolledStudents || '0';
        document.getElementById('pendingStudents').textContent = data.stats.pendingEnrollments || '0';
        
        console.log('Dashboard stats updated successfully');
      } else {
        console.error('API returned error:', data.message);
      }
    })
    .catch(err => {
      console.error('Error loading dashboard stats:', err);
    });
  
  loadActivityLogs();
}

function loadActivityLogs() {
  console.log('Loading activity logs...');
  
  fetch('api/admin-api.php?action=getActivityLogs')
    .then(res => {
      console.log('Activity logs response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Activity logs received:', data);
      
      const logsList = document.getElementById('activityLogs');
      
      if (data.success && data.logs && data.logs.length > 0) {
        logsList.innerHTML = data.logs.map(log => {
          const timeAgo = getTimeAgo(log.login_time);
          
          // Determine icon and color based on activity type and role
          let iconColor = 'text-gray-600';
          let icon = '';
          let badgeColor = 'bg-gray-100 text-gray-800';
          
          if (log.activity_type === 'login') {
            if (log.role === 'teacher') {
              iconColor = 'text-blue-600';
              badgeColor = 'bg-blue-100 text-blue-800';
            } else if (log.role === 'student') {
              iconColor = 'text-orange-600';
              badgeColor = 'bg-orange-100 text-orange-800';
            } else {
              iconColor = 'text-purple-600';
              badgeColor = 'bg-purple-100 text-purple-800';
            }
            icon = `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>`;
          } else if (log.activity_type === 'enrollment_submitted') {
            iconColor = 'text-green-600';
            badgeColor = 'bg-green-100 text-green-800';
            icon = `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>`;
          } else if (log.activity_type === 'enrollment_approved') {
            iconColor = 'text-green-600';
            badgeColor = 'bg-green-100 text-green-800';
            icon = `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
          } else if (log.activity_type === 'failed_login') {
            iconColor = 'text-red-600';
            badgeColor = 'bg-red-100 text-red-800';
            icon = `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>`;
          } else if (log.activity_type === 'logout') {
            iconColor = 'text-gray-600';
            badgeColor = 'bg-gray-100 text-gray-800';
            icon = `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>`;
          } else {
            // Default icon for other activities
            if (log.role === 'teacher') {
              iconColor = 'text-blue-600';
              badgeColor = 'bg-blue-100 text-blue-800';
            } else if (log.role === 'student') {
              iconColor = 'text-orange-600';
              badgeColor = 'bg-orange-100 text-orange-800';
            }
            icon = `<svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
          }
          
          // Activity type labels
          const activityLabels = {
            'login': 'Login',
            'logout': 'Logout',
            'failed_login': 'Failed Login',
            'enrollment_submitted': 'Enrollment',
            'enrollment_approved': 'Approved',
            'account_created': 'Account Created'
          };
          
          const activityLabel = activityLabels[log.activity_type] || log.activity_type;
          
          return `
            <li class="py-3 hover:bg-gray-50 px-2 rounded transition-colors">
              <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">${icon}</div>
                <div class="ml-3 flex-1">
                  <p class="text-sm font-medium text-gray-900">
                    ${log.name || log.username}
                  </p>
                  <p class="text-xs text-gray-600 mt-1">
                    ${log.activity_description || activityLabel}
                  </p>
                  <p class="text-xs text-gray-500 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${badgeColor}">
                      ${activityLabel}
                    </span>
                    <span class="ml-2">${timeAgo}</span>
                  </p>
                </div>
                <div class="text-xs text-gray-400 mt-1">
                  ${new Date(log.login_time).toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                  })}
                </div>
              </div>
            </li>
          `;
        }).join('');
        
        console.log('Activity logs rendered successfully');
      } else {
        logsList.innerHTML = `
          <li class="py-8 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p>No recent activity</p>
          </li>
        `;
        console.log('No activity logs found');
      }
    })
    .catch(err => {
      console.error('Error loading activity logs:', err);
      document.getElementById('activityLogs').innerHTML = 
        '<li class="py-2 text-red-500">Error loading activity logs. Check console for details.</li>';
    });
}

// Helper function to get time ago string
function getTimeAgo(datetime) {
  const now = new Date();
  const loginTime = new Date(datetime);
  const diffMs = now - loginTime;
  const diffMins = Math.floor(diffMs / 60000);
  const diffHours = Math.floor(diffMins / 60);
  const diffDays = Math.floor(diffHours / 24);
  
  if (diffMins < 1) return 'Just now';
  if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
  if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
  if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
  return loginTime.toLocaleDateString();
}

// ========================================
// TEACHER ACCOUNTS - FULL CRUD
// ========================================
function loadTeacherAccounts() {
  fetch('api/admin-api.php?action=getTeacherAccounts')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('teacherAccountsList');
      if (data.success && data.teachers.length > 0) {
        tbody.innerHTML = data.teachers.map(teacher => `
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2 border">${teacher.teacher_id}</td>
            <td class="px-3 py-2 border">${teacher.teacher_name}</td>
            <td class="px-3 py-2 border">${teacher.username || 'N/A'}</td>
            <td class="px-3 py-2 border">
              <span class="px-2 py-1 text-xs rounded ${teacher.username ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                ${teacher.username ? 'Active' : 'No Account'}
              </span>
            </td>
            <td class="px-3 py-2 border text-center">
              <button onclick="viewTeacher('${teacher.teacher_id}')" 
                      class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-1">
                View
              </button>
              <button onclick="editTeacher('${teacher.teacher_id}')" 
                      class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm mr-1">
                Edit
              </button>
              <button onclick="deleteTeacher('${teacher.teacher_id}')" 
                      class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                Delete
              </button>
            </td>
          </tr>
        `).join('');
      } else {
        tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-2 text-center text-gray-400">No teacher accounts found</td></tr>';
      }
    })
    .catch(err => console.error('Error loading teachers:', err));
}

// Add Teacher Form Submit
document.getElementById('addTeacherForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append('action', 'addTeacher');
  
  fetch('api/admin-api.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showNotification('Success!', 'Teacher added successfully', 'success');
      this.reset();
      loadTeacherAccounts();
      loadDashboardStats();
    } else {
      showNotification('Error', data.message, 'error');
    }
  })
  .catch(err => {
    console.error('Error adding teacher:', err);
    showNotification('Error', 'Failed to add teacher. Please try again.', 'error');
  });
});

// View Teacher Details
function viewTeacher(teacherId) {
  fetch(`api/admin-api.php?action=getTeacherDetails&teacher_id=${teacherId}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const teacher = data.teacher;
        showModal('Teacher Details', `
          <div class="space-y-3">
            <div>
              <p class="text-sm text-gray-500">Teacher ID</p>
              <p class="font-semibold">${teacher.teacher_id}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Name</p>
              <p class="font-semibold">${teacher.teacher_name}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Username</p>
              <p class="font-semibold">${teacher.username || 'Not assigned'}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Account Status</p>
              <p class="font-semibold">${teacher.username ? 'Active' : 'No Account'}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Total Subjects Teaching</p>
              <p class="font-semibold">${teacher.subject_count || 0}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Total Schedules</p>
              <p class="font-semibold">${teacher.schedule_count || 0}</p>
            </div>
          </div>
        `);
      }
    })
    .catch(err => console.error('Error:', err));
}

// Edit Teacher
function editTeacher(teacherId) {
  fetch(`api/admin-api.php?action=getTeacherDetails&teacher_id=${teacherId}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const teacher = data.teacher;
        showModal('Edit Teacher', `
          <form id="editTeacherForm" class="space-y-4">
            <input type="hidden" name="teacher_id" value="${teacher.teacher_id}">
            
            <div>
              <label class="block text-sm font-medium mb-1">Teacher ID</label>
              <input type="text" value="${teacher.teacher_id}" class="w-full border rounded p-2 bg-gray-100" disabled>
            </div>
            
            <div>
              <label class="block text-sm font-medium mb-1">Teacher Name *</label>
              <input type="text" name="teacher_name" value="${teacher.teacher_name}" 
                     class="w-full border rounded p-2" required>
            </div>
            
            <div>
              <label class="block text-sm font-medium mb-1">Username</label>
              <input type="text" name="username" value="${teacher.username || ''}" 
                     class="w-full border rounded p-2" placeholder="Leave blank to keep current">
            </div>
            
            <div>
              <label class="block text-sm font-medium mb-1">New Password</label>
              <input type="password" name="password" 
                     class="w-full border rounded p-2" placeholder="Leave blank to keep current">
              <p class="text-xs text-gray-500 mt-1">Only enter if you want to change the password</p>
            </div>
            
            <div class="flex gap-2 mt-4">
              <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                Update Teacher
              </button>
              <button type="button" onclick="closeModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 py-2 rounded">
                Cancel
              </button>
            </div>
          </form>
        `);
        
        // Handle form submission
        document.getElementById('editTeacherForm').addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          formData.append('action', 'updateTeacher');
          
          fetch('api/admin-api.php', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              closeModal();
              showNotification('Updated!', 'Teacher updated successfully', 'success');
              loadTeacherAccounts();
            } else {
              showNotification('Error', data.message, 'error');
            }
          })
          .catch(err => {
            console.error('Error:', err);
            showNotification('Error', 'Failed to update teacher', 'error');
          });
        });
      }
    })
    .catch(err => console.error('Error:', err));
}

// Delete Teacher
function deleteTeacher(teacherId) {
  showConfirmation(
    'Delete Teacher?',
    'Are you sure you want to delete this teacher? This will also remove all related schedules and subjects.',
    function() {
      // On confirm
      fetch('api/admin-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=deleteTeacher&teacher_id=${teacherId}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showNotification('Deleted!', 'Teacher deleted successfully', 'success');
          loadTeacherAccounts();
          loadDashboardStats();
        } else {
          showNotification('Error', data.message, 'error');
        }
      })
      .catch(err => {
        console.error('Error deleting teacher:', err);
        showNotification('Error', 'Failed to delete teacher', 'error');
      });
    },
    function() {
      // On cancel - do nothing
    }
  );
}

// ========================================
// STUDENT ACCOUNTS - FULL CRUD
// ========================================
function loadStudentAccounts() {
  fetch('api/admin-api.php?action=getStudentAccounts')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('studentAccountsList');
      if (data.success && data.students.length > 0) {
        tbody.innerHTML = data.students.map(student => `
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2 border">${student.student_id}</td>
            <td class="px-3 py-2 border">${student.student_name}</td>
            <td class="px-3 py-2 border">${student.grade_level}</td>
            <td class="px-3 py-2 border">${student.section_name || 'Not Assigned'}</td>
            <td class="px-3 py-2 border">
              <span class="px-2 py-1 text-xs rounded ${student.enrollment_status === 'enrolled' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                ${student.enrollment_status || 'Pending'}
              </span>
            </td>
            <td class="px-3 py-2 border text-center">
              <button onclick="viewStudent('${student.student_id}')" 
                      class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs mr-1">
                View
              </button>
              <button onclick="editStudent('${student.student_id}')" 
                      class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs mr-1">
                Edit
              </button>
              <button onclick="deleteStudent('${student.student_id}')" 
                      class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                Delete
              </button>
            </td>
          </tr>
        `).join('');
      } else {
        tbody.innerHTML = '<tr><td colspan="6" class="px-3 py-2 text-center text-gray-400">No student accounts found</td></tr>';
      }
    })
    .catch(err => console.error('Error loading students:', err));
}

// View Student Details
function viewStudent(studentId) {
  fetch(`api/admin-api.php?action=getStudentDetails&student_id=${studentId}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const s = data.student;
        showModal('Student Details', `
          <div class="space-y-3 max-h-96 overflow-y-auto">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Student ID</p>
                <p class="font-semibold">${s.student_id}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Name</p>
                <p class="font-semibold">${s.student_name}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Grade Level</p>
                <p class="font-semibold">${s.grade_level}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Gender</p>
                <p class="font-semibold">${s.gender || 'N/A'}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Birthdate</p>
                <p class="font-semibold">${s.birthdate || 'N/A'}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Religion</p>
                <p class="font-semibold">${s.religion || 'N/A'}</p>
              </div>
              <div class="col-span-2">
                <p class="text-sm text-gray-500">Address</p>
                <p class="font-semibold">${s.address || 'N/A'}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Contact Number</p>
                <p class="font-semibold">${s.contact_number || 'N/A'}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Section</p>
                <p class="font-semibold">${s.section_name || 'Not Assigned'}</p>
              </div>
            </div>
            
            <div class="border-t pt-3 mt-3">
              <p class="font-semibold text-gray-700 mb-2">Parent/Guardian Information</p>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500">Father's Name</p>
                  <p class="font-semibold">${s.father_name || 'N/A'}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Father's Occupation</p>
                  <p class="font-semibold">${s.father_occupation || 'N/A'}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Mother's Name</p>
                  <p class="font-semibold">${s.mother_name || 'N/A'}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Mother's Occupation</p>
                  <p class="font-semibold">${s.mother_occupation || 'N/A'}</p>
                </div>
              </div>
            </div>
          </div>
        `);
      }
    })
    .catch(err => console.error('Error:', err));
}

// Edit Student
function editStudent(studentId) {
  fetch(`api/admin-api.php?action=getStudentDetails&student_id=${studentId}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const s = data.student;
        showModal('Edit Student', `
          <form id="editStudentForm" class="space-y-3 max-h-96 overflow-y-auto">
            <input type="hidden" name="student_id" value="${s.student_id}">
            
            <div class="grid grid-cols-2 gap-3">
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1">Student Name *</label>
                <input type="text" name="student_name" value="${s.student_name}" 
                       class="w-full border rounded p-2" required>
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-1">Grade Level *</label>
                <select name="grade_level" class="w-full border rounded p-2" required>
                  <option value="Kindergarten" ${s.grade_level === 'Kindergarten' ? 'selected' : ''}>Kindergarten</option>
                  <option value="Grade 1" ${s.grade_level === 'Grade 1' ? 'selected' : ''}>Grade 1</option>
                  <option value="Grade 2" ${s.grade_level === 'Grade 2' ? 'selected' : ''}>Grade 2</option>
                  <option value="Grade 3" ${s.grade_level === 'Grade 3' ? 'selected' : ''}>Grade 3</option>
                  <option value="Grade 4" ${s.grade_level === 'Grade 4' ? 'selected' : ''}>Grade 4</option>
                  <option value="Grade 5" ${s.grade_level === 'Grade 5' ? 'selected' : ''}>Grade 5</option>
                  <option value="Grade 6" ${s.grade_level === 'Grade 6' ? 'selected' : ''}>Grade 6</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-1">Gender</label>
                <select name="gender" class="w-full border rounded p-2">
                  <option value="Male" ${s.gender === 'Male' ? 'selected' : ''}>Male</option>
                  <option value="Female" ${s.gender === 'Female' ? 'selected' : ''}>Female</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-1">Birthdate</label>
                <input type="date" name="birthdate" value="${s.birthdate || ''}" 
                       class="w-full border rounded p-2">
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-1">Religion</label>
                <input type="text" name="religion" value="${s.religion || ''}" 
                       class="w-full border rounded p-2">
              </div>
              
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1">Address</label>
                <textarea name="address" class="w-full border rounded p-2" rows="2">${s.address || ''}</textarea>
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-1">Contact Number</label>
                <input type="text" name="contact_number" value="${s.contact_number || ''}" 
                       class="w-full border rounded p-2">
              </div>
            </div>
            
            <div class="flex gap-2 mt-4">
              <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                Update Student
              </button>
              <button type="button" onclick="closeModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 py-2 rounded">
                Cancel
              </button>
            </div>
          </form>
        `);
        
        document.getElementById('editStudentForm').addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          formData.append('action', 'updateStudent');
          
          fetch('api/admin-api.php', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              closeModal();
              showNotification('Updated!', 'Student updated successfully', 'success');
              loadStudentAccounts();
            } else {
              showNotification('Error', data.message, 'error');
            }
          })
          .catch(err => {
            console.error('Error:', err);
            showNotification('Error', 'Failed to update student', 'error');
          });
        });
      }
    })
    .catch(err => console.error('Error:', err));
}

// Delete Student
function deleteStudent(studentId) {
  showConfirmation(
    'Delete Student?',
    'Are you sure you want to delete this student? This will also remove all grades and enrollment records.',
    function() {
      // On confirm
      fetch('api/admin-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=deleteStudent&student_id=${studentId}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showNotification('Deleted!', 'Student deleted successfully', 'success');
          loadStudentAccounts();
          loadDashboardStats();
        } else {
          showNotification('Error', data.message, 'error');
        }
      })
      .catch(err => {
        console.error('Error deleting student:', err);
        showNotification('Error', 'Failed to delete student', 'error');
      });
    },
    function() {
      // On cancel - do nothing
    }
  );
}

// ========================================
// SCHEDULE MANAGEMENT
// ========================================
function loadScheduleDropdowns() {
  console.log('Loading schedule dropdowns...');
  
  // Load teachers
  fetch('api/admin-api.php?action=getTeacherAccounts')
    .then(res => res.json())
    .then(data => {
      console.log('Teachers data:', data);
      const select = document.getElementById('scheduleTeacher');
      if (select && data.success) {
        select.innerHTML = '<option value="">Select Teacher</option>' +
          data.teachers.map(t => `<option value="${t.teacher_id}">${t.teacher_name}</option>`).join('');
        console.log('Teachers dropdown populated');
      } else {
        console.error('Teachers select element not found or data error');
      }
    })
    .catch(err => console.error('Error loading teachers:', err));
  
  // Load subjects
  fetch('api/admin-api.php?action=getSubjects')
    .then(res => res.json())
    .then(data => {
      console.log('Subjects data:', data);
      const select = document.getElementById('scheduleSubject');
      if (select && data.success) {
        select.innerHTML = '<option value="">Select Subject</option>' +
          data.subjects.map(s => `<option value="${s.subject_code}">${s.subject_name}</option>`).join('');
        console.log('Subjects dropdown populated');
      } else {
        console.error('Subjects select element not found or data error');
      }
    })
    .catch(err => console.error('Error loading subjects:', err));
  
  // Load sections
  fetch('api/admin-api.php?action=getSections')
    .then(res => res.json())
    .then(data => {
      console.log('Sections data:', data);
      const select = document.getElementById('scheduleSection');
      if (select && data.success) {
        select.innerHTML = '<option value="">Select Section</option>' +
          data.sections.map(s => `<option value="${s.section_id}">${s.section_name}</option>`).join('');
        console.log('Sections dropdown populated');
      } else {
        console.error('Sections select element not found or data error');
      }
    })
    .catch(err => console.error('Error loading sections:', err));
}

function loadSchedules() {
  console.log('Loading schedules...');
  
  fetch('api/admin-api.php?action=getSchedules')
    .then(res => {
      console.log('Schedules response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Schedules data received:', data);
      
      const tbody = document.getElementById('schedulesList');
      if (!tbody) {
        console.error('schedulesList element not found!');
        return;
      }
      
      if (data.success && data.schedules && data.schedules.length > 0) {
        tbody.innerHTML = data.schedules.map(schedule => {
          const datetime = new Date(schedule.day_time);
          const dayTime = datetime.toLocaleDateString('en-US', { weekday: 'long' }) + ' ' + 
                         datetime.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
          
          return `
            <tr class="hover:bg-gray-50">
              <td class="px-3 py-2 border">${schedule.teacher_name}</td>
              <td class="px-3 py-2 border">${schedule.subject_name}</td>
              <td class="px-3 py-2 border">${schedule.section_name}</td>
              <td class="px-3 py-2 border">${dayTime}</td>
              <td class="px-3 py-2 border">Room ${schedule.room_number}</td>
              <td class="px-3 py-2 border text-center">
                <button onclick="editSchedule(${schedule.schedule_id})" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs mr-1">
                  Edit
                </button>
                <button onclick="deleteSchedule(${schedule.schedule_id})" 
                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                  Delete
                </button>
              </td>
            </tr>
          `;
        }).join('');
        console.log('Schedules rendered successfully:', data.schedules.length, 'schedules');
      } else {
        tbody.innerHTML = '<tr><td colspan="6" class="px-3 py-2 text-center text-gray-400">No schedules found. Add your first schedule above!</td></tr>';
        console.log('No schedules found');
      }
    })
    .catch(err => {
      console.error('Error loading schedules:', err);
      const tbody = document.getElementById('schedulesList');
      if (tbody) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-3 py-2 text-center text-red-500">Error loading schedules. Check console.</td></tr>';
      }
    });
}

document.getElementById('addScheduleForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append('action', 'addSchedule');
  
  fetch('api/admin-api.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showNotification('Success!', 'Schedule added successfully', 'success');
      this.reset();
      loadSchedules();
      loadDashboardStats();
    } else {
      showNotification('Error', data.message, 'error');
    }
  })
  .catch(err => {
    console.error('Error adding schedule:', err);
    showNotification('Error', 'Failed to add schedule', 'error');
  });
});

function deleteSchedule(scheduleId) {
  showConfirmation(
    'Delete Schedule?',
    'Are you sure you want to delete this schedule?',
    function() {
      // On confirm
      fetch('api/admin-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=deleteSchedule&schedule_id=${scheduleId}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showNotification('Deleted!', 'Schedule deleted successfully', 'success');
          loadSchedules();
          loadDashboardStats();
        } else {
          showNotification('Error', data.message, 'error');
        }
      })
      .catch(err => {
        console.error('Error deleting schedule:', err);
        showNotification('Error', 'Failed to delete schedule', 'error');
      });
    },
    function() {
      // On cancel - do nothing
    }
  );
}

function editSchedule(scheduleId) {
  showNotification('Info', 'Edit schedule functionality coming soon!', 'info');
}

// ========================================
// ENROLLMENT MANAGEMENT
// ========================================
function loadPendingEnrollments() {
  fetch('api/admin-api.php?action=getPendingEnrollments')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('pendingEnrollmentsList');
      if (data.success && data.enrollments.length > 0) {
        tbody.innerHTML = data.enrollments.map(enrollment => `
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2 border">${enrollment.student_id}</td>
            <td class="px-3 py-2 border">${enrollment.student_name}</td>
            <td class="px-3 py-2 border">${enrollment.grade_level}</td>
            <td class="px-3 py-2 border">${new Date(enrollment.date_enrolled).toLocaleDateString()}</td>
            <td class="px-3 py-2 border">
              <select id="section_${enrollment.student_id}" class="border rounded p-1 w-full text-sm">
                <option value="">Select Section</option>
                ${data.sections.filter(s => s.grade_level === enrollment.grade_level)
                  .map(s => `<option value="${s.section_id}">${s.section_name}</option>`).join('')}
              </select>
            </td>
            <td class="px-3 py-2 border text-center">
              <button onclick="approveEnrollment('${enrollment.student_id}', '${enrollment.enrollment_id}')" 
                      class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                Approve
              </button>
            </td>
          </tr>
        `).join('');
      } else {
        tbody.innerHTML = '<tr><td colspan="6" class="px-3 py-2 text-center text-gray-400">No pending enrollments</td></tr>';
      }
    })
    .catch(err => console.error('Error loading enrollments:', err));
}

function approveEnrollment(studentId, enrollmentId) {
  const sectionSelect = document.getElementById('section_' + studentId);
  const sectionId = sectionSelect.value;
  
  if (!sectionId) {
    showNotification('Warning', 'Please select a section first!', 'warning');
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
      showNotification('Approved!', 'Enrollment approved successfully', 'success');
      loadPendingEnrollments();
      loadDashboardStats();
    } else {
      showNotification('Error', data.message, 'error');
    }
  })
  .catch(err => {
    console.error('Error approving enrollment:', err);
    showNotification('Error', 'Failed to approve enrollment', 'error');
  });
}

// ========================================
// MODERN MODAL FUNCTIONS
// ========================================
function showNotification(title, message, type = 'success') {
  const iconColors = {
    success: 'text-green-600',
    error: 'text-red-600',
    warning: 'text-yellow-600',
    info: 'text-blue-600'
  };
  
  const bgColors = {
    success: 'bg-green-50',
    error: 'bg-red-50',
    warning: 'bg-yellow-50',
    info: 'bg-blue-50'
  };
  
  const icons = {
    success: `<svg class="w-12 h-12 ${iconColors[type]}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>`,
    error: `<svg class="w-12 h-12 ${iconColors[type]}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>`,
    warning: `<svg class="w-12 h-12 ${iconColors[type]}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
    </svg>`,
    info: `<svg class="w-12 h-12 ${iconColors[type]}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>`
  };
  
  const modalHTML = `
    <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn">
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 transform animate-scaleIn">
        <div class="p-6 text-center">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full ${bgColors[type]} mb-4">
            ${icons[type]}
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">${title}</h3>
          <p class="text-gray-600 mb-6">${message}</p>
          <button onclick="closeNotification()" 
                  class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-all duration-200 transform hover:scale-105">
            OK
          </button>
        </div>
      </div>
    </div>
  `;
  
  document.body.insertAdjacentHTML('beforeend', modalHTML);
  
  // Auto close after 3 seconds
  setTimeout(() => {
    const modal = document.getElementById('notificationModal');
    if (modal) {
      modal.classList.add('animate-fadeOut');
      setTimeout(() => closeNotification(), 300);
    }
  }, 3000);
}

function showConfirmation(title, message, onConfirm, onCancel) {
  const modalHTML = `
    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn">
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 transform animate-scaleIn">
        <div class="p-6">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 text-center mb-2">${title}</h3>
          <p class="text-gray-600 text-center mb-6">${message}</p>
          <div class="flex gap-3">
            <button onclick="handleConfirmCancel()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg transition-all duration-200">
              Cancel
            </button>
            <button onclick="handleConfirmOk()" 
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-all duration-200 transform hover:scale-105">
              Confirm
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
  
  document.body.insertAdjacentHTML('beforeend', modalHTML);
  
  // Store callbacks
  window.confirmCallback = onConfirm;
  window.cancelCallback = onCancel;
}

function handleConfirmOk() {
  if (window.confirmCallback) {
    window.confirmCallback();
  }
  closeConfirmation();
}

function handleConfirmCancel() {
  if (window.cancelCallback) {
    window.cancelCallback();
  }
  closeConfirmation();
}

function closeNotification() {
  const modal = document.getElementById('notificationModal');
  if (modal) {
    modal.remove();
  }
}

function closeConfirmation() {
  const modal = document.getElementById('confirmationModal');
  if (modal) {
    modal.remove();
  }
  window.confirmCallback = null;
  window.cancelCallback = null;
}

// Modal for view/edit forms
function showModal(title, content) {
  const modalHTML = `
    <div id="customModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn">
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden animate-scaleIn">
        <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
          <h3 class="text-xl font-bold">${title}</h3>
          <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-6">
          ${content}
        </div>
      </div>
    </div>
  `;
  
  document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function closeModal() {
  const modal = document.getElementById('customModal');
  if (modal) {
    modal.classList.add('animate-fadeOut');
    setTimeout(() => modal.remove(), 300);
  }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
  const modal = document.getElementById('customModal');
  if (modal && e.target === modal) {
    closeModal();
  }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  @keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
  }
  @keyframes scaleIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }
  .animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
  }
  .animate-fadeOut {
    animation: fadeOut 0.3s ease-out;
  }
  .animate-scaleIn {
    animation: scaleIn 0.3s ease-out;
  }
`;
document.head.appendChild(style);

// ========================================
// INITIALIZE
// ========================================
document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard loaded - initializing...');
  
  // Load dashboard by default
  loadDashboardStats();
  
  // Debug: Check if elements exist
  console.log('Total Teachers element:', document.getElementById('totalTeachers'));
  console.log('Activity Logs element:', document.getElementById('activityLogs'));
});

// ========================================
// TEACHER-STUDENT ASSIGNMENT FUNCTIONS
// Add these functions to your existing admin-dashboard.js file
// ========================================

// Main function to open assignment modal
function assignStudentsToTeacher(teacherId, teacherName) {
  console.log('Opening assignment modal for:', teacherId, teacherName);
  
  // Fetch teacher's assigned sections and students
  Promise.all([
    fetch(`api/admin-api.php?action=getTeacherSectionsForStudents&teacher_id=${teacherId}`).then(r => r.json()),
    fetch(`api/admin-api.php?action=getStudentsByTeacherSections&teacher_id=${teacherId}`).then(r => r.json())
  ])
  .then(([sectionsData, studentsData]) => {
    if (sectionsData.success && studentsData.success) {
      showAssignStudentsModal(teacherId, teacherName, sectionsData.sections, studentsData.students, studentsData.assignedStudents);
    } else {
      showNotification('Error', 'Failed to load data: ' + (sectionsData.message || studentsData.message), 'error');
    }
  })
  .catch(err => {
    console.error('Error:', err);
    showNotification('Error', 'Failed to load data. Check console for details.', 'error');
  });
}

// Show the assign students modal
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
  
  const modalContent = `
    <div class="space-y-4">
      <!-- Teacher Info -->
      <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 rounded-lg p-4">
        <h5 class="font-semibold text-blue-900 mb-2 flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          Teacher Information
        </h5>
        <div class="grid grid-cols-2 gap-2 text-sm">
          <div><span class="text-gray-600">Name:</span> <span class="font-medium">${teacherName}</span></div>
          <div><span class="text-gray-600">ID:</span> <span class="font-medium">${teacherId}</span></div>
          <div><span class="text-gray-600">Assigned Sections:</span> <span class="font-medium">${sections.length}</span></div>
          <div><span class="text-gray-600">Total Students:</span> <span class="font-medium">${students.length}</span></div>
        </div>
      </div>

      <!-- Assigned Sections Summary -->
      ${sections.length > 0 ? `
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
          <p class="text-sm font-semibold text-purple-800 mb-2">📚 Assigned Sections:</p>
          <div class="flex flex-wrap gap-2">
            ${sections.map(s => `
              <span class="bg-purple-200 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                ${s.section_name} (${s.grade_level})
              </span>
            `).join('')}
          </div>
        </div>
      ` : `
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-center">
          <p class="text-sm text-yellow-800">⚠️ No sections assigned. Please assign sections through schedules first.</p>
        </div>
      `}

      <!-- Currently Assigned Students -->
      <div>
        <h5 class="font-semibold text-gray-800 mb-3 flex items-center justify-between">
          <span>
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Currently Assigned Students (${assignedStudents.length})
          </span>
          ${assignedStudents.length > 0 ? `
            <button onclick="removeAllTeacherStudents('${teacherId}', '${teacherName.replace(/'/g, "\\'")}'); return false;" 
                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
              Remove All
            </button>
          ` : ''}
        </h5>
        
        ${assignedStudents.length > 0 ? `
          <div class="bg-green-50 border border-green-200 rounded-lg max-h-48 overflow-y-auto">
            <table class="w-full text-sm">
              <thead class="bg-green-100 sticky top-0">
                <tr>
                  <th class="px-3 py-2 text-left text-green-800">Student ID</th>
                  <th class="px-3 py-2 text-left text-green-800">Name</th>
                  <th class="px-3 py-2 text-left text-green-800">Section</th>
                  <th class="px-3 py-2 text-center text-green-800">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-green-200">
                ${assignedStudents.map(s => `
                  <tr class="hover:bg-green-100">
                    <td class="px-3 py-2">${s.student_id}</td>
                    <td class="px-3 py-2">${s.student_name}</td>
                    <td class="px-3 py-2">
                      <span class="bg-green-200 text-green-800 px-2 py-0.5 rounded-full text-xs">
                        ${s.section_name || 'N/A'}
                      </span>
                    </td>
                    <td class="px-3 py-2 text-center">
                      <button onclick="removeStudentFromTeacher('${teacherId}', '${s.student_id}', '${teacherName.replace(/'/g, "\\'")}'); return false;" 
                              class="text-red-600 hover:text-red-800 p-1">
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
        ` : `
          <p class="text-gray-500 italic text-center py-4 bg-gray-50 rounded-lg">No students assigned yet</p>
        `}
      </div>

      <!-- Available Students by Section -->
      ${sections.length > 0 && students.length > 0 ? `
        <div>
          <h5 class="font-semibold text-gray-800 mb-3">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Assign Students from Sections
          </h5>
          
          <div class="space-y-3 max-h-96 overflow-y-auto">
            ${Object.keys(studentsBySection).map(sectionName => {
              const sectionStudents = studentsBySection[sectionName];
              const unassignedCount = sectionStudents.filter(s => !assignedIds.includes(s.student_id)).length;
              
              return `
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                  <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2 flex justify-between items-center">
                    <span class="text-white font-semibold">${sectionName}</span>
                    <div class="flex items-center gap-2">
                      <span class="text-white text-xs bg-white bg-opacity-20 px-2 py-1 rounded">
                        ${unassignedCount} available
                      </span>
                      ${unassignedCount > 0 ? `
                        <button onclick="assignAllStudentsFromSection('${teacherId}', '${sectionName.replace(/'/g, "\\'")}'); return false;" 
                                class="text-xs bg-white text-blue-600 hover:bg-blue-50 px-3 py-1 rounded font-medium">
                          Assign All
                        </button>
                      ` : ''}
                    </div>
                  </div>
                  <div class="p-3 bg-gray-50">
                    ${sectionStudents.length > 0 ? `
                      <div class="grid grid-cols-1 gap-2 max-h-40 overflow-y-auto">
                        ${sectionStudents.map(student => {
                          const isAssigned = assignedIds.includes(student.student_id);
                          return `
                            <div class="flex items-center justify-between p-2 bg-white border rounded ${isAssigned ? 'border-green-300 bg-green-50' : 'border-gray-200'}">
                              <div class="flex items-center gap-2 flex-1">
                                <input type="checkbox" 
                                       id="student_${student.student_id}" 
                                       value="${student.student_id}"
                                       ${isAssigned ? 'checked disabled' : ''}
                                       class="w-4 h-4 text-blue-600 rounded">
                                <label for="student_${student.student_id}" class="text-sm ${isAssigned ? 'text-gray-500' : 'text-gray-700'} flex-1">
                                  <span class="font-medium">${student.student_id}</span> - ${student.student_name}
                                </label>
                              </div>
                              ${isAssigned ? `
                                <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded-full">Assigned</span>
                              ` : `
                                <button onclick="assignSingleStudent('${teacherId}', '${student.student_id}', '${teacherName.replace(/'/g, "\\'")}'); return false;" 
                                        class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                  Assign
                                </button>
                              `}
                            </div>
                          `;
                        }).join('')}
                      </div>
                    ` : '<p class="text-gray-500 text-sm text-center">No students in this section</p>'}
                  </div>
                </div>
              `;
            }).join('')}
          </div>

          <!-- Bulk Assignment Controls -->
          <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
            <button onclick="assignSelectedStudents('${teacherId}', '${teacherName.replace(/'/g, "\\'")}'); return false;" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Assign Selected Students
            </button>
          </div>
        </div>
      ` : `
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
          <p class="text-yellow-800">No students available. Please ensure sections have enrolled students.</p>
        </div>
      `}

      <!-- Close Button -->
      <div class="flex gap-2 pt-2">
        <button onclick="closeAssignStudentsModal(); return false;" 
                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg font-medium">
          Close
        </button>
      </div>
    </div>
  `;
  
  showModal(`
    <div class="flex items-center">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
      </svg>
      Assign Students to ${teacherName}
    </div>
  `, modalContent);
}

// Assign a single student
function assignSingleStudent(teacherId, studentId, teacherName) {
  fetch('api/admin-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=assignStudentToTeacher&teacher_id=${teacherId}&student_id=${studentId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showNotification('Success!', 'Student assigned successfully!', 'success');
      closeModal();
      if (typeof loadTeacherAccounts === 'function') {
        setTimeout(() => loadTeacherAccounts(), 500);
      }
    } else {
      showNotification('Error', data.message, 'error');
    }
  })
  .catch(err => {
    console.error('Error:', err);
    showNotification('Error', 'Failed to assign student', 'error');
  });
}

// Assign selected students (bulk)
function assignSelectedStudents(teacherId, teacherName) {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked:not([disabled])');
  const studentIds = Array.from(checkboxes).map(cb => cb.value);
  
  if (studentIds.length === 0) {
    showNotification('Warning', 'Please select at least one student', 'warning');
    return;
  }
  
  fetch('api/admin-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=assignMultipleStudentsToTeacher&teacher_id=${teacherId}&student_ids=${studentIds.join(',')}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showNotification('Success!', data.message, 'success');
      closeModal();
      if (typeof loadTeacherAccounts === 'function') {
        setTimeout(() => loadTeacherAccounts(), 500);
      }
    } else {
      showNotification('Error', data.message, 'error');
    }
  })
  .catch(err => {
    console.error('Error:', err);
    showNotification('Error', 'Failed to assign students', 'error');
  });
}

// Assign all students from a section
function assignAllStudentsFromSection(teacherId, sectionName) {
  showConfirmation(
    'Confirm Assignment',
    `Assign all students from ${sectionName}?`,
    function() {
      fetch('api/admin-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=assignSectionStudentsToTeacher&teacher_id=${teacherId}&section_name=${encodeURIComponent(sectionName)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showNotification('Success!', data.message, 'success');
          closeModal();
          if (typeof loadTeacherAccounts === 'function') {
            setTimeout(() => loadTeacherAccounts(), 500);
          }
        } else {
          showNotification('Error', data.message, 'error');
        }
      })
      .catch(err => {
        console.error('Error:', err);
        showNotification('Error', 'Failed to assign students', 'error');
      });
    }
  );
}

// Remove a single student from teacher
function removeStudentFromTeacher(teacherId, studentId, teacherName) {
  showConfirmation(
    'Remove Assignment',
    'Remove this student assignment?',
    function() {
      fetch('api/admin-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=removeStudentFromTeacher&teacher_id=${teacherId}&student_id=${studentId}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showNotification('Success!', 'Student removed successfully!', 'success');
          closeModal();
          if (typeof loadTeacherAccounts === 'function') {
            setTimeout(() => loadTeacherAccounts(), 500);
          }
        } else {
          showNotification('Error', data.message, 'error');
        }
      })
      .catch(err => {
        console.error('Error:', err);
        showNotification('Error', 'Failed to remove student', 'error');
      });
    }
  );
}

// Remove all students from teacher
function removeAllTeacherStudents(teacherId, teacherName) {
  showConfirmation(
    'Remove All Assignments',
    `Remove ALL students from ${teacherName}?`,
    function() {
      fetch('api/admin-api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=removeAllTeacherStudents&teacher_id=${teacherId}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showNotification('Success!', data.message, 'success');
          closeModal();
          if (typeof loadTeacherAccounts === 'function') {
            setTimeout(() => loadTeacherAccounts(), 500);
          }
        } else {
          showNotification('Error', data.message, 'error');
        }
      })
      .catch(err => {
        console.error('Error:', err);
        showNotification('Error', 'Failed to remove students', 'error');
      });
    }
  );
}

// Close assign students modal
function closeAssignStudentsModal() {
  closeModal();
}

// Update the loadTeacherAccounts function to include the Assign Students button
// Replace your existing loadTeacherAccounts function with this updated version:

function loadTeacherAccounts() {
  fetch('api/admin-api.php?action=getTeacherAccounts')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('teacherAccountsList');
      if (data.success && data.teachers.length > 0) {
        tbody.innerHTML = data.teachers.map(teacher => `
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2 border">${teacher.teacher_id}</td>
            <td class="px-3 py-2 border">${teacher.teacher_name}</td>
            <td class="px-3 py-2 border">${teacher.username || 'N/A'}</td>
            <td class="px-3 py-2 border">
              <span class="px-2 py-1 text-xs rounded ${teacher.username ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                ${teacher.username ? 'Active' : 'No Account'}
              </span>
            </td>
            <td class="px-3 py-2 border text-center">
              <button onclick="assignStudentsToTeacher('${teacher.teacher_id}', '${teacher.teacher_name.replace(/'/g, "\\'")}'); return false;" 
                      class="bg-purple-500 hover:bg-purple-600 text-white px-2 py-1 rounded text-xs mr-1"
                      title="Assign Students">
                👥 Students
              </button>
              <button onclick="viewTeacher('${teacher.teacher_id}')" 
                      class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-1">
                View
              </button>
              <button onclick="editTeacher('${teacher.teacher_id}')" 
                      class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm mr-1">
                Edit
              </button>
              <button onclick="deleteTeacher('${teacher.teacher_id}')" 
                      class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                Delete
              </button>
            </td>
          </tr>
        `).join('');
      } else {
        tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-2 text-center text-gray-400">No teacher accounts found</td></tr>';
      }
    })
    .catch(err => console.error('Error loading teachers:', err));
}