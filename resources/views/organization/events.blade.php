@extends('layouts.organization')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🕊️ Events & Campaigns</h1>
                <p class="text-gray-600">Manage organization-wide events and campaigns</p>
            </div>
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>Create Campaign
            </button>
        </div>
    </div>

    <!-- Campaign Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Active Campaigns</p>
                    <p class="text-3xl font-bold text-gray-800">8</p>
                </div>
                <i class="fas fa-bullhorn text-blue-600 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Participants</p>
                    <p class="text-3xl font-bold text-gray-800">3,240</p>
                </div>
                <i class="fas fa-users text-green-600 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Upcoming Events</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $events->count() }}</p>
                </div>
                <i class="fas fa-calendar-alt text-purple-600 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Completion Rate</p>
                    <p class="text-3xl font-bold text-gray-800">92%</p>
                </div>
                <i class="fas fa-check-circle text-orange-600 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- AI Suggestion -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <i class="fas fa-lightbulb text-2xl"></i>
            <h3 class="text-xl font-bold">AI Suggestion</h3>
        </div>
        <p>Based on past engagement, the best day/time for outreach is <strong>Saturday 3-5 PM</strong>. Consider scheduling your next campaign during this window.</p>
    </div>

    <!-- Events List -->
    <div class="space-y-4">
        @forelse($events as $event)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <span class="px-4 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Organization-Wide</span>
                            <span class="text-gray-600 text-sm">{{ $event->start_date->format('M d, Y') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600 mb-3">{{ Str::limit($event->description, 150) }}</p>
                        <div class="flex items-center space-x-6 text-sm text-gray-500">
                            <span><i class="fas fa-users mr-1"></i>{{ rand(100, 500) }} Registered</span>
                            <span><i class="fas fa-map-marker-alt mr-1"></i>Multi-Branch</span>
                            <span><i class="fas fa-user-tie mr-1"></i>Coordinator: Pastor Owusu</span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
                            <i class="fas fa-chart-line"></i> Stats
                        </button>
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">No events scheduled</p>
            </div>
        @endforelse
    </div>
</div>

<script>
// Create Campaign Button
document.querySelector('.bg-green-600').addEventListener('click', function() {
    alert('➕ Create New Campaign\n\nOpening campaign creation form...');
    // TODO: Open modal or redirect to campaign form
});

// Stats Buttons
document.querySelectorAll('.bg-blue-600').forEach(btn => {
    btn.addEventListener('click', function() {
        alert('📊 Event Statistics\n\nShowing detailed event analytics...');
        // TODO: Show event statistics modal
    });
});

// Edit Buttons
document.querySelectorAll('.fa-edit').forEach(btn => {
    if(btn.parentElement.classList.contains('bg-green-600')) {
        btn.parentElement.addEventListener('click', function() {
            alert('✏️ Edit Event\n\nOpening event editor...');
            // TODO: Open edit form
        });
    }
});
</script>
@endsection
