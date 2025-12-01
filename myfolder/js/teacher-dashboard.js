// ========================================
// TEACHER DASHBOARD - GRADE MANAGEMENT
// ========================================

const teacherId = document.currentScript?.getAttribute('data-teacher-id') || 
                  document.querySelector('script[data-teacher-id]')?.getAttribute('data-teacher-id') ||
                  window.teacherId;

// ========================================
// MODERN MODAL SYSTEM
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
  
  setTimeout(() => {
    const modal = document.getElementById('notificationModal');
    if (modal) {
      modal.classList.add('animate-fadeOut');
      setTimeout(() => closeNotification(), 300);
    }
  }, 3000);
}

function closeNotification() {
  const modal = document.getElementById('notificationModal');
  if (modal) modal.remove();
}

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
// SECTION SWITCHING
// ========================================

function showSection(id, btn) {
  document.querySelectorAll("main section").forEach(sec => sec.classList.add("hidden"));
  document.getElementById(id).classList.remove("hidden");

  document.querySelectorAll(".nav-btn").forEach(b => {
    b.classList.remove("bg-white", "bg-opacity-20", "font-semibold");
  });
  
  if (btn) {
    btn.classList.add("bg-white", "bg-opacity-20", "font-semibold");
  }

  if (id === 'mySchedule') {
    loadMySchedule();
  } else if (id === 'gradeEncode') {
    loadTeacherSections();
    loadTeacherSubjects();
  } else if (id === 'viewGrades') {
    loadViewGradeDropdowns();
  } else if (id === 'myStudents') {
    loadAssignedSectionsPage();
  } else if (id === 'archivedStudents') {
    loadArchivedStudents();
  }
}

// ========================================
// LOAD ASSIGNED SECTIONS (FOR MY STUDENTS PAGE)
// ========================================

function loadAssignedSectionsPage() {
  Promise.all([
    fetch('api/teacher-api.php?action=getAssignedSections').then(r => r.json()),
    fetch('api/teacher-api.php?action=getAssignedStudents').then(r => r.json())
  ])
  .then(([sectionsData, studentsData]) => {
    if (sectionsData.success && studentsData.success) {
      displaySectionsGrid(sectionsData.sections, studentsData.grouped);
      updateStudentStats(studentsData);
    }
  })
  .catch(error => {
    console.error('Error loading assigned sections:', error);
    document.getElementById('sectionsGrid').innerHTML = `
      <div class="col-span-full text-center py-8">
        <svg class="w-16 h-16 mx-auto text-red-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-gray-500">Failed to load sections</p>
      </div>
    `;
  });
}

function updateStudentStats(data) {
  document.getElementById('totalStudentsCount').textContent = data.total || 0;
  document.getElementById('totalSectionsCount').textContent = data.grouped ? data.grouped.length : 0;
  
  // Calculate unique grade levels
  const gradeLevels = data.grouped ? new Set(data.grouped.map(g => g.grade_level)) : new Set();
  document.getElementById('totalGradeLevelsCount').textContent = gradeLevels.size;
}

function displaySectionsGrid(sections, groupedStudents) {
  const grid = document.getElementById('sectionsGrid');
  
  if (!sections || sections.length === 0) {
    grid.innerHTML = `
      <div class="col-span-full text-center py-12">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
        <p class="text-gray-400 font-semibold text-lg">No sections assigned yet</p>
        <p class="text-gray-400 text-sm mt-2">Contact your administrator to assign sections and students</p>
      </div>
    `;
    return;
  }
  
  grid.innerHTML = sections.map(section => `
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 cursor-pointer transform hover:scale-105"
         onclick="viewSectionDetailsPage('${section.section_id}', '${section.section_name}')">
      <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
          <h5 class="text-xl font-bold text-blue-900">${section.section_name}</h5>
          <p class="text-blue-600 text-sm font-medium">${section.grade_level}</p>
        </div>
        <div class="bg-blue-500 text-white rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg shadow-md">
          ${section.student_count}
        </div>
      </div>
      <div class="flex items-center text-blue-700 text-sm font-medium">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        <span>Click to view students</span>
      </div>
    </div>
  `).join('');
}

