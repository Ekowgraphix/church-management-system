@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back to K.G.C Emmanuel Temple')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Banner -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-green-400/10 to-blue-400/10 border border-green-300/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">
                    Welcome Back, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-green-200 text-lg">Here's what's happening in your church today</p>
            </div>
            <div class="flex items-center space-x-3 text-white bg-white/10 px-6 py-3 rounded-xl backdrop-blur-sm border border-white/20">
                <i class="fas fa-calendar-alt text-green-300 text-lg"></i>
                <span class="font-semibold">{{ now()->format('l, F d, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <a href="{{ route('members.create') }}" class="glass-card p-6 rounded-2xl hover:scale-105 transition-all group">
            <div class="w-14 h-14 gradient-blue rounded-xl flex items-center justify-center mb-3 group-hover:rotate-6 transition-transform">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
            <p class="text-white font-semibold">Add Member</p>
        </a>
        
        <a href="{{ route('attendance.index') }}" class="glass-card p-6 rounded-2xl hover:scale-105 transition-all group">
            <div class="w-14 h-14 gradient-purple rounded-xl flex items-center justify-center mb-3 group-hover:rotate-6 transition-transform">
                <i class="fas fa-check-circle text-white text-2xl"></i>
            </div>
            <p class="text-white font-semibold">Attendance</p>
        </a>
        
        <a href="{{ route('donations.create') }}" class="glass-card p-6 rounded-2xl hover:scale-105 transition-all group">
            <div class="w-14 h-14 gradient-orange rounded-xl flex items-center justify-center mb-3 group-hover:rotate-6 transition-transform">
                <i class="fas fa-dollar-sign text-white text-2xl"></i>
            </div>
            <p class="text-white font-semibold">Add Donation</p>
        </a>
        
        <a href="{{ route('events.create') }}" class="glass-card p-6 rounded-2xl hover:scale-105 transition-all group">
            <div class="w-14 h-14 gradient-pink rounded-xl flex items-center justify-center mb-3 group-hover:rotate-6 transition-transform">
                <i class="fas fa-calendar-plus text-white text-2xl"></i>
            </div>
            <p class="text-white font-semibold">New Event</p>
        </a>
        
        <a href="{{ route('sms.create') }}" class="glass-card p-6 rounded-2xl hover:scale-105 transition-all group">
            <div class="w-14 h-14 gradient-cyan rounded-xl flex items-center justify-center mb-3 group-hover:rotate-6 transition-transform">
                <i class="fas fa-sms text-white text-2xl"></i>
            </div>
            <p class="text-white font-semibold">Send SMS</p>
        </a>
        
        <a href="{{ route('prayer-requests.create') }}" class="glass-card p-6 rounded-2xl hover:scale-105 transition-all group">
            <div class="w-14 h-14 gradient-green rounded-xl flex items-center justify-center mb-3 group-hover:rotate-6 transition-transform">
                <i class="fas fa-praying-hands text-white text-2xl"></i>
            </div>
            <p class="text-white font-semibold">Prayer</p>
        </a>
    </div>

    <!-- Key Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members -->
        <div class="glass-card rounded-2xl p-6 gradient-blue transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-white/70 text-sm font-medium">TOTAL</p>
                    <p class="text-3xl font-black text-white">{{ number_format($stats['total_members']) }}</p>
                </div>
            </div>
            <div class="border-t border-white/10 pt-3 flex items-center justify-between">
                <span class="text-white/80 text-sm font-medium">Members</span>
                <span class="text-white/60 text-xs">+{{ $stats['new_members_this_month'] }} this month</span>
            </div>
        </div>

        <!-- Active Members -->
        <div class="glass-card rounded-2xl p-6 gradient-green transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-check text-white text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-white/70 text-sm font-medium">ACTIVE</p>
                    <p class="text-3xl font-black text-white">{{ number_format($stats['active_members']) }}</p>
                </div>
            </div>
            <div class="border-t border-white/10 pt-3 flex items-center justify-between">
                <span class="text-white/80 text-sm font-medium">Active Members</span>
                <span class="text-white/60 text-xs">{{ number_format(($stats['active_members']/$stats['total_members'])*100, 1) }}% rate</span>
            </div>
        </div>

        <!-- Financial Balance -->
        <div class="glass-card rounded-2xl p-6 gradient-orange transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-white text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-white/70 text-sm font-medium">NET INCOME</p>
                    <p class="text-3xl font-black text-white">GH₵{{ number_format($stats['net_income'], 0) }}</p>
                </div>
            </div>
            <div class="border-t border-white/10 pt-3 flex items-center justify-between">
                <span class="text-white/80 text-sm font-medium">This Month</span>
                <span class="text-white/60 text-xs">
                    @if($stats['net_income'] > 0)
                        <i class="fas fa-arrow-up"></i> Profit
                    @else
                        <i class="fas fa-arrow-down"></i> Deficit
                    @endif
                </span>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="glass-card rounded-2xl p-6 gradient-purple transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
                <div class="text-right">
                    <p class="text-white/70 text-sm font-medium">UPCOMING</p>
                    <p class="text-3xl font-black text-white">{{ $stats['total_events'] }}</p>
                </div>
            </div>
            <div class="border-t border-white/10 pt-3 flex items-center justify-between">
                <span class="text-white/80 text-sm font-medium">Events</span>
                <span class="text-white/60 text-xs">{{ $quickStats['events_this_week'] }} this week</span>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Visitors -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-user-plus text-cyan-300 text-xl"></i>
                <span class="text-2xl font-bold text-white">{{ $stats['total_visitors'] }}</span>
            </div>
            <p class="text-white/70 text-sm font-medium">Visitors</p>
        </div>

        <!-- Prayer Requests -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-praying-hands text-purple-300 text-xl"></i>
                <span class="text-2xl font-bold text-white">{{ $stats['pending_prayers'] }}</span>
            </div>
            <p class="text-white/70 text-sm font-medium">Prayers</p>
        </div>

        <!-- Small Groups -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-user-friends text-blue-300 text-xl"></i>
                <span class="text-2xl font-bold text-white">{{ $stats['total_small_groups'] }}</span>
            </div>
            <p class="text-white/70 text-sm font-medium">Groups</p>
        </div>

        <!-- Equipment -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-tools text-orange-300 text-xl"></i>
                <span class="text-2xl font-bold text-white">{{ $stats['total_equipment'] }}</span>
            </div>
            <p class="text-white/70 text-sm font-medium">Equipment</p>
        </div>

        <!-- Children -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-child text-pink-300 text-xl"></i>
                <span class="text-2xl font-bold text-white">{{ $stats['total_children'] }}</span>
            </div>
            <p class="text-white/70 text-sm font-medium">Children</p>
        </div>

        <!-- Media Files -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-photo-video text-green-300 text-xl"></i>
                <span class="text-2xl font-bold text-white">{{ $stats['total_media_files'] }}</span>
            </div>
            <p class="text-white/70 text-sm font-medium">Media</p>
        </div>
    </div>

    <!-- Financial Overview & New Modules -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Financial Breakdown -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-2xl font-black text-white mb-6">Financial Overview</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-green rounded-lg flex items-center justify-center">
                            <i class="fas fa-hand-holding-usd text-white"></i>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm">Donations</p>
                            <p class="text-white font-bold">GH₵{{ number_format($stats['total_donations'], 2) }}</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-up text-green-400"></i>
                </div>

                <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-purple rounded-lg flex items-center justify-center">
                            <i class="fas fa-donate text-white"></i>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm">Tithes</p>
                            <p class="text-white font-bold">GH₵{{ number_format($stats['total_tithes'], 2) }}</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-up text-green-400"></i>
                </div>

                <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-blue rounded-lg flex items-center justify-center">
                            <i class="fas fa-hand-holding-heart text-white"></i>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm">Offerings</p>
                            <p class="text-white font-bold">GH₵{{ number_format($stats['total_offerings'], 2) }}</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-up text-green-400"></i>
                </div>

                <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-orange rounded-lg flex items-center justify-center">
                            <i class="fas fa-receipt text-white"></i>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm">Expenses</p>
                            <p class="text-white font-bold">GH₵{{ number_format($stats['total_expenses'], 2) }}</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-down text-red-400"></i>
                </div>

                <div class="border-t border-white/10 pt-4 mt-4">
                    <div class="flex items-center justify-between">
                        <p class="text-white font-bold">Net Balance</p>
                        <p class="text-2xl font-black text-{{ $stats['net_income'] >= 0 ? 'green' : 'red' }}-400">
                            GH₵{{ number_format($stats['net_income'], 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Modules Stats -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-2xl font-black text-white mb-6">Ministry Modules</h3>
            <div class="space-y-4">
                <!-- Welfare -->
                <a href="{{ route('welfare.index') }}" class="block p-4 bg-white/5 rounded-xl hover:bg-white/10 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-cyan rounded-lg flex items-center justify-center">
                                <i class="fas fa-heart text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Welfare</p>
                                <p class="text-white/60 text-sm">{{ $stats['welfare_requests_pending'] }} pending requests</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-white/40 group-hover:text-white transition"></i>
                    </div>
                </a>

                <!-- Partnerships -->
                <a href="{{ route('partnerships.index') }}" class="block p-4 bg-white/5 rounded-xl hover:bg-white/10 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-orange rounded-lg flex items-center justify-center">
                                <i class="fas fa-handshake text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Partnerships</p>
                                <p class="text-white/60 text-sm">{{ $stats['active_partnerships'] }} active partners</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-white/40 group-hover:text-white transition"></i>
                    </div>
                </a>

                <!-- Counselling -->
                <a href="{{ route('counselling.index') }}" class="block p-4 bg-white/5 rounded-xl hover:bg-white/10 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-blue rounded-lg flex items-center justify-center">
                                <i class="fas fa-hands-helping text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Counselling</p>
                                <p class="text-white/60 text-sm">{{ $stats['counselling_sessions'] }} sessions this month</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-white/40 group-hover:text-white transition"></i>
                    </div>
                </a>

                <!-- Children Ministry -->
                <a href="{{ route('children.index') }}" class="block p-4 bg-white/5 rounded-xl hover:bg-white/10 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-pink rounded-lg flex items-center justify-center">
                                <i class="fas fa-child text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Children Ministry</p>
                                <p class="text-white/60 text-sm">{{ $stats['total_children'] }} registered</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-white/40 group-hover:text-white transition"></i>
                    </div>
                </a>

                <!-- Media -->
                <a href="{{ route('media.index') }}" class="block p-4 bg-white/5 rounded-xl hover:bg-white/10 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-purple rounded-lg flex items-center justify-center">
                                <i class="fas fa-photo-video text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Media</p>
                                <p class="text-white/60 text-sm">{{ $stats['total_media_files'] }} files</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-white/40 group-hover:text-white transition"></i>
                    </div>
                </a>

                <!-- Birthdays -->
                <a href="{{ route('birthdays.index') }}" class="block p-4 bg-white/5 rounded-xl hover:bg-white/10 transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-pink rounded-lg flex items-center justify-center">
                                <i class="fas fa-birthday-cake text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Birthdays</p>
                                <p class="text-white/60 text-sm">{{ $quickStats['birthdays_this_month'] }} this month</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-white/40 group-hover:text-white transition"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- Upcoming Birthdays -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-black text-white">Upcoming Birthdays 🎂</h3>
                <a href="{{ route('birthdays.index') }}" class="text-green-300 hover:text-green-200 text-sm font-medium">View All →</a>
            </div>
            <div class="space-y-3">
                @forelse($upcomingBirthdays as $member)
                    <div class="flex items-center space-x-3 p-3 bg-white/5 rounded-xl hover:bg-white/10 transition">
                        <div class="w-12 h-12 gradient-pink rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($member->first_name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-white">{{ $member->full_name }}</p>
                            <div class="flex items-center text-sm text-white/60">
                                <i class="fas fa-calendar-day mr-1"></i>
                                <span>{{ $member->date_of_birth ? $member->date_of_birth->format('M d') : 'N/A' }}</span>
                            </div>
                        </div>
                        <a href="tel:{{ $member->phone }}" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-green-500 transition">
                            <i class="fas fa-phone text-white text-sm"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-8 text-white/60">
                        <i class="fas fa-birthday-cake text-4xl mb-3 opacity-50"></i>
                        <p>No upcoming birthdays</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities & Upcoming Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-2xl font-black text-white mb-6">Recent Activities</h3>
            <div class="space-y-3">
                @forelse($recentMembers->take(5) as $member)
                    <div class="flex items-center space-x-3 p-4 bg-white/5 rounded-xl">
                        <div class="w-10 h-10 gradient-blue rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($member->first_name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-white">{{ $member->full_name }}</p>
                            <p class="text-white/60 text-sm">New member joined</p>
                        </div>
                        <span class="text-white/40 text-xs">{{ $member->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-white/60 text-center py-8">No recent activities</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-black text-white">Upcoming Events</h3>
                <a href="{{ route('events.index') }}" class="text-green-300 hover:text-green-200 text-sm font-medium">View All →</a>
            </div>
            <div class="space-y-3">
                @forelse($upcomingEvents as $event)
                    <div class="flex items-center space-x-3 p-4 bg-white/5 rounded-xl hover:bg-white/10 transition group">
                        <div class="w-12 h-12 gradient-purple rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-white truncate">{{ $event->title }}</p>
                            <p class="text-white/60 text-sm">{{ $event->event_date ? $event->event_date->format('M d, Y • g:i A') : 'Date TBA' }}</p>
                        </div>
                        <i class="fas fa-arrow-right text-white/40 group-hover:text-white transition"></i>
                    </div>
                @empty
                    <div class="text-center py-8 text-white/60">
                        <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
                        <p>No upcoming events</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Insights -->
    <div class="glass-card rounded-2xl p-6">
        <h3 class="text-2xl font-black text-white mb-6">Today's Insights</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-white/5 rounded-xl">
                <div class="w-16 h-16 gradient-blue rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-check text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-black text-white mb-2">{{ $quickStats['attendance_today'] }}</p>
                <p class="text-white/60 text-sm font-medium">Attendance Today</p>
            </div>

            <div class="text-center p-6 bg-white/5 rounded-xl">
                <div class="w-16 h-16 gradient-purple rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-week text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-black text-white mb-2">{{ $quickStats['events_this_week'] }}</p>
                <p class="text-white/60 text-sm font-medium">Events This Week</p>
            </div>

            <div class="text-center p-6 bg-white/5 rounded-xl">
                <div class="w-16 h-16 gradient-pink rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-birthday-cake text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-black text-white mb-2">{{ $quickStats['birthdays_this_month'] }}</p>
                <p class="text-white/60 text-sm font-medium">Birthdays This Month</p>
            </div>

            <div class="text-center p-6 bg-white/5 rounded-xl">
                <div class="w-16 h-16 gradient-orange rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-praying-hands text-white text-2xl"></i>
                </div>
                <p class="text-3xl font-black text-white mb-2">{{ $quickStats['new_prayers_this_week'] }}</p>
                <p class="text-white/60 text-sm font-medium">New Prayers This Week</p>
            </div>
        </div>
    </div>

</div>
@endsection
