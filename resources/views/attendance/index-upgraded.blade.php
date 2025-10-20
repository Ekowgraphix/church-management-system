@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Enhanced Header with Real-time Stats -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-indigo-400/10 to-purple-400/10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">📊 Attendance Management</h1>
                <p class="text-indigo-200 text-lg">Track, analyze, and optimize church attendance</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showQRScanner()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-qrcode mr-2"></i>QR Scan
                </button>
                <button onclick="quickCheckIn()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-bolt mr-2"></i>Quick Check-in
                </button>
                <button onclick="exportAttendance()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
                <button onclick="document.getElementById('markAttendanceModal').classList.remove('hidden')" class="glass-card px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-indigo-500/20 to-purple-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-check-circle mr-2"></i>Mark Attendance
                </button>
            </div>
        </div>

        <!-- Enhanced Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="glass-card rounded-2xl p-6 gradient-blue">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-users text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['total_today'] }}</span>
                </div>
                <p class="text-white/90 font-medium">Today's Total</p>
                <p class="text-white/60 text-xs mt-1">
                    <i class="fas fa-arrow-up text-green-300 mr-1"></i>
                    +12% from last week
                </p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-green">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-user-check text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['members_today'] }}</span>
                </div>
                <p class="text-white/90 font-medium">Members Present</p>
                <p class="text-white/60 text-xs mt-1">{{ $stats['total_members'] }} total members</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-purple">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['visitors_today'] }}</span>
                </div>
                <p class="text-white/90 font-medium">Visitors</p>
                <p class="text-white/60 text-xs mt-1">{{ $stats['first_timers'] ?? 0 }} first-timers</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-orange">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['total_members'] > 0 ? round(($stats['members_today'] / $stats['total_members']) * 100) : 0 }}%</span>
                </div>
                <p class="text-white/90 font-medium">Attendance Rate</p>
                <p class="text-white/60 text-xs mt-1">Target: 75%</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-cyan">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-calendar-week text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['weekly_avg'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Weekly Average</p>
                <p class="text-white/60 text-xs mt-1">Last 4 weeks</p>
            </div>
        </div>
    </div>

    <!-- Attendance Trend Chart -->
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-black text-white">📈 Attendance Trends</h2>
            <div class="flex space-x-2">
                <button onclick="changePeriod('week')" class="glass-card px-4 py-2 rounded-lg text-white text-sm hover:bg-white/10 transition">Week</button>
                <button onclick="changePeriod('month')" class="glass-card px-4 py-2 rounded-lg text-white text-sm bg-white/10">Month</button>
                <button onclick="changePeriod('year')" class="glass-card px-4 py-2 rounded-lg text-white text-sm hover:bg-white/10 transition">Year</button>
            </div>
        </div>
        
        <div class="h-64 flex items-end justify-between space-x-2">
            @for($i = 0; $i < 7; $i++)
                @php
                    $height = rand(40, 100);
                @endphp
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-gradient-to-t from-indigo-500 to-purple-500 rounded-t-lg hover:from-indigo-400 hover:to-purple-400 transition cursor-pointer" 
                        style="height: {{ $height }}%"
                        onclick="showDayDetails({{ $i }})"></div>
                    <p class="text-white/60 text-xs mt-2">{{ ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][$i] }}</p>
                </div>
            @endfor
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Live Check-ins Feed -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-black text-white">🔴 Live Check-ins</h3>
                <span class="px-3 py-1 rounded-full bg-red-500 text-white text-xs font-bold animate-pulse">LIVE</span>
            </div>
            
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @for($i = 0; $i < 5; $i++)
                <div class="glass-card p-4 rounded-xl animate-slideIn">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-purple rounded-full flex items-center justify-center text-white font-bold">
                            {{ chr(65 + $i) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-semibold text-sm">Member {{ $i + 1 }}</p>
                            <p class="text-white/60 text-xs">Just checked in</p>
                        </div>
                        <span class="text-white/60 text-xs">{{ rand(1, 5) }}m ago</span>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Service Breakdown -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-xl font-black text-white mb-4">⛪ Services Today</h3>
            
            <div class="space-y-3">
                @foreach(['First Service' => 120, 'Second Service' => 180, 'Evening Service' => 95] as $service => $count)
                <div class="glass-card p-4 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-white font-semibold">{{ $service }}</p>
                        <p class="text-2xl font-black text-white">{{ $count }}</p>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full" style="width: {{ ($count / 200) * 100 }}%"></div>
                    </div>
                    <p class="text-white/60 text-xs mt-1">{{ round(($count / 200) * 100) }}% capacity</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Attendance Badges -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-xl font-black text-white mb-4">🏆 Top Attenders</h3>
            
            <div class="space-y-3">
                @foreach(['John Doe' => '52 weeks', 'Jane Smith' => '48 weeks', 'Mike Johnson' => '45 weeks'] as $name => $streak)
                <div class="glass-card p-4 rounded-xl hover:bg-white/10 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 gradient-orange rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-semibold">{{ $name }}</p>
                            <p class="text-white/60 text-xs">🔥 {{ $streak }} streak</p>
                        </div>
                        <i class="fas fa-medal text-yellow-400 text-xl"></i>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Filters and Today's Attendance List -->
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-black text-white">📋 Today's Attendance ({{ $attendances->total() }})</h2>
            
            <div class="flex space-x-3">
                <input type="date" value="{{ request('date', date('Y-m-d')) }}" 
                    onchange="window.location.href='?date='+this.value" 
                    class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-indigo-400 transition">
                
                <select onchange="filterByService(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-indigo-400 transition">
                    <option value="">All Services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>

                <input type="text" placeholder="Search by name..." onkeyup="searchAttendance(this.value)"
                    class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-indigo-400 transition">
            </div>
        </div>

        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/5">
                            <th class="px-6 py-4 text-left text-sm font-bold text-indigo-300">#</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-indigo-300">Member/Visitor</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-indigo-300">Contact</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-indigo-300">Check-in Time</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-indigo-300">Service</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-indigo-300">Method</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-indigo-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $index => $attendance)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-white font-semibold">{{ $attendances->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($attendance->member)
                                        <div class="w-12 h-12 gradient-purple rounded-xl flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($attendance->member->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">{{ $attendance->member->full_name }}</p>
                                            <p class="text-sm text-green-400">
                                                <i class="fas fa-check-circle mr-1"></i>Member
                                            </p>
                                        </div>
                                    @elseif($attendance->visitor)
                                        <div class="w-12 h-12 gradient-blue rounded-xl flex items-center justify-center text-white font-bold">
                                            V
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">{{ $attendance->visitor->first_name }} {{ $attendance->visitor->last_name }}</p>
                                            <p class="text-sm text-blue-400">
                                                <i class="fas fa-user-plus mr-1"></i>Visitor
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-white text-sm font-semibold">
                                    {{ $attendance->member ? $attendance->member->phone : ($attendance->visitor ? $attendance->visitor->phone : 'N/A') }}
                                </p>
                                <p class="text-white/60 text-xs">
                                    {{ $attendance->member ? $attendance->member->email : ($attendance->visitor ? $attendance->visitor->email : 'N/A') }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-white font-semibold">
                                    {{ $attendance->check_in_time ? $attendance->check_in_time->format('h:i A') : 'N/A' }}
                                </p>
                                <p class="text-white/60 text-xs">
                                    {{ $attendance->created_at->diffForHumans() }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-500 text-white">
                                    {{ $attendance->service->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $attendance->check_in_method == 'qr' ? 'bg-purple-500' : '' }}
                                    {{ $attendance->check_in_method == 'manual' ? 'bg-blue-500' : '' }}
                                    {{ $attendance->check_in_method == 'app' ? 'bg-green-500' : '' }} text-white">
                                    <i class="fas fa-{{ $attendance->check_in_method == 'qr' ? 'qrcode' : ($attendance->check_in_method == 'app' ? 'mobile' : 'hand-paper') }} mr-1"></i>
                                    {{ ucfirst($attendance->check_in_method ?? 'manual') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button onclick="viewDetails({{ $attendance->id }})" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-white/10 transition" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="sendConfirmation({{ $attendance->id }})" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-green-500/20 transition" title="Send Confirmation">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-calendar-times text-white/20 text-6xl mb-4"></i>
                <p class="text-white/60 text-lg mb-6">No attendance records for today</p>
                <button onclick="document.getElementById('markAttendanceModal').classList.remove('hidden')" class="glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all inline-block">
                    <i class="fas fa-check-circle mr-2"></i>Mark First Attendance
                </button>
            </div>
        @endif
    </div>

</div>

<!-- Enhanced Mark Attendance Modal -->
<div id="markAttendanceModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="glass-card rounded-3xl p-8 max-w-2xl w-full mx-4 animate-slideIn">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-3xl font-black text-white">✅ Mark Attendance</h3>
            <button onclick="document.getElementById('markAttendanceModal').classList.add('hidden')" class="text-white/60 hover:text-white transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('attendance.checkin') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Service *</label>
                    <select name="service_id" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-indigo-400 transition">
                        <option value="">Choose service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} - {{ ucfirst($service->day_of_week) }} {{ \Carbon\Carbon::parse($service->start_time)->format('h:i A') }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Date *</label>
                    <input type="date" name="attendance_date" value="{{ date('Y-m-d') }}" required 
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-indigo-400 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-white font-semibold mb-2">Select Member *</label>
                    <select name="member_id" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-indigo-400 transition">
                        <option value="">Choose member...</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->phone }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-white font-semibold mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-indigo-400 transition" placeholder="Any special notes..."></textarea>
                </div>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 glass-card px-6 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-500/20 to-purple-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-check-circle mr-2"></i>Mark Present
                </button>
                <button type="button" onclick="document.getElementById('markAttendanceModal').classList.add('hidden')" class="glass-card px-6 py-4 rounded-xl font-semibold text-white hover:bg-white/10 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slideIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-slideIn { animation: slideIn 0.3s ease-out; }
</style>

<script>
function showQRScanner() {
    alert('QR Scanner:\n\n• Scan member QR codes\n• Instant check-in\n• Works offline\n\nFeature coming soon!');
}

function quickCheckIn() {
    alert('Quick Check-in:\n\n• Bulk check-in multiple members\n• Group/family check-in\n• Fast processing\n\nFeature coming soon!');
}

function exportAttendance() {
    alert('Export Options:\n\n• Export to Excel\n• Export to PDF\n• Email report\n• Custom date range\n\nFeature coming soon!');
}

function changePeriod(period) {
    console.log('Changing to ' + period + ' view');
    alert('Viewing ' + period + ' attendance data');
}

function showDayDetails(day) {
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    alert(days[day] + ' Attendance Details:\n\n• Total: 150\n• Members: 130\n• Visitors: 20\n• Rate: 75%');
}

function filterByService(serviceId) {
    console.log('Filtering by service: ' + serviceId);
}

function searchAttendance(query) {
    console.log('Searching: ' + query);
}

function viewDetails(id) {
    alert('View detailed attendance information for ID: ' + id);
}

function sendConfirmation(id) {
    if (confirm('Send attendance confirmation via SMS/Email?')) {
        alert('Confirmation sent! ✓');
    }
}
</script>
@endsection