function viewSectionDetailsPage(sectionId, sectionName) {
  viewSectionDetails(sectionId, sectionName);
}

// ========================================
// LOAD ASSIGNED SECTIONS (SIDEBAR DISPLAY) - REMOVED
// This is no longer needed since we have a dedicated page
// ========================================

// ========================================
// VIEW SECTION DETAILS MODAL
// ========================================

function viewSectionDetails(sectionId, sectionName) {
  // Use a simpler API call to get students by section
  fetch('api/teacher-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=getStudentsBySection&section_id=${sectionId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showSectionModal(sectionName, data.students);
    } else {
      showNotification('Error', data.message || 'Failed to load students', 'error');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showNotification('Error', 'Failed to load section details', 'error');
  });
}

function showSectionModal(sectionName, students) {
  const modal = document.createElement('div');
  modal.id = 'sectionModal';
  modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn';
  
  const studentCount = students ? students.length : 0;
  
  modal.innerHTML = `
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto animate-scaleIn">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-t-xl">
        <div class="flex justify-between items-center">
          <h3 class="text-xl font-bold flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            ${sectionName} - Student List
          </h3>
          <button onclick="closeSectionModal()" class="text-white hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
      <div class="p-6">
        <div class="mb-4 flex items-center justify-between">
          <p class="text-gray-600 text-sm">Total Students: <span class="font-bold text-blue-600">${studentCount}</span></p>
          <div class="text-xs text-gray-500">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Students assigned by admin
          </div>
        </div>
        ${studentCount > 0 ? `
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
              <thead class="bg-blue-100">
                <tr>
                  <th class="px-4 py-3 border text-left font-semibold text-blue-800">Student ID</th>
                  <th class="px-4 py-3 border text-left font-semibold text-blue-800">Student Name</th>
                </tr>
              </thead>
              <tbody>
                ${students.map((student, index) => `
                  <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} hover:bg-blue-50 transition-colors">
                    <td class="px-4 py-3 border font-medium text-gray-900">${student.student_id}</td>
                    <td class="px-4 py-3 border text-gray-700">${student.student_name}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        ` : `
          <div class="text-center py-8">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-400 font-medium">No students assigned in this section</p>
            <p class="text-gray-400 text-sm mt-1">Contact admin to assign students</p>
          </div>
        `}
      </div>
      <div class="bg-gray-50 px-6 py-3 rounded-b-xl flex justify-end">
        <button onclick="closeSectionModal()" 
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
          Close
        </button>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function closeSectionModal() {
  const modal = document.getElementById('sectionModal');
  if (modal) modal.remove();
}

// ========================================
// LOAD TEACHER DATA
// ========================================

function loadTeacherSections() {
  fetch('api/teacher-api.php?action=getTeacherSections')
    .then(res => res.json())
    .then(data => {
      const gradeFilter = document.getElementById('gradeFilter');
      const sectionFilter = document.getElementById('sectionFilter');
      
      if (data.success && data.sections) {
        const gradeLevels = [...new Set(data.sections.map(s => s.grade_level))];
        gradeFilter.innerHTML = '<option value="">Select Grade Level</option>' +
          gradeLevels.map(g => `<option value="${g}">${g}</option>`).join('');
        
        gradeFilter.addEventListener('change', function() {
          const selectedGrade = this.value;
          const filteredSections = data.sections.filter(s => s.grade_level === selectedGrade);
          sectionFilter.innerHTML = '<option value="">Select Section</option>' +
            filteredSections.map(s => `<option value="${s.section_id}">${s.section_name}</option>`).join('');
        });
      }
    })
    .catch(err => console.error('Error loading sections:', err));
}

function loadTeacherSubjects() {
  fetch('api/teacher-api.php?action=getTeacherSubjects')
    .then(res => res.json())
    .then(data => {
      const subjectFilter = document.getElementById('subjectFilter');
      if (data.success && data.subjects) {
        subjectFilter.innerHTML = '<option value="">Select Subject</option>' +
          data.subjects.map(s => `<option value="${s.subject_code}">${s.subject_name}</option>`).join('');
      }
    })
    .catch(err => console.error('Error loading subjects:', err));
}

// ========================================
// FILTER STUDENTS
// ========================================

function filterStudents() {
  const gradeLevel = document.getElementById('gradeFilter').value;
  const sectionId = document.getElementById('sectionFilter').value;
  const subjectCode = document.getElementById('subjectFilter').value;
  const period = document.getElementById('periodFilter').value;
  
  if (!gradeLevel || !sectionId || !subjectCode) {
    showNotification('Warning', 'Please select grade level, section, and subject.', 'warning');
    return;
  }
  
  fetch('api/teacher-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=getStudentsForGrading&section_id=${sectionId}&subject_code=${subjectCode}&grading_period=${period}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success && data.students) {
      if (data.students.length === 0) {
        showNotification('No Students', 'No students assigned to you in this section.', 'info');
        document.getElementById('studentTableContainer').classList.add('hidden');
      } else {
        displayStudentsTable(data.students, subjectCode, period);
        document.getElementById('studentTableContainer').classList.remove('hidden');
      }
    } else {
      showNotification('Error', data.message || 'Failed to load students', 'error');
    }
  })
  .catch(err => {
    console.error('Error loading students:', err);
    showNotification('Error', 'Failed to load students. Please try again.', 'error');
  });
}

