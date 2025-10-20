@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Welcome Greeting -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->full_name }}" 
                         class="w-16 h-16 rounded-full object-cover border-4 border-indigo-500">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-black text-2xl">
                        {{ strtoupper(substr($member->first_name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Welcome back, {{ $member->first_name }} 🙏</h1>
                    <p class="text-gray-600">Member ID: {{ $member->member_id }} | {{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">Next Service</div>
                <div class="text-2xl font-bold text-indigo-600" id="countdown">Loading...</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('portal.giving') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hand-holding-usd text-green-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Give Offering</h3>
                    <p class="text-sm text-gray-600">Support the ministry</p>
                </div>
            </div>
        </a>

        <a href="{{ route('small-groups.index') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Join a Group</h3>
                    <p class="text-sm text-gray-600">Connect with others</p>
                </div>
            </div>
        </a>

        <a href="{{ route('prayer-requests.index') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-praying-hands text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Send Prayer Request</h3>
                    <p class="text-sm text-gray-600">We'll pray for you</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Upcoming Events & Devotional -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Upcoming Events -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">📅 Upcoming Events</h3>
                    <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingEvents as $event)
                        <div class="bg-gray-50 p-4 rounded-xl flex items-center justify-between hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $event->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $event->start_date->format('M d, Y - h:i A') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('events.show', $event) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700">
                                RSVP
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No upcoming events</p>
                    @endforelse
                </div>
            </div>

            <!-- Latest Devotional -->
            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center space-x-3 mb-4">
                    <i class="fas fa-book-open text-2xl"></i>
                    <h3 class="text-xl font-bold">📖 Today's Devotional</h3>
                </div>
                @php
                    $todayDevotional = \App\Models\Devotional::today()->published()->first();
                @endphp
                @if($todayDevotional)
                    <div class="bg-white/10 rounded-xl p-4 mb-4">
                        <p class="font-bold mb-2">{{ $todayDevotional->title }}</p>
                        <p class="text-sm opacity-90 mb-2">{{ $todayDevotional->scripture_reference }}</p>
                        <p class="text-sm opacity-80 line-clamp-3">{{ Str::limit($todayDevotional->message, 150) }}</p>
                    </div>
                    <a href="{{ route('devotional.index') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
                        Read Full Devotional
                    </a>
                @else
                    <p class="text-white/80 mb-4">No devotional for today yet. Check back later!</p>
                    <a href="{{ route('devotional.archive') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
                        Browse Archive
                    </a>
                @endif
            </div>
        </div>

        <!-- Right Column: Recent Chats & Attendance Summary -->
        <div class="space-y-6">
            <!-- Recent Messages -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">💬 Recent Chats</h3>
                    <a href="{{ route('chat.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">View All</a>
                </div>
                <div class="space-y-3">
                    @php
                        $recentMessages = \App\Models\Message::where('receiver_id', auth()->id())
                            ->orWhere('sender_id', auth()->id())
                            ->with(['sender', 'receiver'])
                            ->latest()
                            ->take(3)
                            ->get();
                    @endphp
                    @forelse($recentMessages as $msg)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            @php
                                $chatPartner = $msg->sender->id == auth()->id() ? $msg->receiver : $msg->sender;
                                $partnerMember = \App\Models\Member::where('email', $chatPartner->email)->first();
                            @endphp
                            @if($partnerMember && $partnerMember->photo)
                                <img src="{{ asset('storage/' . $partnerMember->photo) }}" 
                                     alt="{{ $chatPartner->name }}"
                                     class="w-10 h-10 rounded-full object-cover border-2 border-green-400">
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($chatPartner->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm">{{ $msg->sender->id == auth()->id() ? $msg->receiver->name : $msg->sender->name }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ Str::limit($msg->message, 30) }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8 text-sm">No recent messages</p>
                        <a href="{{ route('chat.index') }}" class="block text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                            Start Chatting
                        </a>
                    @endforelse
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Attendance Summary</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-600">Total Attendance</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_attendance'] }}</p>
                        </div>
                        <i class="fas fa-calendar-check text-green-600 text-3xl"></i>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-600">This Month</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['attendance_this_month'] }}</p>
                        </div>
                        <i class="fas fa-calendar-day text-blue-600 text-3xl"></i>
                    </div>
                    <a href="{{ route('portal.attendance') }}" class="block text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 font-semibold">
                        View Full History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Service Countdown Timer
    function updateCountdown() {
        // Set next Sunday service at 9:00 AM
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
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            document.getElementById('countdown').innerHTML = `${days}d ${hours}h ${minutes}m`;
        } else {
            document.getElementById('countdown').innerHTML = 'Service Today!';
        }
    }
    
    updateCountdown();
    setInterval(updateCountdown, 60000); // Update every minute
</script>
@endpush
@endsection
