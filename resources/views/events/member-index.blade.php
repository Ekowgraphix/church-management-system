@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">📅 Church Events</h1>
        <p class="text-gray-300">Discover and register for upcoming events</p>
    </div>

    <!-- Filter Options -->
    <div class="mb-6">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="type" onchange="this.form.submit()" 
                    class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                <option value="">All Types</option>
                <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service</option>
                <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                <option value="social" {{ request('type') == 'social' ? 'selected' : '' }}>Social</option>
                <option value="outreach" {{ request('type') == 'outreach' ? 'selected' : '' }}>Outreach</option>
                <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>Training</option>
            </select>
            <select name="status" onchange="this.form.submit()" 
                    class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                <option value="">All Status</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </form>
    </div>

    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">
                    <!-- Event Image -->
                    <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-600 relative overflow-hidden">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-6xl opacity-50"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $event->status == 'upcoming' ? 'bg-blue-500' : '' }}
                                {{ $event->status == 'ongoing' ? 'bg-green-500' : '' }}
                                {{ $event->status == 'completed' ? 'bg-gray-500' : '' }}
                                text-white">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $event->event_type == 'service' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $event->event_type == 'meeting' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $event->event_type == 'conference' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $event->event_type == 'social' ? 'bg-pink-100 text-pink-700' : '' }}
                                {{ $event->event_type == 'outreach' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $event->event_type == 'training' ? 'bg-cyan-100 text-cyan-700' : '' }}">
                                {{ ucfirst($event->event_type) }}
                            </span>
                            @if($event->registration_fee > 0)
                                <span class="text-green-600 font-bold">₵{{ number_format($event->registration_fee) }}</span>
                            @else
                                <span class="text-gray-500 text-sm">Free</span>
                            @endif
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $event->description }}</p>

                        <!-- Date & Location -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-calendar text-indigo-500 w-5"></i>
                                <span>{{ $event->start_date->format('M d, Y - h:i A') }}</span>
                            </div>
                            @if($event->location)
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-map-marker-alt text-indigo-500 w-5"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                            @endif
                            @if($event->capacity)
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-users text-indigo-500 w-5"></i>
                                    <span>Capacity: {{ $event->capacity }} people</span>
                                </div>
                            @endif
                        </div>

                        <!-- View Details Button -->
                        <a href="{{ route('events.show', $event) }}" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                            View Details & RSVP
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No Events Found</h3>
            <p class="text-gray-600">Check back later for upcoming events!</p>
        </div>
    @endif
</div>
@endsection