function displayStudentsTable(students, subjectCode, period) {
  const tbody = document.getElementById('studentTable');
  tbody.innerHTML = students.map(student => `
    <tr class="hover:bg-gray-50 transition-colors">
      <td class="px-4 py-3 border font-medium text-gray-900">${student.student_id}</td>
      <td class="px-4 py-3 border font-medium text-gray-700">${student.student_name}</td>
      <td class="px-4 py-3 border text-center">
        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold ${student.current_grade ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-500'}">
          ${student.current_grade || 'No grade'}
        </span>
      </td>
      <td class="px-4 py-3 border">
        <input type="number" 
               id="grade_${student.student_id}" 
               value="${student.current_grade || ''}"
               min="0" 
               max="100" 
               step="0.01"
               placeholder="Enter grade"
               class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none transition-colors">
      </td>
      <td class="px-4 py-3 border text-center">
        <button onclick="saveGrade('${student.student_id}', '${subjectCode}', '${period}')" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-all transform hover:scale-105">
          Save
        </button>
      </td>
    </tr>
  `).join('');
}

// ========================================
// SAVE GRADE
// ========================================

function saveGrade(studentId, subjectCode, gradingPeriod) {
  const gradeInput = document.getElementById(`grade_${studentId}`);
  const grade = gradeInput.value;
  
  if (!grade || grade === '') {
    showNotification('Warning', 'Please enter a grade.', 'warning');
    return;
  }
  
  if (grade < 0 || grade > 100) {
    showNotification('Invalid Grade', 'Grade must be between 0 and 100.', 'error');
    return;
  }
  
  fetch('api/teacher-api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=saveGrade&student_id=${studentId}&subject_code=${subjectCode}&grading_period=${gradingPeriod}&grade=${grade}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showNotification('Success!', 'Grade saved successfully', 'success');
      // Refresh the student list to show updated grade
      filterStudents();
    } else {
      showNotification('Error', data.message || 'Failed to save grade', 'error');
    }
  })
  .catch(err => {
    console.error('Error saving grade:', err);
    showNotification('Error', 'Failed to save grade. Please try again.', 'error');
  });
}

// ========================================
// VIEW GRADES
// ========================================

