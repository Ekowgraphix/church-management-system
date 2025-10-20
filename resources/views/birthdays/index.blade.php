@extends('layouts.app')

@section('content')
<div>
    <!-- Header with Stats -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-black text-white mb-2 flex items-center">
                    <i class="fas fa-birthday-cake mr-3 text-neon-green"></i>
                    Birthday Dashboard
                </h1>
                <p class="text-gray-300">Celebrate with our church family</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
                <button onclick="exportBirthdays()" class="btn btn-secondary">
                    <i class="fas fa-download mr-2"></i> Export
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-pink-500/20 to-purple-500/20 p-6 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Today</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['today_count'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-birthday-cake text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 p-6 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">This Week</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['week_count'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 p-6 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">This Month</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['month_count'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-orange-500/20 to-red-500/20 p-6 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Upcoming (30 days)</p>
                        <p class="text-3xl font-bold text-white">{{ $stats['upcoming_count'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Age Milestones -->
    @if($milestones->count() > 0)
    <div class="glass-card p-6 rounded-2xl mb-6">
        <h2 class="text-2xl font-bold text-white mb-4">🏆 Special Age Milestones This Month</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($milestones as $milestone)
            <div class="bg-gradient-to-r from-yellow-500/20 to-orange-500/20 p-4 rounded-xl border-2 border-yellow-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-white font-bold">{{ $milestone['name'] }}</div>
                        <div class="text-yellow-400 text-sm">Turning {{ $milestone['age'] }} years! 🎊</div>
                    </div>
                    <div class="text-4xl font-bold text-yellow-500">{{ $milestone['age'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Today's Birthdays -->
    <div class="glass-card p-6 rounded-2xl mb-6">
        <h2 class="text-2xl font-bold text-white mb-4">🎉 Today's Birthdays</h2>
        @if($todayBirthdays->count() > 0 || $todayChildrenBirthdays->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($todayBirthdays as $member)
                <div class="bg-gradient-to-r from-pink-500/20 to-purple-500/20 p-4 rounded-xl border-2 border-pink-500/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                {{ strtoupper(substr($member->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-white font-bold text-lg">{{ $member->full_name }}</div>
                                <div class="text-pink-400 text-sm font-semibold">🎂 Turning {{ \Carbon\Carbon::parse($member->date_of_birth)->age }} years</div>
                                <div class="text-gray-400 text-xs">{{ $member->phone }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            @if($member->phone)
                            <button onclick="sendWish('{{ $member->full_name }}', '{{ $member->phone }}', 'sms')" class="btn btn-sm bg-green-500/20 text-green-400 hover:bg-green-500/30">
                                <i class="fas fa-sms mr-1"></i> SMS
                            </button>
                            @endif
                            @if($member->email)
                            <button onclick="sendWish('{{ $member->full_name }}', '{{ $member->email }}', 'email')" class="btn btn-sm bg-blue-500/20 text-blue-400 hover:bg-blue-500/30">
                                <i class="fas fa-envelope mr-1"></i> Email
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @foreach($todayChildrenBirthdays as $child)
                <div class="bg-gradient-to-r from-blue-500/20 to-cyan-500/20 p-4 rounded-xl border-2 border-blue-500/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                {{ strtoupper(substr($child->child_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-white font-bold text-lg">{{ $child->child_name }}</div>
                                <div class="text-blue-400 text-sm font-semibold">🎈 Turning {{ $child->dob->age }} years</div>
                                <div class="text-gray-400 text-xs">Parent: {{ $child->parent_name }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            @if($child->contact)
                            <button onclick="sendWish('{{ $child->parent_name }}', '{{ $child->contact }}', 'sms')" class="btn btn-sm bg-green-500/20 text-green-400 hover:bg-green-500/30">
                                <i class="fas fa-sms mr-1"></i> SMS Parent
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-center py-8">No birthdays today</p>
        @endif
    </div>

    <!-- This Week's Birthdays -->
    <div class="glass-card p-6 rounded-2xl mb-6">
        <h2 class="text-2xl font-bold text-white mb-4">📅 This Week's Birthdays</h2>
        @if($weekBirthdays->count() > 0 || $weekChildrenBirthdays->count() > 0)
            <div class="space-y-3">
                @foreach($weekBirthdays as $member)
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($member->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-white font-semibold">{{ $member->full_name }}</div>
                            <div class="text-gray-400 text-sm">Member</div>
                        </div>
                    </div>
                    <div class="text-gray-300">{{ \Carbon\Carbon::parse($member->date_of_birth)->format('M d') }}</div>
                </div>
                @endforeach
                @foreach($weekChildrenBirthdays as $child)
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($child->child_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-white font-semibold">{{ $child->child_name }}</div>
                            <div class="text-gray-400 text-sm">Child</div>
                        </div>
                    </div>
                    <div class="text-gray-300">{{ $child->dob->format('M d') }}</div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-center py-8">No birthdays this week</p>
        @endif
    </div>

    <!-- Tomorrow's Birthdays -->
    @if($tomorrowBirthdays->count() > 0 || $tomorrowChildrenBirthdays->count() > 0)
    <div class="glass-card p-6 rounded-2xl mb-6">
        <h2 class="text-2xl font-bold text-white mb-4">🌅 Tomorrow's Birthdays</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($tomorrowBirthdays as $member)
            <div class="bg-gradient-to-r from-purple-500/20 to-indigo-500/20 p-4 rounded-xl border border-purple-500/30">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($member->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-white font-semibold">{{ $member->full_name }}</div>
                        <div class="text-purple-400 text-sm">Member</div>
                    </div>
                </div>
            </div>
            @endforeach
            @foreach($tomorrowChildrenBirthdays as $child)
            <div class="bg-gradient-to-r from-blue-500/20 to-cyan-500/20 p-4 rounded-xl border border-blue-500/30">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($child->child_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-white font-semibold">{{ $child->child_name }}</div>
                        <div class="text-blue-400 text-sm">Child</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Upcoming Birthdays (Next 30 Days) -->
    @if($upcomingBirthdays->count() > 0)
    <div class="glass-card p-6 rounded-2xl mb-6">
        <h2 class="text-2xl font-bold text-white mb-4">📅 Upcoming Birthdays (Next 30 Days)</h2>
        <div class="space-y-3">
            @foreach($upcomingBirthdays as $upcoming)
            <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl hover:bg-white/10 transition">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 {{ $upcoming['type'] == 'member' ? 'bg-green-500' : 'bg-blue-500' }} rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($upcoming['name'], 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-white font-semibold">{{ $upcoming['name'] }}</div>
                        <div class="text-gray-400 text-sm">
                            {{ ucfirst($upcoming['type']) }} • Turning {{ $upcoming['age'] }} years
                            @if($upcoming['type'] == 'child' && isset($upcoming['parent']))
                            • Parent: {{ $upcoming['parent'] }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-white font-semibold">{{ $upcoming['date']->format('M d, Y') }}</div>
                    <div class="text-gray-400 text-sm">{{ $upcoming['date']->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- This Month's Birthdays -->
    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-2xl font-bold text-white mb-4">🗓️ This Month's Birthdays</h2>
        @if($monthBirthdays->count() > 0 || $monthChildrenBirthdays->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($monthBirthdays as $member)
                <div class="p-3 bg-white/5 rounded-lg hover:bg-white/10 transition">
                    <div class="flex items-center justify-between">
                        <div class="text-white">{{ $member->full_name }}</div>
                        <div class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($member->date_of_birth)->format('M d') }}</div>
                    </div>
                </div>
                @endforeach
                @foreach($monthChildrenBirthdays as $child)
                <div class="p-3 bg-blue-500/10 rounded-lg hover:bg-blue-500/20 transition">
                    <div class="flex items-center justify-between">
                        <div class="text-white">{{ $child->child_name }}</div>
                        <div class="text-gray-400 text-sm">{{ $child->dob->format('M d') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-center py-8">No birthdays this month</p>
        @endif
    </div>
</div>

<script>
function sendWish(name, recipient, type) {
    const typeText = type === 'sms' ? 'SMS' : 'Email';
    const message = prompt(`Enter birthday message for ${name} (via ${typeText}):`, 'Happy Birthday! 🎉 Wishing you a blessed and joyful day filled with love and happiness. May God continue to bless you abundantly!');
    
    if (message) {
        // Show loading indicator
        const loadingMsg = `Sending ${typeText}...`;
        
        fetch('{{ route("birthdays.send-wish") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                recipient: recipient,
                message: message,
                type: type
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message || `Birthday wish sent via ${typeText} successfully! ✅`);
            } else {
                alert(data.message || `Failed to send ${typeText}. Please try again.`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Failed to send birthday wish via ${typeText}. Please try again.\n\nNote: SMS and Email services need to be configured in your .env file.`);
        });
    }
}

function exportBirthdays() {
    window.location.href = '{{ route("birthdays.index") }}?export=csv';
}
</script>

@endsection
