@extends('layouts.ministry-leader')

@section('title', 'Events')
@section('header', 'Events Management')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
            Ministry Events
        </h2>
        <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-plus mr-2"></i>Create Event
        </button>
    </div>

    <div class="space-y-4">
        @forelse($events as $event)
            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <h3 class="text-lg font-bold text-gray-800">{{ $event->name }}</h3>
                            <span class="ml-3 px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">
                                {{ ucfirst($event->status ?? 'upcoming') }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-3">{{ $event->description }}</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <p class="text-gray-600">
                                <i class="fas fa-calendar text-purple-600 mr-2"></i>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                            </p>
                            <p class="text-gray-600">
                                <i class="fas fa-clock text-purple-600 mr-2"></i>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('h:i A') }}
                            </p>
                            <p class="text-gray-600">
                                <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                                {{ $event->location ?? 'Location TBD' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="ml-4 flex space-x-2">
                        <button class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="px-3 py-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition text-sm">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500">No events scheduled</p>
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