function loadViewGradeDropdowns() {
  const viewSectionFilter = document.getElementById('viewSectionFilter');
  const viewSubjectFilter = document.getElementById('viewSubjectFilter');
  
  fetch('api/teacher-api.php?action=getTeacherSections')
    .then(res => res.json())
    .then(data => {
      if (data.success && data.sections) {
        viewSectionFilter.innerHTML = '<option value="">Select Section</option>' +
          data.sections.map(s => `<option value="${s.section_id}">${s.section_name} (${s.grade_level})</option>`).join('');
      }
    })
    .catch(err => console.error('Error loading sections:', err));
  
  fetch('api/teacher-api.php?action=getTeacherSubjects')
    .then(res => res.json())
    .then(data => {
      if (data.success && data.subjects) {
        viewSubjectFilter.innerHTML = '<option value="">Select Subject</option>' +
          data.subjects.map(s => `<option value="${s.subject_code}">${s.subject_name}</option>`).join('');
      }
    })
    .catch(err => console.error('Error loading subjects:', err));
}

function viewGradesTable() {
  const sectionId = document.getElementById('viewSectionFilter').value;
  const subjectCode = document.getElementById('viewSubjectFilter').value;
  
  if (!sectionId || !subjectCode) {
    showNotification('Warning', 'Please select section and subject.', 'warning');
    return;
  }
  
  fetch(`api/teacher-api.php?action=getGradesReport&section_id=${sectionId}&subject_code=${subjectCode}`)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.grades) {
        displayGradesReport(data.grades);
        document.getElementById('gradesTableContainer').classList.remove('hidden');
      } else {
        showNotification('No Grades', 'No grades found for this section and subject.', 'info');
      }
    })
    .catch(err => {
      console.error('Error loading grades:', err);
      showNotification('Error', 'Failed to load grades report.', 'error');
    });
}

function displayGradesReport(grades) {
  const tbody = document.getElementById('gradesTableBody');
  tbody.innerHTML = grades.map((student, index) => {
    const studentGrades = student.grades;
    const avg = student.average;
    
    return `
      <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} hover:bg-blue-50 transition-colors">
        <td class="px-3 py-3 border font-medium text-gray-900">${student.student_name}</td>
        <td class="px-3 py-3 border text-center">${studentGrades['1st'] || '-'}</td>
        <td class="px-3 py-3 border text-center">${studentGrades['2nd'] || '-'}</td>
        <td class="px-3 py-3 border text-center">${studentGrades['3rd'] || '-'}</td>
        <td class="px-3 py-3 border text-center">${studentGrades['4th'] || '-'}</td>
        <td class="px-3 py-3 border text-center font-bold ${avg >= 75 ? 'text-green-600' : avg ? 'text-red-600' : 'text-gray-400'}">
          ${avg || '-'}
        </td>
      </tr>
    `;
  }).join('');
}

// ========================================
// MY SCHEDULE
// ========================================

function loadMySchedule() {
  fetch('api/teacher-api.php?action=getMySchedule')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('scheduleTableBody');
      if (data.success && data.schedules && data.schedules.length > 0) {
        tbody.innerHTML = data.schedules.map((schedule, index) => {
          const datetime = new Date(schedule.day_time);
          const dayTime = datetime.toLocaleDateString('en-US', { weekday: 'long' }) + ', ' +
                         datetime.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
          
          return `
            <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} hover:bg-blue-50 transition-colors">
              <td class="px-4 py-3 border font-medium text-gray-900">${schedule.subject_name}</td>
              <td class="px-4 py-3 border text-gray-700">${schedule.section_name}</td>
              <td class="px-4 py-3 border text-gray-700">${dayTime}</td>
              <td class="px-4 py-3 border text-gray-700">Room ${schedule.room_number}</td>
            </tr>
          `;
        }).join('');
      } else {
        tbody.innerHTML = `
          <tr>
            <td colspan="4" class="px-4 py-8 text-center">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <p class="text-gray-400 font-medium">No schedule assigned yet</p>
              <p class="text-gray-400 text-sm mt-1">Contact admin for schedule assignment</p>
            </td>
          </tr>
        `;
      }
    })
    .catch(err => {
      console.error('Error loading schedule:', err);
      document.getElementById('scheduleTableBody').innerHTML = 
        '<tr><td colspan="4" class="px-4 py-3 text-center text-red-500">Error loading schedule</td></tr>';
    });
}

