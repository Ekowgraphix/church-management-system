@extends('layouts.app')

@section('page-title', 'Events Management')
@section('page-subtitle', 'Manage church events and registrations')

@section('content')
<div>
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-3">
            <form method="GET" class="flex space-x-2">
                <select name="type" onchange="this.form.submit()" 
                        class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    <option value="">All Types</option>
                    <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service</option>
                    <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                    <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                    <option value="social" {{ request('type') == 'social' ? 'selected' : '' }}>Social</option>
                    <option value="outreach" {{ request('type') == 'outreach' ? 'selected' : '' }}>Outreach</option>
                    <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>Training</option>
                </select>
                <select name="status" onchange="this.form.submit()" 
                        class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    <option value="">All Status</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Event
        </a>
    </div>

    <!-- Events Grid -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="glass-card rounded-2xl overflow-hidden hover:scale-105 transition-transform cursor-pointer">
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
                                {{ $event->status == 'cancelled' ? 'bg-red-500' : '' }}
                                text-white">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $event->event_type == 'service' ? 'bg-purple-500/20 text-purple-300' : '' }}
                                {{ $event->event_type == 'meeting' ? 'bg-blue-500/20 text-blue-300' : '' }}
                                {{ $event->event_type == 'conference' ? 'bg-green-500/20 text-green-300' : '' }}
                                {{ $event->event_type == 'social' ? 'bg-pink-500/20 text-pink-300' : '' }}
                                {{ $event->event_type == 'outreach' ? 'bg-orange-500/20 text-orange-300' : '' }}
                                {{ $event->event_type == 'training' ? 'bg-cyan-500/20 text-cyan-300' : '' }}">
                                {{ ucfirst($event->event_type) }}
                            </span>
                            @if($event->registration_fee > 0)
                                <span class="text-green-300 font-bold">₵{{ number_format($event->registration_fee) }}</span>
                            @else
                                <span class="text-gray-400 text-sm">Free</span>
                            @endif
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $event->description }}</p>

                        <!-- Date & Location -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-300">
                                <i class="fas fa-calendar text-green-400 w-5"></i>
                                <span>{{ $event->start_date->format('M d, Y - h:i A') }}</span>
                            </div>
                            @if($event->location)
                                <div class="flex items-center text-sm text-gray-300">
                                    <i class="fas fa-map-marker-alt text-green-400 w-5"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Registration Info -->
                        @if($event->requires_registration)
                            <div class="flex items-center justify-between mb-4 p-3 bg-white/5 rounded-xl">
                                <div class="text-sm">
                                    <p class="text-gray-400">Registered</p>
                                    <p class="text-white font-bold">{{ $event->registration_count }}{{ $event->capacity ? '/' . $event->capacity : '' }}</p>
                                </div>
                                @if($event->capacity)
                                    <div class="text-right text-sm">
                                        <p class="text-gray-400">Available</p>
                                        <p class="text-green-300 font-bold">{{ $event->available_spots }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-sm flex-1">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-primary btn-sm btn-icon">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @else
        <div class="glass-card p-16 rounded-2xl text-center">
            <i class="fas fa-calendar-times text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-lg mb-2">No events found</p>
            <p class="text-gray-500 text-sm mb-6">Create your first event to get started</p>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create First Event
            </a>
        </div>
    @endif
</div>
@endsection
