@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cyan-900 via-blue-900 to-purple-900 p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">🧍‍♂️ Visitors Management</h1>
                <p class="text-white/80">Track, engage, and convert visitors to members</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showAddVisitorModal()" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90">
                    + Add Visitor
                </button>
                <button onclick="generateQRCodes()" class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90">
                    📱 QR Sign-In
                </button>
                <button onclick="exportVisitorReport()" class="bg-white/20 backdrop-blur text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/30">
                    📤 Export
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl p-6 text-white shadow-2xl transform hover:scale-105 transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">This Week</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $stats['visitors_this_week'] ?? 0 }}</h3>
                <p class="text-sm opacity-90">New Visitors</p>
                <div class="mt-3 flex items-center text-xs">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $stats['visitor_change'] ?? 0 }}% from last week</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-2xl transform hover:scale-105 transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">Converted</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $stats['converted_members'] ?? 0 }}</h3>
                <p class="text-sm opacity-90">Became Members</p>
                <div class="mt-3 flex items-center text-xs">
                    <i class="fas fa-trophy mr-1"></i>
                    <span>{{ $stats['conversion_rate'] ?? 0 }}% conversion rate</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-yellow-600 rounded-2xl p-6 text-white shadow-2xl transform hover:scale-105 transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-phone text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">Pending</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $stats['pending_followup'] ?? 0 }}</h3>
                <p class="text-sm opacity-90">Follow-Ups Needed</p>
                <div class="mt-3 flex items-center text-xs">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    <span>Needs attention</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl p-6 text-white shadow-2xl transform hover:scale-105 transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-redo text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">Returning</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $stats['returning_visitors'] ?? 0 }}</h3>
                <p class="text-sm opacity-90">Returning Visitors</p>
                <div class="mt-3 flex items-center text-xs">
                    <i class="fas fa-heart mr-1"></i>
                    <span>Great engagement!</span>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">📊 Visitor Trends (Last 6 Months)</h3>
                <canvas id="visitorTrendsChart" height="250"></canvas>
            </div>

            <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">🎯 Service Popularity Heatmap</h3>
                <canvas id="serviceHeatmapChart" height="250"></canvas>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Visitors List -->
            <div class="lg:col-span-2 bg-white/10 backdrop-blur rounded-2xl p-6">
                <!-- Tabs -->
                <div class="flex space-x-4 mb-6 border-b border-white/20">
                    <button onclick="filterStatus('all')" class="tab-btn active px-4 py-2 text-white font-semibold border-b-2 border-white">
                        All Visitors
                    </button>
                    <button onclick="filterStatus('new')" class="tab-btn px-4 py-2 text-white/70 font-semibold border-b-2 border-transparent hover:text-white">
                        New
                    </button>
                    <button onclick="filterStatus('contacted')" class="tab-btn px-4 py-2 text-white/70 font-semibold border-b-2 border-transparent hover:text-white">
                        Contacted
                    </button>
                    <button onclick="filterStatus('converted')" class="tab-btn px-4 py-2 text-white/70 font-semibold border-b-2 border-transparent hover:text-white">
                        Converted
                    </button>
                </div>

                <!-- Filters -->
                <div class="flex space-x-3 mb-6">
                    <select class="flex-1 bg-white/20 text-white rounded-xl px-4 py-2 border border-white/30">
                        <option>All Services</option>
                        <option>Sunday Service</option>
                        <option>Midweek Service</option>
                        <option>Youth Service</option>
                        <option>Special Events</option>
                    </select>
                    <input type="date" class="bg-white/20 text-white rounded-xl px-4 py-2 border border-white/30">
                    <input type="text" placeholder="Search..." class="bg-white/20 text-white rounded-xl px-4 py-2 border border-white/30 placeholder-white/60">
                </div>

                <!-- Visitor Cards -->
                <div class="space-y-3 max-h-[600px] overflow-y-auto">
                    @forelse($visitors ?? [] as $visitor)
                    <div class="bg-white/10 rounded-xl p-4 hover:bg-white/20 transition">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($visitor->first_name, 0, 1) }}{{ substr($visitor->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold text-lg">{{ $visitor->full_name }}</h4>
                                    <p class="text-white/60 text-sm">📱 {{ $visitor->phone }} • 📧 {{ $visitor->email }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $visitor->followup_status === 'Pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                {{ $visitor->followup_status === 'Completed' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                {{ $visitor->followup_status === 'Converted' ? 'bg-green-500/20 text-green-400' : '' }}">
                                {{ $visitor->followup_status }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm text-white/80 mb-3">
                            <div><i class="fas fa-calendar mr-2"></i>Visited: {{ $visitor->visit_date->format('M d, Y') }}</div>
                            <div><i class="fas fa-church mr-2"></i>{{ $visitor->service_attended ?? 'N/A' }}</div>
                            <div><i class="fas fa-user-friends mr-2"></i>Invited by: {{ $visitor->invited_by ?? 'Walk-in' }}</div>
                            <div><i class="fas fa-redo mr-2"></i>Visits: {{ $visitor->visit_count ?? 1 }}</div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="showFollowUpModal({{ $visitor->id }})" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600">
                                📞 Follow-Up
                            </button>
                            <button onclick="viewJourney({{ $visitor->id }})" class="flex-1 bg-purple-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-600">
                                🛤️ Journey
                            </button>
                            <button onclick="sendWelcomeSMS({{ $visitor->id }})" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-600">
                                📱 SMS
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-white/60">
                        <i class="fas fa-user-plus text-5xl mb-4"></i>
                        <p>No visitors yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- AI Follow-Up Assistant -->
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl p-6 text-white">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-robot mr-2"></i>
                        AI Follow-Up Assistant
                    </h3>
                    <p class="text-sm mb-4 opacity-90">Generate personalized follow-up messages!</p>
                    <select id="messageType" class="w-full bg-white/20 rounded-xl px-4 py-3 text-white mb-3">
                        <option>Welcome Message</option>
                        <option>Follow-Up Call</option>
                        <option>Event Invitation</option>
                        <option>Prayer Request</option>
                    </select>
                    <button onclick="generateFollowUpMessage()" class="w-full bg-white text-purple-600 px-4 py-2 rounded-xl font-semibold hover:bg-white/90">
                        🤖 Generate Message
                    </button>
                    <div id="generatedMessage" class="mt-4 hidden bg-white/20 rounded-xl p-4 text-sm"></div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button onclick="bulkSendWelcome()" class="w-full bg-white/20 text-white px-4 py-3 rounded-xl hover:bg-white/30 text-left">
                            <i class="fas fa-envelope mr-2"></i>
                            Send Bulk Welcome
                        </button>
                        <button onclick="generateQRCodes()" class="w-full bg-white/20 text-white px-4 py-3 rounded-xl hover:bg-white/30 text-left">
                            <i class="fas fa-qrcode mr-2"></i>
                            Generate QR Codes
                        </button>
                        <button onclick="exportVisitors()" class="w-full bg-white/20 text-white px-4 py-3 rounded-xl hover:bg-white/30 text-left">
                            <i class="fas fa-download mr-2"></i>
                            Export Visitors
                        </button>
                        <button onclick="viewCalendar()" class="w-full bg-white/20 text-white px-4 py-3 rounded-xl hover:bg-white/30 text-left">
                            <i class="fas fa-calendar mr-2"></i>
                            Follow-Up Calendar
                        </button>
                    </div>
                </div>

                <!-- Conversion Funnel -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">📈 Conversion Funnel</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-white text-sm mb-2">
                                <span>First Visit</span>
                                <span>100%</span>
                            </div>
                            <div class="bg-white/20 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-white text-sm mb-2">
                                <span>Second Visit</span>
                                <span>65%</span>
                            </div>
                            <div class="bg-white/20 rounded-full h-3">
                                <div class="bg-purple-500 h-3 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-white text-sm mb-2">
                                <span>Third Visit</span>
                                <span>45%</span>
                            </div>
                            <div class="bg-white/20 rounded-full h-3">
                                <div class="bg-pink-500 h-3 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-white text-sm mb-2">
                                <span>Converted</span>
                                <span>35%</span>
                            </div>
                            <div class="bg-white/20 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full" style="width: 35%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Visitor Modal -->
<div id="addVisitorModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-8 max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold mb-6">Add New Visitor</h2>
        <form action="{{ route('visitors.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">First Name</label>
                    <input type="text" name="first_name" required class="w-full px-4 py-2 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Last Name</label>
                    <input type="text" name="last_name" required class="w-full px-4 py-2 border rounded-xl">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Phone</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-2 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border rounded-xl">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Visit Date</label>
                    <input type="date" name="visit_date" required class="w-full px-4 py-2 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Service Attended</label>
                    <select name="service_attended" class="w-full px-4 py-2 border rounded-xl">
                        <option>Sunday Service</option>
                        <option>Midweek Service</option>
                        <option>Youth Service</option>
                        <option>Special Event</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Invited By</label>
                <input type="text" name="invited_by" class="w-full px-4 py-2 border rounded-xl">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Feedback/Notes</label>
                <textarea name="feedback" rows="3" class="w-full px-4 py-2 border rounded-xl"></textarea>
            </div>
            <div class="flex items-center mb-6">
                <input type="checkbox" name="send_welcome" id="sendWelcome" class="mr-2">
                <label for="sendWelcome">Send welcome SMS/Email</label>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold">
                    Add Visitor
                </button>
                <button type="button" onclick="closeModal('addVisitorModal')" class="px-6 py-3 bg-gray-200 rounded-xl font-semibold">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/visitors-ai.js') }}"></script>
<script>
// Initialize Charts
const ctx1 = document.getElementById('visitorTrendsChart');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'New Visitors',
            data: [12, 19, 15, 25, 22, 30],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
        }, {
            label: 'Converted',
            data: [4, 7, 5, 9, 8, 11],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: 'white' } } },
        scales: {
            y: { ticks: { color: 'white' }, grid: { color: 'rgba(255,255,255,0.1)' } },
            x: { ticks: { color: 'white' }, grid: { color: 'rgba(255,255,255,0.1)' } }
        }
    }
});