// ========================================
// ARCHIVED STUDENTS FUNCTIONS
// ========================================

function loadArchivedStudents() {
  const status = document.getElementById('archiveStatusFilter')?.value || 'all';
  const schoolYear = document.getElementById('archiveYearFilter')?.value || 'all';
  const search = document.getElementById('archiveSearchInput')?.value || '';
  
  // Show loading state
  const tbody = document.getElementById('archivedStudentsTable');
  tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-8 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="text-gray-500 mt-2">Loading archived students...</p></td></tr>';
  
  const url = `api/teacher-api.php?action=getTeacherArchivedStudents&status=${status}&school_year=${schoolYear}&search=${encodeURIComponent(search)}`;
  
  console.log('Fetching archived students from:', url);
  
  fetch(url)
    .then(res => {
      console.log('Response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Archive data received:', data);
      if (data.success) {
        displayArchivedStudents(data.students);
        updateArchiveStats(data.stats, data.schoolYears);
        populateSchoolYearsFilter(data.schoolYears);
      } else {
        console.error('API returned error:', data.message);
        tbody.innerHTML = `
          <tr>
            <td colspan="8" class="px-4 py-8 text-center">
              <svg class="w-12 h-12 mx-auto text-red-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-red-500 font-medium">Error loading data</p>
              <p class="text-gray-500 text-sm">${data.message || 'Unknown error'}</p>
            </td>
          </tr>
        `;
        showNotification('Error', data.message || 'Failed to load archived students', 'error');
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
      tbody.innerHTML = `
        <tr>
          <td colspan="8" class="px-4 py-8 text-center">
            <svg class="w-12 h-12 mx-auto text-red-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-500 font-medium">Connection Error</p>
            <p class="text-gray-500 text-sm">${err.message}</p>
          </td>
        </tr>
      `;
      showNotification('Error', 'Failed to connect to server', 'error');
    });
}

function updateArchiveStats(stats, schoolYears) {
  const total = Object.values(stats).reduce((sum, count) => sum + parseInt(count), 0);
  document.getElementById('totalArchivedCount').textContent = total || 0;
  document.getElementById('graduatedCount').textContent = stats.graduated || 0;
  document.getElementById('transferredCount').textContent = stats.transferred || 0;
  document.getElementById('schoolYearsCount').textContent = schoolYears ? schoolYears.length : 0;
}

function populateSchoolYearsFilter(schoolYears) {
  const select = document.getElementById('archiveYearFilter');
  if (!select) return;
  
  const currentValue = select.value;
  select.innerHTML = '<option value="all">All Years</option>';
  
  if (schoolYears && schoolYears.length > 0) {
    schoolYears.forEach(year => {
      select.innerHTML += `<option value="${year}">${year}</option>`;
    });
  }
  
  select.value = currentValue;
}

function displayArchivedStudents(students) {
  const tbody = document.getElementById('archivedStudentsTable');
  
  if (!students || students.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="8" class="px-4 py-12 text-center">
          <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
          </svg>
          <p class="text-gray-400 font-medium">No archived students found</p>
          <p class="text-gray-400 text-sm mt-1">Students you taught who graduated or transferred will appear here</p>
        </td>
      </tr>
    `;
    return;
  }
  
  tbody.innerHTML = students.map((s, index) => {
    const statusColors = {
      graduated: 'bg-green-100 text-green-800',
      transferred: 'bg-yellow-100 text-yellow-800',
      archived: 'bg-gray-100 text-gray-800'
    };
    
    const statusIcons = {
      graduated: `<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
      </svg>`,
      transferred: `<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
      </svg>`,
      archived: `<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
      </svg>`
    };
    
    return `
      <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} hover:bg-gray-100 transition-colors">
        <td class="px-4 py-3 border font-medium text-gray-900">${s.student_id}</td>
        <td class="px-4 py-3 border text-gray-700">${s.student_name}</td>
        <td class="px-4 py-3 border text-gray-700">${s.grade_level}</td>
        <td class="px-4 py-3 border text-gray-700">${s.section_name || 'N/A'}</td>
        <td class="px-4 py-3 border">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${statusColors[s.student_status] || statusColors.archived}">
            ${statusIcons[s.student_status] || statusIcons.archived}
            ${s.student_status.charAt(0).toUpperCase() + s.student_status.slice(1)}
          </span>
        </td>
        <td class="px-4 py-3 border text-gray-700">${s.school_year || 'N/A'}</td>
        <td class="px-4 py-3 border text-gray-700 text-sm">${s.archive_date ? new Date(s.archive_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A'}</td>
        <td class="px-4 py-3 border text-center">
          <button onclick="viewArchivedStudentDetails('${s.student_id}')" 
                  class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors" 
                  title="View Details">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
          </button>
        </td>
      </tr>
    `;
  }).join('');
}

function viewArchivedStudentDetails(studentId) {
  fetch(`api/teacher-api.php?action=getArchivedStudentDetails&student_id=${encodeURIComponent(studentId)}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        showArchivedStudentModal(data.student, data.grades);
      } else {
        showNotification('Error', data.message || 'Failed to load student details', 'error');
      }
    })
    .catch(err => {
      console.error('Error:', err);
      showNotification('Error', 'Failed to load student details', 'error');
    });
}

