@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ $volunteer->name }}! 🙌</h1>
                <p class="text-gray-600">Thank you for serving in God's house</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</p>
                <div class="mt-2">
                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                        <i class="fas fa-check-circle mr-1"></i>Active Volunteer
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Upcoming Events</p>
            <p class="text-4xl font-black">{{ $stats['upcoming_events'] }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-tasks text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Tasks Due This Week</p>
            <p class="text-4xl font-black">{{ $stats['tasks_due'] }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Hours Served</p>
            <p class="text-4xl font-black">{{ $stats['hours_served'] }}</p>
            <p class="text-xs mt-1 opacity-75">This month</p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Task Completion</p>
            <p class="text-4xl font-black">{{ $stats['tasks_completed'] }}%</p>
        </div>
    </div>

    <!-- Progress Tracker -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white mb-6">
        <h3 class="text-xl font-bold mb-3">Monthly Progress</h3>
        <p class="mb-4">You've completed {{ $stats['tasks_completed'] }}% of your assigned tasks this month - Keep it up! 🎯</p>
        <div class="bg-white/20 rounded-full h-4">
            <div class="bg-green-400 rounded-full h-4 transition-all" style="width: {{ $stats['tasks_completed'] }}%"></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('volunteer.events') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-center">
            <i class="fas fa-calendar-check text-blue-600 text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800">View Event Schedule</h3>
        </a>
        <a href="{{ route('volunteer.tasks') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-center">
            <i class="fas fa-check-circle text-green-600 text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800">Complete a Task</h3>
        </a>
        <a href="{{ route('volunteer.communication') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-center">
            <i class="fas fa-comment-dots text-purple-600 text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800">Message Team Lead</h3>
        </a>
        <a href="{{ route('volunteer.prayer') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-center">
            <i class="fas fa-praying-hands text-orange-600 text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800">Submit Prayer</h3>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Events -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📅 Upcoming Events</h3>
            <div class="space-y-3">
                @forelse($upcomingEvents as $event)
                    <div class="p-4 bg-gray-50 rounded-lg flex items-center justify-between hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $event->title }}</p>
                                <p class="text-sm text-gray-600">{{ $event->start_date->format('M d, Y - h:i A') }}</p>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Ushering Team</span>
                            </div>
                        </div>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
                            Check-in
                        </button>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No upcoming events</p>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Scripture of the Day -->
            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-bold mb-3">📖 Scripture of the Day</h3>
                <p class="text-sm italic mb-2">"Whatever you do, work at it with all your heart, as working for the Lord, not for human beings."</p>
                <p class="text-xs opacity-75">- Colossians 3:23</p>
            </div>

            <!-- Next Service Countdown -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">⛪ Next Service</h3>
                <div class="text-center">
                    <div class="text-3xl font-black text-orange-600" id="countdown">Loading...</div>
                    <p class="text-sm text-gray-600 mt-2">Sunday Worship - 9:00 AM</p>
                </div>
            </div>

            <!-- Announcements -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">📢 Announcements</h3>
                <div class="space-y-3 text-sm">
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <p class="font-semibold text-gray-800">Team Meeting</p>
                        <p class="text-gray-600">Saturday 3 PM - All volunteers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Service Countdown Timer
    function updateCountdown() {
        const now = new Date();
        const dayOfWeek = now.getDay();
        const daysUntilSunday = (7 - dayOfWeek) % 7 || 7;
        
        const nextService = new Date(now);
        nextService.setDate(now.getDate() + daysUntilSunday);
        nextService.setHours(9, 0, 0, 0);
        
        const diff = nextService - now;
        
        if (diff > 0) {
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            
            document.getElementById('countdown').innerHTML = `${days}d ${hours}h`;
        } else {
            document.getElementById('countdown').innerHTML = 'Today!';
        }
    }
    
    updateCountdown();
    setInterval(updateCountdown, 60000);
</script>
@endpush
@endsection
