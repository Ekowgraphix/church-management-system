@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📅 Programs & Events</h1>
                <p class="text-gray-600">Manage church programs and activities</p>
            </div>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Create New Event
            </button>
        </div>
    </div>

    <!-- Events List -->
    <div class="space-y-4">
        @forelse($events as $event)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex flex-col items-center justify-center">
                            <span class="text-2xl font-bold text-blue-600">{{ $event->start_date->format('d') }}</span>
                            <span class="text-xs text-blue-600">{{ $event->start_date->format('M') }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>
                            <p class="text-gray-600 mb-3">{{ Str::limit($event->description, 120) }}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span><i class="fas fa-clock mr-1"></i>{{ $event->start_date->format('h:i A') }}</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location ?? 'Church Hall' }}</span>
                                <span><i class="fas fa-users mr-1"></i>{{ $event->attendees_count ?? 0 }} RSVPs</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
                            <i class="fas fa-bell"></i> Remind
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

    @if($events->hasPages())
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