function showArchivedStudentModal(student, grades) {
  const statusBadges = {
    graduated: '<span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">🎓 Graduated</span>',
    transferred: '<span class="px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">➡️ Transferred</span>',
    archived: '<span class="px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">📦 Archived</span>'
  };
  
  // Group grades by subject
  const gradesBySubject = {};
  grades.forEach(g => {
    if (!gradesBySubject[g.subject_name]) {
      gradesBySubject[g.subject_name] = {};
    }
    gradesBySubject[g.subject_name][g.grading_period] = g.grade_score;
  });
  
  const content = `
    <div class="space-y-6">
      <!-- Student Header -->
      <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
        <div class="flex items-center space-x-4 mb-4">
          <div class="w-20 h-20 bg-gray-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
            ${student.student_name.charAt(0).toUpperCase()}
          </div>
          <div>
            <h3 class="text-2xl font-bold text-gray-900">${student.student_name}</h3>
            <p class="text-gray-600 text-sm">Student ID: <span class="font-semibold">${student.student_id}</span></p>
            <div class="mt-2">${statusBadges[student.student_status] || statusBadges.archived}</div>
          </div>
        </div>
        ${student.archive_reason ? `
          <div class="bg-white rounded-lg p-3 border-l-4 border-gray-400">
            <p class="text-sm text-gray-600"><strong>Reason:</strong> ${student.archive_reason}</p>
          </div>
        ` : ''}
      </div>

      <!-- Student Info Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white border rounded-lg p-4">
          <h4 class="font-semibold text-gray-700 mb-3 flex items-center border-b pb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Academic Information
          </h4>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between py-1 border-b border-gray-100">
              <span class="text-gray-600">Grade Level</span>
              <span class="font-semibold">${student.grade_level}</span>
            </div>
            <div class="flex justify-between py-1 border-b border-gray-100">
              <span class="text-gray-600">Section</span>
              <span class="font-semibold">${student.section_name || 'N/A'}</span>
            </div>
            <div class="flex justify-between py-1">
              <span class="text-gray-600">School Year</span>
              <span class="font-semibold">${student.school_year || 'N/A'}</span>
            </div>
          </div>
        </div>

        <div class="bg-white border rounded-lg p-4">
          <h4 class="font-semibold text-gray-700 mb-3 flex items-center border-b pb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Archive Information
          </h4>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between py-1 border-b border-gray-100">
              <span class="text-gray-600">Archive Date</span>
              <span class="font-semibold">${student.archive_date ? new Date(student.archive_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</span>
            </div>
            <div class="flex justify-between py-1">
              <span class="text-gray-600">Contact</span>
              <span class="font-semibold">${student.contact_number || 'N/A'}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Grades Section -->
      ${Object.keys(gradesBySubject).length > 0 ? `
        <div class="bg-white border rounded-lg p-5">
          <h4 class="font-semibold text-gray-700 mb-4 flex items-center border-b pb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            Academic Record
          </h4>
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 py-2 text-left font-semibold text-gray-700">Subject</th>
                  <th class="px-3 py-2 text-center font-semibold text-gray-700">1st</th>
                  <th class="px-3 py-2 text-center font-semibold text-gray-700">2nd</th>
                  <th class="px-3 py-2 text-center font-semibold text-gray-700">3rd</th>
                  <th class="px-3 py-2 text-center font-semibold text-gray-700">4th</th>
                  <th class="px-3 py-2 text-center font-semibold text-gray-700">Average</th>
                </tr>
              </thead>
              <tbody class="divide-y">
                ${Object.keys(gradesBySubject).map(subject => {
                  const subjectGrades = gradesBySubject[subject];
                  const grades = ['1st', '2nd', '3rd', '4th'].map(q => subjectGrades[q] || '-');
                  const numericGrades = grades.filter(g => g !== '-').map(g => parseFloat(g));
                  const average = numericGrades.length > 0 
                    ? (numericGrades.reduce((a, b) => a + b, 0) / numericGrades.length).toFixed(2)
                    : '-';
                  
                  return `
                    <tr class="hover:bg-gray-50">
                      <td class="px-3 py-2 font-medium">${subject}</td>
                      <td class="px-3 py-2 text-center">${grades[0]}</td>
                      <td class="px-3 py-2 text-center">${grades[1]}</td>
                      <td class="px-3 py-2 text-center">${grades[2]}</td>
                      <td class="px-3 py-2 text-center">${grades[3]}</td>
                      <td class="px-3 py-2 text-center font-bold ${average !== '-' && parseFloat(average) >= 75 ? 'text-green-600' : 'text-red-600'}">${average}</td>
                    </tr>
                  `;
                }).join('')}
              </tbody>
            </table>
          </div>
        </div>
      ` : `
        <div class="bg-gray-50 border rounded-lg p-8 text-center">
          <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <p class="text-gray-500">No grades recorded for this student</p>
        </div>
      `}
    </div>
  `;
  
  showModernModal(`Archived Student - ${student.student_name}`, content, 'large');
}