const ctx2 = document.getElementById('serviceHeatmapChart');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Sunday', 'Midweek', 'Youth', 'Special'],
        datasets: [{
            label: 'Visitors',
            data: [45, 28, 35, 20],
            backgroundColor: [
                'rgb(59, 130, 246)',
                'rgb(168, 85, 247)',
                'rgb(236, 72, 153)',
                'rgb(251, 191, 36)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: 'white' } } },
        scales: {
            y: { ticks: { color: 'white' }, grid: { color: 'rgba(255,255,255,0.1)' } },
            x: { ticks: { color: 'white' }, grid: { color: 'rgba(255,255,255,0.1)' } }
        }
    }
});

// Modal Functions
function showAddVisitorModal() {
    document.getElementById('addVisitorModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Filter Functions
function filterStatus(status) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-white');
        btn.classList.add('text-white/70', 'border-transparent');
    });
    event.target.classList.add('active', 'border-white', 'text-white');
    event.target.classList.remove('text-white/70', 'border-transparent');
    // Add filtering logic here
}

// AI Functions
function generateFollowUpMessage() {
    const messageDiv = document.getElementById('generatedMessage');
    messageDiv.classList.remove('hidden');
    messageDiv.innerHTML = '<div class="animate-pulse">Generating...</div>';
    
    setTimeout(() => {
        messageDiv.innerHTML = `
            <div class="font-semibold mb-2">Generated Message:</div>
            <div class="mb-3">Hi! Thank you for visiting us last Sunday. We'd love to see you again this week! God bless you! 🙏</div>
            <button onclick="navigator.clipboard.writeText(this.previousElementSibling.textContent)" class="bg-white text-purple-600 px-4 py-2 rounded-lg text-sm font-semibold">
                📋 Copy Message
            </button>
        `;
    }, 1500);
}

function showFollowUpModal(id) {
    alert('Opening follow-up form for visitor #' + id);
}

function viewJourney(id) {
    window.location.href = `/visitors/${id}/journey`;
}

function sendWelcomeSMS(id) {
    if (confirm('Send welcome SMS to this visitor?')) {
        alert('SMS sent successfully!');
    }
}

function exportVisitorReport() {
    alert('Generating visitor report...');
}

function generateQRCodes() {
    alert('Generating QR codes for visitors...');
}

function bulkSendWelcome() {
    alert('Sending bulk welcome messages...');
}

function exportVisitors() {
    alert('Exporting visitors to Excel...');
}

function viewCalendar() {
    window.location.href = '/visitors/calendar';
}
</script>
@endsection
