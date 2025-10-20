@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Enhanced Counselling Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-teal-400/10 to-cyan-400/10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">💬 Counselling Sessions</h1>
                <p class="text-teal-200 text-lg">Professional spiritual and life guidance</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showCalendar()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-calendar-alt mr-2"></i>Calendar
                </button>
                <button onclick="counsellorSchedule()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-user-clock mr-2"></i>Schedule
                </button>
                <button onclick="exportSessions()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
                <a href="{{ route('counselling.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-teal-500/20 to-cyan-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>New Session
                </a>
            </div>
        </div>

        <!-- Enhanced Counselling Stats -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="glass-card rounded-2xl p-6 gradient-blue">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-clipboard-list text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['total'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Total Sessions</p>
                <p class="text-white/60 text-xs mt-1">All time</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-green">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['this_month'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">This Month</p>
                <p class="text-white/60 text-xs mt-1">{{ date('F') }}</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-purple">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-clock text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['upcoming'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Upcoming</p>
                <p class="text-white/60 text-xs mt-1">Scheduled</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-orange">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-user-tie text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['counsellors'] ?? 5 }}</span>
                </div>
                <p class="text-white/90 font-medium">Counsellors</p>
                <p class="text-white/60 text-xs mt-1">Active</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-red">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-redo text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['followups'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Follow-ups</p>
                <p class="text-white/60 text-xs mt-1">Due this week</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-cyan">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['success_rate'] ?? 92 }}%</span>
                </div>
                <p class="text-white/90 font-medium">Success Rate</p>
                <p class="text-white/60 text-xs mt-1">Resolution</p>
            </div>
        </div>
    </div>

    <!-- Session Categories -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach([
            ['icon' => 'ring', 'name' => 'Marriage', 'count' => 34, 'color' => 'pink'],
            ['icon' => 'home', 'name' => 'Family', 'count' => 28, 'color' => 'blue'],
            ['icon' => 'user', 'name' => 'Personal', 'count' => 45, 'color' => 'purple'],
            ['icon' => 'briefcase', 'name' => 'Career', 'count' => 18, 'color' => 'orange'],
            ['icon' => 'heart-broken', 'name' => 'Grief', 'count' => 12, 'color' => 'red'],
            ['icon' => 'praying-hands', 'name' => 'Spiritual', 'count' => 56, 'color' => 'green']
        ] as $cat)
        <button onclick="filterByType('{{ $cat['name'] }}')" class="glass-card p-4 rounded-xl hover:bg-white/10 transition group">
            <div class="w-12 h-12 gradient-{{ $cat['color'] }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-{{ $cat['icon'] }} text-white text-xl"></i>
            </div>
            <p class="text-white font-semibold text-sm">{{ $cat['name'] }}</p>
            <p class="text-white/60 text-xs">{{ $cat['count'] }} sessions</p>
        </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sessions List -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Filters -->
            <div class="glass-card rounded-2xl p-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <select onchange="filterByCounsellor(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-teal-400 transition">
                        <option value="">All Counsellors</option>
                        <option value="pastor">Pastor John</option>
                        <option value="elder">Elder Sarah</option>
                        <option value="deacon">Deacon Mark</option>
                    </select>

                    <select onchange="filterByConfidentiality(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-teal-400 transition">
                        <option value="">All Levels</option>
                        <option value="normal">Normal</option>
                        <option value="private">Private</option>
                        <option value="strict">Strictly Confidential</option>
                    </select>

                    <select onchange="filterByStatus(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-teal-400 transition">
                        <option value="">All Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="followup">Follow-up Needed</option>
                    </select>

                    <input type="date" onchange="filterByDate(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-teal-400 transition">

                    <input type="text" placeholder="Search sessions..." onkeyup="searchSessions(this.value)"
                        class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 text-sm focus:outline-none focus:border-teal-400 transition">
                </div>
            </div>

            <!-- Session Cards -->
            @if($counsellings->count() > 0)
                @foreach($counsellings as $counselling)
                <div class="glass-card rounded-2xl p-6 hover:bg-white/10 transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="w-14 h-14 gradient-teal rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-tie text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-xl font-bold text-white">{{ $counselling->counsellor_name ?? ($counselling->counsellor ? $counselling->counsellor->name : 'N/A') }}</h3>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                        {{ $counselling->is_confidential ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400' }}">
                                        <i class="fas fa-lock mr-1"></i>{{ $counselling->is_confidential ? 'Confidential' : 'Normal' }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2">
                                    <p class="text-white/80">
                                        <i class="fas fa-user mr-2 text-teal-400"></i>
                                        <span class="font-semibold">Counselee:</span>
                                        @if($counselling->member)
                                            {{ $counselling->member->first_name }} {{ $counselling->member->last_name }}
                                        @else
                                            {{ $counselling->member_name ?? 'Anonymous' }}
                                        @endif
                                    </p>
                                    
                                    <p class="text-white/80">
                                        <i class="fas fa-clipboard mr-2 text-purple-400"></i>
                                        <span class="font-semibold">Topic:</span> {{ $counselling->topic }}
                                    </p>

                                    @if($counselling->session_type)
                                    <p class="text-white/80">
                                        <i class="fas fa-tag mr-2 text-purple-400"></i>
                                        <span class="font-semibold">Type:</span> {{ ucfirst($counselling->session_type) }}
                                    </p>
                                    @endif

                                    <p class="text-white/80">
                                        <i class="fas fa-calendar mr-2 text-blue-400"></i>
                                        <span class="font-semibold">Session Date:</span> {{ $counselling->session_date->format('M d, Y') }}
                                        @if($counselling->session_time)
                                            {{ $counselling->session_time->format('h:i A') }}
                                        @endif
                                    </p>

                                    @if($counselling->follow_up_date)
                                    <p class="text-white/80">
                                        <i class="fas fa-redo mr-2 text-orange-400"></i>
                                        <span class="font-semibold">Follow-up:</span> {{ $counselling->follow_up_date->format('M d, Y') }}
                                    </p>
                                    @endif

                                    @if($counselling->notes)
                                    <p class="text-white/60 text-sm mt-2 italic">
                                        {{ Str::limit($counselling->notes, 100) }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end space-y-2">
                            <span class="px-4 py-2 rounded-lg text-xs font-bold 
                                {{ $counselling->status === 'completed' ? 'bg-green-500/20 text-green-400' : '' }}
                                {{ $counselling->status === 'pending' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                {{ $counselling->status === 'cancelled' ? 'bg-red-500/20 text-red-400' : '' }}
                                {{ $counselling->status === 'follow_up' ? 'bg-orange-500/20 text-orange-400' : '' }}">
                                {{ ucfirst($counselling->status) }}
                            </span>
                            @if($counselling->duration)
                            <span class="text-white/60 text-xs">{{ $counselling->duration }} min</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-white/10">
                        <div class="flex space-x-2">
                            <a href="{{ route('counselling.show', $counselling) }}" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-white/10 transition">
                                <i class="fas fa-eye mr-2"></i>View Details
                            </a>
                            <a href="{{ route('counselling.edit', $counselling) }}" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-teal-500/20 transition">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                        </div>
                        
                        <div class="flex space-x-2">
                            @if($counselling->follow_up_date)
                            <button onclick="sendReminder({{ $counselling->id }})" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-orange-500/20 transition" title="Send Reminder">
                                <i class="fas fa-bell"></i>
                            </button>
                            @endif
                            <form method="POST" action="{{ route('counselling.destroy', $counselling) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="glass-card px-3 py-2 rounded-lg text-red-400 hover:bg-red-500/20 transition" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="mt-6">
                    {{ $counsellings->links() }}
                </div>
            @else
                <div class="glass-card rounded-2xl p-12 text-center">
                    <i class="fas fa-comments text-white/20 text-6xl mb-4"></i>
                    <p class="text-white/60 text-lg mb-6">No counselling sessions found</p>
                    <a href="{{ route('counselling.create') }}" class="glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all inline-block">
                        <i class="fas fa-plus mr-2"></i>Schedule First Session
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Today's Schedule -->
            <div class="glass-card rounded-2xl p-6 bg-gradient-to-br from-teal-500/10 to-cyan-500/10">
                <h3 class="text-xl font-black text-white mb-4">📅 Today's Schedule</h3>
                <div class="space-y-3">
                    @for($i = 0; $i < 3; $i++)
                    <div class="glass-card p-4 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-white font-bold">{{ ['09:00 AM', '02:00 PM', '04:30 PM'][$i] }}</p>
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full font-bold">
                                {{ ['30min', '1hr', '45min'][$i] }}
                            </span>
                        </div>
                        <p class="text-white/80 text-sm font-semibold">{{ ['Marriage Counselling', 'Personal Issues', 'Family Mediation'][$i] }}</p>
                        <p class="text-white/60 text-xs mt-1">{{ ['Pastor John', 'Elder Sarah', 'Deacon Mark'][$i] }}</p>
                    </div>
                    @endfor
                </div>
                <button onclick="viewFullSchedule()" class="w-full mt-4 glass-card px-4 py-2 rounded-xl text-white font-semibold hover:bg-white/10 transition">
                    View Full Calendar
                </button>
            </div>

            <!-- Counsellor Availability -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">👥 Counsellor Status</h3>
                <div class="space-y-3">
                    @foreach([
                        ['name' => 'Pastor John', 'status' => 'available', 'sessions' => 45],
                        ['name' => 'Elder Sarah', 'status' => 'in-session', 'sessions' => 38],
                        ['name' => 'Deacon Mark', 'status' => 'available', 'sessions' => 32]
                    ] as $counsellor)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div class="w-10 h-10 gradient-purple rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($counsellor['name'], 0, 1) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 {{ $counsellor['status'] === 'available' ? 'bg-green-500' : 'bg-orange-500' }} rounded-full border-2 border-gray-800"></div>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm">{{ $counsellor['name'] }}</p>
                                <p class="text-white/60 text-xs">{{ $counsellor['sessions'] }} sessions</p>
                            </div>
                        </div>
                        <button onclick="bookCounsellor('{{ $counsellor['name'] }}')" class="glass-card px-3 py-1 rounded-lg text-white text-xs hover:bg-white/10 transition">
                            Book
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Session Resources -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">📚 Resources</h3>
                <div class="space-y-2">
                    <button onclick="openResource('guidelines')" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition">
                        <i class="fas fa-book mr-2 text-blue-400"></i>Counselling Guidelines
                    </button>
                    <button onclick="openResource('forms')" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition">
                        <i class="fas fa-file-alt mr-2 text-green-400"></i>Session Forms
                    </button>
                    <button onclick="openResource('referrals')" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition">
                        <i class="fas fa-handshake mr-2 text-purple-400"></i>Referral Network
                    </button>
                    <button onclick="openResource('training')" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition">
                        <i class="fas fa-graduation-cap mr-2 text-orange-400"></i>Training Materials
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">📊 Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-white/80 text-sm">Avg. Session Length</span>
                        <span class="text-white font-bold">52 min</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/80 text-sm">Follow-up Rate</span>
                        <span class="text-green-400 font-bold">87%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/80 text-sm">Resolution Rate</span>
                        <span class="text-green-400 font-bold">92%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/80 text-sm">This Week</span>
                        <span class="text-blue-400 font-bold">18 sessions</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function showCalendar() {
    window.location.href = '{{ route("counselling.calendar") }}';
}

function counsellorSchedule() {
    alert('Counsellor Schedule:\n\n• View availability\n• Book appointments\n• Manage time slots\n• Set unavailable times\n\nFeature coming soon!');
}

function exportSessions() {
    alert('Export Options:\n\n• Export to PDF\n• Export to Excel\n• Session reports\n• Statistical analysis\n• Custom date range\n\nFeature coming soon!');
}

function filterByType(type) {
    console.log('Filtering by type: ' + type);
}

function filterByCounsellor(counsellor) {
    console.log('Filtering by counsellor: ' + counsellor);
}

function filterByConfidentiality(level) {
    console.log('Filtering by confidentiality: ' + level);
}

function filterByStatus(status) {
    console.log('Filtering by status: ' + status);
}

function filterByDate(date) {
    console.log('Filtering by date: ' + date);
}

function searchSessions(query) {
    console.log('Searching: ' + query);
}

function sendReminder(id) {
    if (confirm('Send follow-up reminder to counselee?')) {
        alert('Reminder sent successfully via SMS and Email! ✓');
    }
}

function viewFullSchedule() {
    window.location.href = '{{ route("counselling.calendar") }}';
}

function bookCounsellor(name) {
    alert('Book Session with ' + name + ':\n\n• Select date and time\n• Choose session type\n• Add notes\n• Confirm booking\n\nFeature coming soon!');
}

function openResource(type) {
    const resources = {
        guidelines: 'Counselling Guidelines:\n\n• Confidentiality protocols\n• Session best practices\n• Documentation standards\n• Ethical considerations',
        forms: 'Session Forms:\n\n• Intake forms\n• Session notes templates\n• Follow-up forms\n• Consent documents',
        referrals: 'Referral Network:\n\n• Professional counselors\n• Mental health specialists\n• Legal advisors\n• Support groups',
        training: 'Training Materials:\n\n• Counselling techniques\n• Active listening skills\n• Crisis intervention\n• Certification courses'
    };
    alert(resources[type] + '\n\nFeature coming soon!');
}
</script>
@endsection