function exportArchivedStudentsTeacher() {
  const students = Array.from(document.querySelectorAll('#archivedStudentsTable tr'))
    .slice(0, -1) // Remove loading row if exists
    .map(row => {
      const cells = row.querySelectorAll('td');
      if (cells.length < 7) return null;
      return {
        id: cells[0].textContent,
        name: cells[1].textContent,
        grade: cells[2].textContent,
        section: cells[3].textContent,
        status: cells[4].textContent.trim(),
        year: cells[5].textContent,
        date: cells[6].textContent
      };
    })
    .filter(s => s !== null);
  
  if (students.length === 0) {
    showNotification('No Data', 'No students to export', 'warning');
    return;
  }
  
  let csv = 'Student ID,Name,Grade Level,Section,Status,School Year,Archive Date\n';
  students.forEach(s => {
    csv += `"${s.id}","${s.name}","${s.grade}","${s.section}","${s.status}","${s.year}","${s.date}"\n`;
  });
  
  const blob = new Blob([csv], { type: 'text/csv' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `archived_students_${new Date().toISOString().split('T')[0]}.csv`;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

// ========================================
// INITIALIZE
// ========================================

document.addEventListener('DOMContentLoaded', () => {
  // Load initial data
  loadTeacherSections();
  loadTeacherSubjects();
  
  console.log('Teacher Dashboard initialized');
});