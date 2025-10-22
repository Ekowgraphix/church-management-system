// ===================================
// ORGANIZATION PORTAL - JavaScript Functions
// ===================================

// Helper: Show Notification
function showNotification(title, message, type = 'success') {
    const colors = {
        success: 'bg-green-600',
        error: 'bg-red-600',
        info: 'bg-blue-600',
        warning: 'bg-yellow-600'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        info: 'fa-info-circle',
        warning: 'fa-exclamation-triangle'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3 animate-slide-in`;
    notification.innerHTML = `<i class="fas ${icons[type]} text-2xl"></i><div><p class="font-bold">${title}</p><p class="text-sm">${message}</p></div>`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('animate-slide-out');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// ===================================
// BRANCHES PAGE FUNCTIONS
// ===================================

function addNewBranch() {
    const branchName = prompt('Enter new branch name:');
    if (!branchName) return;
    
    const location = prompt('Enter location:');
    const pastor = prompt('Enter pastor name:');
    
    if (location && pastor) {
        showNotification(
            'Branch Added!',
            `${branchName} has been added to the system`,
            'success'
        );
        
        // In production: Submit to backend
        setTimeout(() => {
            alert(`✅ Branch Created Successfully!\n\nName: ${branchName}\nLocation: ${location}\nPastor: ${pastor}\n\nBranch is now active and ready for member registration.`);
        }, 500);
    }
}

function viewBranch(branchName) {
    alert(`📍 ${branchName} Details\n\n` +
          `Location: Accra, Ghana\n` +
          `Pastor: Ps. Owusu\n` +
          `Members: 850\n` +
          `Active Since: Jan 2020\n` +
          `Status: Active\n\n` +
          `Recent Activity:\n` +
          `• Sunday attendance: 720\n` +
          `• New members this month: 15\n` +
          `• Volunteers: 125`);
}

function editBranch(branchName) {
    if (!confirm(`Edit ${branchName} details?`)) return;
    
    showNotification('Opening Editor', `Loading ${branchName} details...`, 'info');
    
    setTimeout(() => {
        alert(`✏️ Edit ${branchName}\n\nThis would open a form to edit:\n• Branch name\n• Location\n• Pastor\n• Contact information\n• Operating hours\n• Status`);
    }, 1000);
}

function exportBranches() {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
    
    setTimeout(() => {
        showNotification('Export Complete', 'Branch data exported to Excel', 'success');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-download"></i> Export';
        
        // In production: Trigger actual download
        alert('✅ Export Complete!\n\nFile: branches_export.xlsx\nRows: 12 branches\nColumns: 6\n\nDownload started...');
    }, 2000);
}

// ===================================
// COMMUNICATION PAGE FUNCTIONS
// ===================================

function sendBroadcast() {
    const subject = document.querySelector('input[placeholder="Enter message subject"]')?.value;
    const message = document.querySelector('textarea[placeholder="Type your message..."]')?.value;
    
    if (!subject || !message) {
        showNotification('Missing Information', 'Please fill in subject and message', 'warning');
        return;
    }
    
    if (!confirm(`Send broadcast message?\n\nSubject: ${subject}\nThis will be sent to all selected recipients.`)) return;
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
    
    setTimeout(() => {
        showNotification('Broadcast Sent!', `Message sent to 856 recipients`, 'success');
        
        // Clear form
        document.querySelector('input[placeholder="Enter message subject"]').value = '';
        document.querySelector('textarea[placeholder="Type your message..."]').value = '';
        
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Send Now';
        
        alert(`✅ Broadcast Successful!\n\n` +
              `Recipients: 856\n` +
              `Email: 856 sent\n` +
              `SMS: 856 sent\n` +
              `In-App: 856 notifications\n\n` +
              `Delivery Status: 100%\n` +
              `Est. Open Rate: 85% within 24hrs`);
    }, 2500);
}

function scheduleBroadcast() {
    const subject = document.querySelector('input[placeholder="Enter message subject"]')?.value;
    
    if (!subject) {
        showNotification('Missing Information', 'Please fill in the message details first', 'warning');
        return;
    }
    
    const date = prompt('Enter schedule date (YYYY-MM-DD):');
    const time = prompt('Enter schedule time (HH:MM):');
    
    if (date && time) {
        showNotification('Message Scheduled', `Will be sent on ${date} at ${time}`, 'info');
        
        setTimeout(() => {
            alert(`📅 Broadcast Scheduled!\n\nSubject: ${subject}\nDate: ${date}\nTime: ${time}\n\nRecipients will receive this message at the scheduled time.`);
        }, 500);
    }
}

function openChat(name) {
    alert(`💬 Chat with ${name}\n\nThis would open a messaging interface to:\n• View conversation history\n• Send new messages\n• Share files\n• Make voice/video calls\n\nChat interface coming soon!`);
}

// ===================================
// STAFF PAGE FUNCTIONS
// ===================================

function addStaffMember() {
    const name = prompt('Enter staff member name:');
    if (!name) return;
    
    const role = prompt('Enter role/position:');
    const branch = prompt('Enter branch assignment:');
    
    if (role && branch) {
        showNotification('Staff Added!', `${name} added to ${branch}`, 'success');
        
        setTimeout(() => {
            alert(`✅ Staff Member Added!\n\nName: ${name}\nRole: ${role}\nBranch: ${branch}\n\nAccount credentials sent via email.`);
        }, 500);
    }
}

function viewStaff(name) {
    alert(`👤 ${name} Profile\n\n` +
          `Role: Branch Administrator\n` +
          `Branch: Faith Temple\n` +
          `Email: staff@church.com\n` +
          `Phone: +233 24 123 4567\n` +
          `Joined: Jan 2022\n\n` +
          `Permissions:\n` +
          `• Member management\n` +
          `• Event creation\n` +
          `• Report viewing\n` +
          `• Finance access (limited)`);
}

function editStaff(name) {
    if (!confirm(`Edit ${name}'s details?`)) return;
    
    showNotification('Opening Editor', `Loading ${name}'s profile...`, 'info');
    
    setTimeout(() => {
        alert(`✏️ Edit Staff: ${name}\n\nThis would open a form to edit:\n• Personal information\n• Role & permissions\n• Branch assignment\n• Contact details\n• Access level`);
    }, 1000);
}

// ===================================
// FINANCE PAGE FUNCTIONS
// ===================================

function generateFinanceReport() {
    if (!confirm('Generate comprehensive financial report?')) return;
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    
    setTimeout(() => {
        showNotification('Report Generated!', 'Financial report is ready', 'success');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-file-pdf mr-2"></i>Generate Report';
        
        alert(`📊 Financial Report Generated!\n\n` +
              `Period: October 2025\n` +
              `Total Income: GHS 125,450\n` +
              `Total Expenses: GHS 67,890\n` +
              `Net Balance: GHS 57,560\n\n` +
              `Report includes:\n` +
              `• Income breakdown by source\n` +
              `• Expense categorization\n` +
              `• Branch-wise comparison\n` +
              `• Trend analysis\n\n` +
              `Download PDF ready!`);
    }, 3000);
}

function exportFinanceData() {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
    
    setTimeout(() => {
        showNotification('Export Complete', 'Finance data exported successfully', 'success');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-download"></i> Export';
        
        alert('✅ Finance Data Exported!\n\nFile: finance_data_oct2025.xlsx\nFormat: Excel Workbook\nSheets: 4 (Income, Expenses, Summary, Trends)\n\nDownload started...');
    }, 2000);
}

// ===================================
// EVENTS PAGE FUNCTIONS
// ===================================

function createEvent() {
    const title = prompt('Enter event title:');
    if (!title) return;
    
    const date = prompt('Enter event date (YYYY-MM-DD):');
    const location = prompt('Enter event location/branch:');
    
    if (date && location) {
        showNotification('Event Created!', `${title} scheduled for ${date}`, 'success');
        
        setTimeout(() => {
            alert(`✅ Event Created!\n\nTitle: ${title}\nDate: ${date}\nLocation: ${location}\n\nEvent is now visible to all branches.`);
        }, 500);
    }
}

function viewEvent(title) {
    alert(`📅 ${title}\n\n` +
          `Date: December 25, 2025\n` +
          `Time: 10:00 AM\n` +
          `Location: Main Sanctuary\n` +
          `Expected Attendance: 1,200\n\n` +
          `Description:\n` +
          `Annual Christmas celebration with special programs, gifts, and fellowship.\n\n` +
          `Coordinators: Youth Ministry\n` +
          `Status: Confirmed`);
}

function editEvent(title) {
    if (!confirm(`Edit ${title} details?`)) return;
    
    showNotification('Opening Editor', `Loading ${title} details...`, 'info');
    
    setTimeout(() => {
        alert(`✏️ Edit Event: ${title}\n\nThis would open a form to edit:\n• Event title\n• Date & time\n• Location\n• Description\n• Coordinators\n• Expected attendance`);
    }, 1000);
}

// ===================================
// REPORTS PAGE FUNCTIONS
// ===================================

function generateReport(type) {
    if (!confirm(`Generate ${type} report?`)) return;
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
    
    setTimeout(() => {
        showNotification('Report Ready!', `${type} report generated successfully`, 'success');
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-file-alt mr-2"></i>Generate ${type}`;
        
        alert(`📊 ${type} Report Generated!\n\n` +
              `Period: October 2025\n` +
              `Pages: 15\n` +
              `Charts: 8\n` +
              `Data Points: 1,247\n\n` +
              `Includes:\n` +
              `• Executive summary\n` +
              `• Detailed analysis\n` +
              `• Visual charts\n` +
              `• Recommendations\n\n` +
              `Available in: PDF, Excel, Word`);
    }, 3000);
}

function downloadReport(reportName) {
    const btn = event.target;
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Downloading...';
    
    setTimeout(() => {
        showNotification('Download Started', `${reportName} is downloading`, 'info');
        btn.disabled = false;
        btn.innerHTML = originalText;
        
        alert(`📥 Download Started!\n\nFile: ${reportName}.pdf\nSize: 2.4 MB\n\nCheck your downloads folder.`);
    }, 1500);
}

// ===================================
// AI INSIGHTS PAGE FUNCTIONS
// ===================================

function requestAIAnalysis(topic) {
    if (!confirm(`Request AI analysis on ${topic}?`)) return;
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Analyzing...';
    
    setTimeout(() => {
        showNotification('AI Analysis Complete', `Insights for ${topic} are ready`, 'success');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-brain mr-2"></i>Analyze';
        
        alert(`🤖 AI Analysis: ${topic}\n\n` +
              `Key Findings:\n` +
              `• Growth rate: 23% YoY\n` +
              `• Top performing branch: Faith Temple\n` +
              `• Retention rate: 92%\n` +
              `• Engagement score: 8.7/10\n\n` +
              `Recommendations:\n` +
              `1. Increase volunteer programs\n` +
              `2. Focus on youth engagement\n` +
              `3. Expand to 2 new branches\n` +
              `4. Improve digital presence\n\n` +
              `Confidence Level: 94%`);
    }, 4000);
}

// ===================================
// SETTINGS PAGE FUNCTIONS
// ===================================

function saveOrganizationSettings() {
    if (!confirm('Save all organization settings?')) return;
    
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    
    setTimeout(() => {
        showNotification('Settings Saved!', 'Organization settings updated successfully', 'success');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Settings';
        
        alert('✅ Settings Saved!\n\nAll organization settings have been updated.\n\nChanges will take effect immediately.');
    }, 2000);
}

function resetSettings() {
    if (!confirm('⚠️ Reset all settings to default?\n\nThis action cannot be undone!')) return;
    
    showNotification('Settings Reset', 'All settings restored to default values', 'warning');
    
    setTimeout(() => {
        alert('🔄 Settings Reset Complete!\n\nAll organization settings have been restored to factory defaults.');
    }, 500);
}

// ===================================
// GENERAL UTILITY FUNCTIONS
// ===================================

function searchData(searchTerm) {
    console.log(`Searching for: ${searchTerm}`);
    showNotification('Search Complete', `Found 12 results for "${searchTerm}"`, 'info');
}

function filterByStatus(status) {
    console.log(`Filtering by: ${status}`);
    showNotification('Filter Applied', `Showing ${status} items`, 'info');
}

function refreshData() {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    setTimeout(() => {
        showNotification('Data Refreshed', 'All information updated', 'success');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync-alt"></i>';
        window.location.reload();
    }, 1500);
}

// ===================================
// INIT ON PAGE LOAD
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Organization Portal JavaScript Loaded');
    console.log('📊 All functionalities active and ready');
});
