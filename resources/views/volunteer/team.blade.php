@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">👥 My Team / Group</h1>
        <p class="text-gray-600">Connect with your volunteer team members</p>
    </div>

    <!-- Team Leader -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white mb-6">
        <h3 class="text-xl font-bold mb-4">Team Leader</h3>
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-orange-600">JM</span>
            </div>
            <div class="flex-1">
                <p class="text-lg font-bold">John Mensah</p>
                <p class="text-sm opacity-90">Ushering Team Coordinator</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="callTeamLeader('John Mensah', '+233 24 123 4567')" class="bg-white text-orange-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                    <i class="fas fa-phone"></i>
                </button>
                <button onclick="messageTeamLeader('John Mensah')" class="bg-white text-orange-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-all">
                    <i class="fas fa-comment"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Team Members -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Team Members</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="font-bold text-blue-600">AO</span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Abigail Owusu</p>
                    <p class="text-sm text-gray-600">Volunteer</p>
                </div>
                <button onclick="messageTeamMember('Abigail Owusu')" class="text-blue-600 hover:text-blue-800 transition-all">
                    <i class="fas fa-comment"></i>
                </button>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="font-bold text-green-600">FA</span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Felix Addo</p>
                    <p class="text-sm text-gray-600">Volunteer</p>
                </div>
                <button onclick="messageTeamMember('Felix Addo')" class="text-blue-600 hover:text-blue-800 transition-all">
                    <i class="fas fa-comment"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- AI Team Insights -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
        <h3 class="text-xl font-bold mb-3">🧠 AI Team Insights</h3>
        <div class="space-y-3">
            <div class="bg-white/20 rounded-lg p-4">
                <p class="font-semibold">Team Attendance Rate</p>
                <p class="text-2xl font-bold">95%</p>
                <p class="text-sm opacity-90">Excellent consistency this month!</p>
            </div>
            <div class="bg-white/20 rounded-lg p-4">
                <p class="font-semibold">Team Efficiency</p>
                <p class="text-sm">Your team completes tasks 20% faster than average. Keep up the great work! 🎯</p>
            </div>
        </div>
    </div>
</div>

<script>
// Call Team Leader
function callTeamLeader(name, phone) {
    if(confirm(`📞 Call ${name}?\n\nPhone: ${phone}`)) {
        alert(`📱 Dialing ${phone}...\n\nOpening phone dialer.`);
        // On mobile, this would trigger the phone dialer
        window.location.href = `tel:${phone}`;
    }
}

// Message Team Leader
function messageTeamLeader(name) {
    alert(`💬 Message ${name}\n\nOpening messaging interface...`);
    // TODO: Open internal messaging system or redirect to communication page
    // window.location.href = '/volunteer/communication?to=' + encodeURIComponent(name);
}

// Message Team Member
function messageTeamMember(name) {
    const message = prompt(`💬 Send message to ${name}:\n\nType your message:`);
    
    if(message && message.trim()) {
        // Show sending feedback
        alert(`✉️ Sending message...\n\nTo: ${name}\nMessage: "${message}"\n\nMessage sent successfully!`);
        
        // TODO: Send message via API
        // fetch('/api/messages/send', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //     },
        //     body: JSON.stringify({
        //         recipient: name,
        //         message: message
        //     })
        // });
    }
}

// View Full Team Stats
function viewTeamStats() {
    alert(`📊 Team Statistics\n\n` +
          `Team Name: Ushering Team\n` +
          `Total Members: 8\n` +
          `Active Members: 7\n` +
          `Attendance Rate: 95%\n` +
          `Tasks Completed: 142\n` +
          `Average Response Time: 2.5 hours\n\n` +
          `🏆 Top Performer: Abigail Owusu\n` +
          `⭐ Most Helpful: Felix Addo`);
}

// Schedule Team Meeting
function scheduleTeamMeeting() {
    const date = prompt('📅 Schedule Team Meeting\n\nEnter date (YYYY-MM-DD):');
    
    if(date) {
        const time = prompt('⏰ Enter time (HH:MM):');
        
        if(time) {
            alert(`✅ Team Meeting Scheduled!\n\n` +
                  `📅 Date: ${date}\n` +
                  `⏰ Time: ${time}\n` +
                  `👥 Participants: All team members\n\n` +
                  `Notifications will be sent to all team members.`);
            
            // TODO: Create meeting via API
        }
    }
}

// Request Team Backup
function requestBackup() {
    if(confirm('🆘 Request Team Backup?\n\nThis will notify available volunteers to assist your team.')) {
        alert(`📢 Backup Request Sent!\n\n` +
              `Notifying available volunteers...\n` +
              `Expected response time: 15 minutes\n\n` +
              `You will receive a notification when someone accepts.`);
        
        // TODO: Send backup request via API
    }
}
</script>
@endsection
