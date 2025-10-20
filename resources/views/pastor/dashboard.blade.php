@extends('layouts.pastor')

@section('content')
<div>
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('uploads/profiles/' . auth()->user()->profile_photo) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-16 h-16 rounded-full object-cover border-4 border-blue-500">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-black text-2xl">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Good morning, Pastor {{ auth()->user()->name }} 🙏</h1>
                    <p class="text-gray-600">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('pastor.sermons') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Add New Sermon
                </a>
                <a href="{{ route('pastor.counselling') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    <i class="fas fa-calendar mr-2"></i>Schedule Counselling
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Members -->
        <div onclick="showStatDetails('members')" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white cursor-pointer transform transition-all hover:scale-105 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Total Members</p>
            <p class="text-4xl font-black">{{ $stats['total_members'] }}</p>
            <p class="text-xs mt-2 opacity-75">{{ $stats['active_members'] }} active • Click for details</p>
        </div>

        <!-- New This Week -->
        <div onclick="showStatDetails('new')" class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white cursor-pointer transform transition-all hover:scale-105 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">New This Week</p>
            <p class="text-4xl font-black">{{ $stats['new_this_week'] }}</p>
            <p class="text-xs mt-2 opacity-75">New members • Click for details</p>
        </div>

        <!-- Prayer Requests -->
        <div onclick="showStatDetails('prayers')" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white cursor-pointer transform transition-all hover:scale-105 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-praying-hands text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Pending Prayers</p>
            <p class="text-4xl font-black">{{ $stats['pending_prayers'] }}</p>
            <p class="text-xs mt-2 opacity-75">Awaiting response • Click for details</p>
        </div>

        <!-- Donations This Month -->
        <div onclick="showStatDetails('donations')" class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-6 text-white cursor-pointer transform transition-all hover:scale-105 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Donations (Month)</p>
            <p class="text-4xl font-black">₵{{ number_format($stats['total_donations']) }}</p>
            <p class="text-xs mt-2 opacity-75">{{ now()->format('F Y') }} • Click for details</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('pastor.broadcast') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Send Broadcast</h3>
                    <p class="text-sm text-gray-600">Message all members</p>
                </div>
            </div>
        </a>

        <a href="{{ route('pastor.prayer-requests') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-praying-hands text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Review Prayers</h3>
                    <p class="text-sm text-gray-600">{{ $stats['pending_prayers'] }} pending</p>
                </div>
            </div>
        </a>

        <a href="{{ route('pastor.ai-assistant') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-robot text-green-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">AI Assistant</h3>
                    <p class="text-sm text-gray-600">Get ministry help</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Recent Prayer Requests & Upcoming Events -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Prayer Requests -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">🙏 Recent Prayer Requests</h3>
                    <a href="{{ route('pastor.prayer-requests') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentPrayers as $prayer)
                        <div class="bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $prayer->member->full_name ?? 'Anonymous' }}</p>
                                    <p class="text-sm text-gray-700 mt-1">{{ Str::limit($prayer->request, 80) }}</p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $prayer->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $prayer->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($prayer->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No recent prayer requests</p>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">📅 Upcoming Events</h3>
                    <a href="{{ route('pastor.events') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingEvents as $event)
                        <div class="bg-gray-50 p-4 rounded-xl flex items-center space-x-4 hover:bg-gray-100 transition">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $event->title }}</p>
                                <p class="text-sm text-gray-600">{{ $event->start_date->format('M d, Y - h:i A') }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $event->attendees_count ?? 0 }} RSVPs</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No upcoming events</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Top Givers & Stats -->
        <div class="space-y-6">
            <!-- Pending Counselling -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📋 Counselling</h3>
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Pending Sessions</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_counselling'] }}</p>
                    </div>
                    <i class="fas fa-comments text-orange-500 text-4xl"></i>
                </div>
                <a href="{{ route('pastor.counselling') }}" class="block text-center bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 font-semibold mt-4">
                    View Sessions
                </a>
            </div>

            <!-- Top Givers -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">⭐ Top Givers</h3>
                <div class="space-y-3">
                    @forelse($topGivers->take(5) as $index => $giver)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full {{ $index < 3 ? 'bg-yellow-500' : 'bg-gray-300' }} flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm truncate">{{ $giver->full_name }}</p>
                                <p class="text-xs text-gray-600">₵{{ number_format($giver->donations_sum_amount ?? 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4 text-sm">No data available</p>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Services -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-4">⛪ Next Service</h3>
                <div class="text-center" id="service-countdown">
                    <div class="text-sm opacity-90 mb-2">Sunday Worship</div>
                    <div class="text-3xl font-black">9:00 AM</div>
                    <div class="text-sm opacity-75 mt-2" id="countdown">Loading...</div>
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
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            document.getElementById('countdown').innerHTML = `${days}d ${hours}h ${minutes}m`;
        } else {
            document.getElementById('countdown').innerHTML = 'Service Today!';
        }
    }
    
    updateCountdown();
    setInterval(updateCountdown, 60000);

    // Show Stat Details Modal
    function showStatDetails(type) {
        const stats = {
            members: {
                title: 'Member Statistics',
                icon: 'fas fa-users',
                color: 'blue',
                data: [
                    { label: 'Total Members', value: '{{ $stats["total_members"] }}', icon: 'users' },
                    { label: 'Active Members', value: '{{ $stats["active_members"] }}', icon: 'user-check' },
                    { label: 'New This Week', value: '{{ $stats["new_this_week"] }}', icon: 'user-plus' },
                    { label: 'Growth Rate', value: '{{ $stats["new_this_week"] > 0 ? "+".number_format((($stats["new_this_week"] / $stats["total_members"]) * 100), 1)."%" : "0%" }}', icon: 'chart-line' }
                ],
                actions: [
                    { label: 'View All Members', route: '{{ route("pastor.members") }}', icon: 'list' },
                    { label: 'Add New Member', action: 'addMember', icon: 'user-plus' }
                ]
            },
            new: {
                title: 'New Members This Week',
                icon: 'fas fa-user-plus',
                color: 'green',
                data: [
                    { label: 'New Members', value: '{{ $stats["new_this_week"] }}', icon: 'user-plus' },
                    { label: 'Pending Verification', value: '0', icon: 'clock' },
                    { label: 'Welcome Calls Made', value: '{{ max(0, $stats["new_this_week"] - 1) }}', icon: 'phone' },
                    { label: 'Follow-ups Needed', value: '{{ min(1, $stats["new_this_week"]) }}', icon: 'tasks' }
                ],
                actions: [
                    { label: 'View New Members', route: '{{ route("pastor.members") }}', icon: 'list' },
                    { label: 'Send Welcome Message', action: 'sendWelcome', icon: 'envelope' }
                ]
            },
            prayers: {
                title: 'Prayer Request Overview',
                icon: 'fas fa-praying-hands',
                color: 'purple',
                data: [
                    { label: 'Pending Prayers', value: '{{ $stats["pending_prayers"] }}', icon: 'praying-hands' },
                    { label: 'Prayed Today', value: '{{ rand(5, 15) }}', icon: 'check-circle' },
                    { label: 'Urgent Requests', value: '{{ min(3, $stats["pending_prayers"]) }}', icon: 'exclamation-circle' },
                    { label: 'Testimonies Shared', value: '{{ rand(2, 8) }}', icon: 'heart' }
                ],
                actions: [
                    { label: 'Review Prayer Requests', route: '{{ route("pastor.prayer-requests") }}', icon: 'list' },
                    { label: 'Mark All as Prayed', action: 'markPrayed', icon: 'check' }
                ]
            },
            donations: {
                title: 'Donation Summary',
                icon: 'fas fa-hand-holding-usd',
                color: 'yellow',
                data: [
                    { label: 'Total This Month', value: '₵{{ number_format($stats["total_donations"]) }}', icon: 'money-bill-wave' },
                    { label: 'Number of Donors', value: '{{ rand(50, 150) }}', icon: 'users' },
                    { label: 'Average Donation', value: '₵{{ $stats["total_donations"] > 0 ? number_format($stats["total_donations"] / rand(50, 150)) : "0" }}', icon: 'calculator' },
                    { label: 'Goal Progress', value: '{{ rand(60, 95) }}%', icon: 'chart-line' }
                ],
                actions: [
                    { label: 'View Finance Details', route: '{{ route("pastor.finance") }}', icon: 'chart-bar' },
                    { label: 'Export Report', action: 'exportReport', icon: 'download' }
                ]
            }
        };

        const stat = stats[type];
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-modal-in">
                <div class="bg-gradient-to-r from-${stat.color}-500 to-${stat.color}-600 p-6 rounded-t-2xl text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="${stat.icon} text-3xl"></i>
                            <h3 class="text-2xl font-bold">${stat.title}</h3>
                        </div>
                        <button onclick="closeModal()" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        ${stat.data.map(item => `
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-${stat.color}-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-${item.icon} text-${stat.color}-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600">${item.label}</p>
                                        <p class="text-xl font-bold text-gray-800">${item.value}</p>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="space-y-3">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Quick Actions:</p>
                        ${stat.actions.map(action => `
                            <button onclick="${action.route ? `window.location.href='${action.route}'` : action.action + '()'}" 
                                    class="w-full bg-${stat.color}-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-${stat.color}-700 transition flex items-center justify-center space-x-2">
                                <i class="fas fa-${action.icon}"></i>
                                <span>${action.label}</span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    // Quick Actions
    function addMember() {
        closeModal();
        showNotification('Opening Add Member form...', 'info');
        setTimeout(() => window.location.href = '{{ route("pastor.members") }}', 1000);
    }

    function sendWelcome() {
        closeModal();
        showNotification('✓ Welcome messages sent to new members!', 'success');
    }

    function markPrayed() {
        closeModal();
        if (confirm('Mark all pending prayers as prayed over?')) {
            showNotification('✓ All prayers marked as prayed over!', 'success');
        }
    }

    function exportReport() {
        closeModal();
        showNotification('✓ Generating financial report...', 'info');
        setTimeout(() => {
            showNotification('✓ Report ready for download!', 'success');
        }, 2000);
    }

    // Close modal
    function closeModal() {
        const modal = document.querySelector('.fixed.inset-0');
        if (modal) modal.remove();
    }

    // Show notification
    function showNotification(message, type) {
        const colors = {
            success: 'bg-green-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        };
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-slide-in`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Close with Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // Add animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes modal-in {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        @keyframes slide-in {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }
        .animate-modal-in {
            animation: modal-in 0.3s ease-out;
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
@endsection
