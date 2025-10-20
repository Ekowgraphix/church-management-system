@extends('layouts.app')

@section('content')
<div>
    <!-- Header -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">
                    <i class="fas fa-user-times mr-3 text-orange-400"></i>
                    Absence Notifications
                </h1>
                <p class="text-gray-300">Track and notify absent members</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="sendBulkNotifications()" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i> Send All Notifications
                </button>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Absent Members</p>
                    <p class="text-3xl font-bold text-white">{{ count($absentMembers) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-times text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Last Service</p>
                    <p class="text-xl font-bold text-white">{{ $lastSunday->format('M d, Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Notifications Sent</p>
                    <p class="text-3xl font-bold text-white" id="sent-count">0</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Absent Members List -->
    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-2xl font-bold text-white mb-6">Absent Members</h2>

        @if(count($absentMembers) > 0)
            <div class="space-y-3">
                @foreach($absentMembers as $member)
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl hover:bg-white/10 transition" id="member-{{ $member->id }}">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-orange-500/20 rounded-full flex items-center justify-center">
                            <span class="text-orange-400 font-bold">{{ strtoupper(substr($member->first_name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $member->full_name }}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-400">
                                @if($member->phone)
                                    <span><i class="fas fa-phone mr-1"></i> {{ $member->phone }}</span>
                                @endif
                                @if($member->email)
                                    <span><i class="fas fa-envelope mr-1"></i> {{ $member->email }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if($member->phone)
                        <button onclick="sendNotification({{ $member->id }}, 'sms')" class="btn btn-sm bg-green-500/20 text-green-400 hover:bg-green-500/30">
                            <i class="fas fa-sms mr-1"></i> SMS
                        </button>
                        @endif
                        @if($member->email)
                        <button onclick="sendNotification({{ $member->id }}, 'email')" class="btn btn-sm bg-blue-500/20 text-blue-400 hover:bg-blue-500/30">
                            <i class="fas fa-envelope mr-1"></i> Email
                        </button>
                        @endif
                        <a href="{{ route('attendance.member-history', $member) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-history"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-check-circle text-6xl text-green-400 mb-4"></i>
                <p class="text-white text-xl font-bold mb-2">No Absences!</p>
                <p class="text-gray-400">All active members attended the last service</p>
            </div>
        @endif
    </div>

    <!-- Absence History -->
    <div class="glass-card p-6 rounded-2xl mt-6">
        <h2 class="text-2xl font-bold text-white mb-6">Frequently Absent Members</h2>
        <p class="text-gray-400 text-sm mb-4">Members who have missed 3 or more services in the last month</p>
        
        <div class="space-y-3">
            <!-- This would be populated with data from a query -->
            <p class="text-gray-400 text-center py-8">Feature coming soon...</p>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="success-toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg hidden transform transition-all">
    <div class="flex items-center space-x-3">
        <i class="fas fa-check-circle text-2xl"></i>
        <div>
            <p class="font-bold">Notification Sent!</p>
            <p id="success-text" class="text-sm"></p>
        </div>
    </div>
</div>

<!-- Error Toast -->
<div id="error-toast" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg hidden transform transition-all">
    <div class="flex items-center space-x-3">
        <i class="fas fa-exclamation-circle text-2xl"></i>
        <div>
            <p class="font-bold">Failed to Send</p>
            <p id="error-text" class="text-sm"></p>
        </div>
    </div>
</div>

<script>
function sendNotification(memberId, type) {
    // Show loading state
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    // Simulate sending notification (replace with actual API call)
    setTimeout(() => {
        // Success
        showSuccess(`${type.toUpperCase()} notification sent successfully`);
        
        // Update sent count
        const sentCount = document.getElementById('sent-count');
        sentCount.textContent = parseInt(sentCount.textContent) + 1;
        
        // Reset button
        button.innerHTML = originalHTML;
        button.disabled = false;
        
        // Optionally mark as sent
        const memberCard = document.getElementById(`member-${memberId}`);
        if (!memberCard.querySelector('.sent-badge')) {
            const badge = document.createElement('span');
            badge.className = 'sent-badge px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full ml-2';
            badge.innerHTML = '<i class="fas fa-check mr-1"></i> Notified';
            memberCard.querySelector('.text-white.font-semibold').appendChild(badge);
        }
    }, 1000);
}

function sendBulkNotifications() {
    if (!confirm('Send notifications to all absent members?')) return;
    
    const buttons = document.querySelectorAll('[onclick^="sendNotification"]');
    let delay = 0;
    
    buttons.forEach(button => {
        setTimeout(() => button.click(), delay);
        delay += 500;
    });
}

function showSuccess(message) {
    document.getElementById('success-text').textContent = message;
    const toast = document.getElementById('success-toast');
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

function showError(message) {
    document.getElementById('error-text').textContent = message;
    const toast = document.getElementById('error-toast');
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}
</script>
@endsection
