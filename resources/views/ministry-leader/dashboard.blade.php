@extends('layouts.ministry-leader')

@section('title', 'Ministry Leader Dashboard')
@section('header', 'Ministry Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Members Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Members</p>
                <h3 class="text-3xl font-bold mt-2">{{ $totalMembers }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Active Groups Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Active Groups</p>
                <h3 class="text-3xl font-bold mt-2">{{ $activeGroups }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-user-friends text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Upcoming Events Card -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Upcoming Events</p>
                <h3 class="text-3xl font-bold mt-2">{{ $upcomingEvents->count() }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Monthly Donations Card -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Monthly Giving</p>
                <h3 class="text-3xl font-bold mt-2">GH₵{{ number_format($monthlyDonations, 2) }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <i class="fas fa-hand-holding-usd text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Upcoming Events -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                Upcoming Events
            </h3>
            <a href="{{ route('ministry-leader.events') }}" class="text-purple-600 hover:text-purple-800 text-sm">
                View All →
            </a>
        </div>
        
        <div class="space-y-3">
            @forelse($upcomingEvents as $event)
                <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="bg-purple-100 rounded-lg p-3 mr-4">
                        <i class="fas fa-calendar text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $event->name }}</h4>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y - h:i A') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No upcoming events</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Prayer Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-praying-hands text-blue-600 mr-2"></i>
                Recent Prayer Requests
            </h3>
            <a href="{{ route('ministry-leader.prayer-requests') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                View All →
            </a>
        </div>
        
        <div class="space-y-3">
            @forelse($recentPrayers as $prayer)
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <h4 class="font-semibold text-gray-800">{{ $prayer->title ?? 'Prayer Request' }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($prayer->request, 60) }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="far fa-clock mr-1"></i>
                        {{ $prayer->created_at->diffForHumans() }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No prayer requests</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
        Quick Actions
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('ministry-leader.members') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
            <i class="fas fa-users text-3xl text-blue-600 mb-2"></i>
            <span class="text-sm font-semibold text-gray-700">View Members</span>
        </a>
        
        <a href="{{ route('ministry-leader.groups') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <i class="fas fa-user-friends text-3xl text-green-600 mb-2"></i>
            <span class="text-sm font-semibold text-gray-700">Manage Groups</span>
        </a>
        
        <a href="{{ route('ministry-leader.reports') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
            <i class="fas fa-chart-line text-3xl text-purple-600 mb-2"></i>
            <span class="text-sm font-semibold text-gray-700">View Reports</span>
        </a>
        
        <a href="{{ route('ministry-leader.communication') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
            <i class="fas fa-comments text-3xl text-orange-600 mb-2"></i>
            <span class="text-sm font-semibold text-gray-700">Send Message</span>
        </a>
    </div>
</div>
@endsection
