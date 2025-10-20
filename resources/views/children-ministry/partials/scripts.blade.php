<script>
// View Switching
function switchView(view) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');

    if (view === 'grid') {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridBtn.classList.add('bg-white/10');
        listBtn.classList.remove('bg-white/10');
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listBtn.classList.add('bg-white/10');
        gridBtn.classList.remove('bg-white/10');
    }
}

// Filtering Functions
function filterByAge(age) {
    console.log('Filtering by age group: ' + age);
    alert('Filtering children by age group: ' + age + '\n\nThis will show only children in this age range.');
}

function filterByClass(classGroup) {
    console.log('Filtering by class: ' + classGroup);
}

function filterByGender(gender) {
    console.log('Filtering by gender: ' + gender);
}

function sortChildren(sortBy) {
    console.log('Sorting by: ' + sortBy);
}

function searchChildren(query) {
    console.log('Searching: ' + query);
}

// Attendance Functions
function showAttendanceTracker() {
    alert('📋 Attendance Tracker:\n\n• Weekly attendance reports\n• Automated check-in/check-out\n• Parent pickup verification\n• Absence notifications\n• Attendance trends\n\nFeature coming soon!');
}

function quickAttendance() {
    alert('⚡ Quick Check-in:\n\n• Scan QR codes\n• Bulk check-in by class\n• Mobile-friendly interface\n• Instant parent notifications\n\nFeature coming soon!');
}

function markAttendance(childId) {
    if (confirm('Mark this child as present for today?')) {
        alert('✅ Attendance Marked!\n\nChild has been checked in successfully.\nParent will receive a notification.');
    }
}

function bulkCheckIn() {
    alert('👥 Bulk Check-in:\n\n• Select entire class\n• Mark all as present\n• Quick attendance by group\n• Print attendance sheet\n\nFeature coming soon!');
}

// Profile & Child Management
function viewProfile(childId) {
    alert('👧 Child Profile:\n\n• Photo & personal info\n• Attendance history\n• Points & achievements\n• Medical information\n• Guardian details\n• Growth tracking\n\nFeature coming soon!');
}

// Teacher Management
function showTeachers() {
    alert('👨‍🏫 Teacher Management:\n\n• Active teachers list\n• Schedule assignments\n• Class assignments\n• Availability calendar\n• Training records\n\nFeature coming soon!');
}

function showTeacherAssignment() {
    alert('📋 Teacher Assignment:\n\n• Assign teachers to classes\n• Rotation scheduling\n• Backup teacher setup\n• Notification system\n\nFeature coming soon!');
}

// Events Management
function showEvents() {
    alert('🎉 Events Planner:\n\n• VBS (Vacation Bible School)\n• Kids camps\n• Special services\n• Field trips\n• Birthday parties\n• Christmas programs\n\nFeature coming soon!');
}

// AI Lesson Planner
function generateLesson() {
    const ageGroup = document.getElementById('ageGroupSelect').value;
    const topic = document.getElementById('lessonTopic').value;

    if (!ageGroup) {
        alert('Please select an age group first!');
        return;
    }

    if (!topic) {
        alert('Please enter a Bible story or theme!');
        return;
    }

    alert('🧠 AI Lesson Planner:\n\n' +
          'Generating lesson for: ' + ageGroup.toUpperCase() + '\n' +
          'Topic: ' + topic + '\n\n' +
          'Your lesson will include:\n' +
          '• Age-appropriate Bible story\n' +
          '• Interactive activities\n' +
          '• Memory verse\n' +
          '• Craft ideas\n' +
          '• Songs & games\n' +
          '• Take-home materials\n\n' +
          'AI Integration coming soon!');
}

// Birthday Functions
function sendBirthdayWish(childId) {
    if (confirm('Send birthday wish to this child and their parents?')) {
        alert('🎂 Birthday Wishes Sent!\n\n• SMS to parents\n• Email with greeting card\n• Special mention in class\n\nThe child will feel extra special!');
    }
}

function viewAllBirthdays() {
    alert('🎉 Birthday Calendar:\n\n• This month: 5 birthdays\n• Next month: 3 birthdays\n• Upcoming celebrations\n• Send group wishes\n• Birthday party planning\n\nFeature coming soon!');
}

// Points & Gamification
function viewFullLeaderboard() {
    alert('🏆 Full Leaderboard:\n\n' +
          'Top 10 Children by Points:\n\n' +
          '1. Emma J. - 250 pts\n' +
          '2. Noah W. - 230 pts\n' +
          '3. Olivia B. - 220 pts\n' +
          '4. Liam S. - 210 pts\n' +
          '5. Ava M. - 200 pts\n\n' +
          'Points awarded for:\n' +
          '• Perfect attendance: 10 pts\n' +
          '• Memory verses: 20 pts\n' +
          '• Helping others: 15 pts\n' +
          '• Bringing a friend: 25 pts\n\n' +
          'Feature coming soon!');
}

// Milestones
function manageMilestones() {
    alert('🎯 Growth Tracking:\n\n' +
          'Track important milestones:\n\n' +
          '📖 First Bible verse memorized\n' +
          '✝️ Baptism or dedication\n' +
          '🙏 First public prayer\n' +
          '📚 Bible reading achievements\n' +
          '🎤 First solo performance\n' +
          '👥 Leadership roles\n' +
          '🌟 Character development\n\n' +
          'Feature coming soon!');
}

// Communication
function sendParentNotification() {
    alert('📧 Parent Notification:\n\n' +
          'Send messages about:\n\n' +
          '• Weekly lesson summary\n' +
          '• Upcoming events\n' +
          '• Child\'s achievements\n' +
          '• Attendance reminders\n' +
          '• Special announcements\n\n' +
          'Via SMS, Email & App notifications\n\n' +
          'Feature coming soon!');
}

// Utilities
function printNameTags() {
    alert('🖨️ Print Name Tags:\n\n' +
          '• Select children or entire class\n' +
          '• Custom designs with photos\n' +
          '• Include allergy info\n' +
          '• Parent pickup codes\n' +
          '• QR codes for check-in\n\n' +
          'Feature coming soon!');
}

function exportChildren() {
    alert('📊 Export Options:\n\n' +
          '• Children roster (Excel/PDF)\n' +
          '• Attendance reports\n' +
          '• Contact list for parents\n' +
          '• Birthday calendar\n' +
          '• Medical information summary\n' +
          '• Points & achievements\n\n' +
          'Feature coming soon!');
}

// Initialize tooltips on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Children Ministry page loaded successfully!');
});
</script>
