@extends('layouts.app')

@section('page-title', $event->title)
@section('page-subtitle', 'Event details and registrations')

@section('content')
<div>
    <!-- Event Header -->
    <div class="glass-card rounded-2xl overflow-hidden mb-6">
        @if($event->image)
            <div class="h-64 bg-gradient-to-br from-purple-500 to-pink-600 relative">
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            </div>
        @else
            <div class="h-64 bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                <i class="fas fa-calendar-alt text-white text-8xl opacity-50"></i>
            </div>
        @endif
        
        <div class="p-8">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $event->event_type == 'service' ? 'bg-purple-500/20 text-purple-300' : '' }}
                            {{ $event->event_type == 'meeting' ? 'bg-blue-500/20 text-blue-300' : '' }}
                            {{ $event->event_type == 'conference' ? 'bg-green-500/20 text-green-300' : '' }}
                            {{ $event->event_type == 'social' ? 'bg-pink-500/20 text-pink-300' : '' }}
                            {{ $event->event_type == 'outreach' ? 'bg-orange-500/20 text-orange-300' : '' }}
                            {{ $event->event_type == 'training' ? 'bg-cyan-500/20 text-cyan-300' : '' }}">
                            {{ ucfirst($event->event_type) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $event->status == 'upcoming' ? 'bg-blue-500' : '' }}
                            {{ $event->status == 'ongoing' ? 'bg-green-500' : '' }}
                            {{ $event->status == 'completed' ? 'bg-gray-500' : '' }}
                            {{ $event->status == 'cancelled' ? 'bg-red-500' : '' }}
                            text-white">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    
                    @if($event->description)
                        <p class="text-gray-300 mb-4">{{ $event->description }}</p>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center text-gray-300">
                            <i class="fas fa-calendar text-green-400 w-6"></i>
                            <span>{{ $event->start_date->format('M d, Y - h:i A') }}</span>
                        </div>
                        <div class="flex items-center text-gray-300">
                            <i class="fas fa-clock text-green-400 w-6"></i>
                            <span>{{ $event->end_date->format('M d, Y - h:i A') }}</span>
                        </div>
                        @if($event->location)
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-map-marker-alt text-green-400 w-6"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        @endif
                        @if($event->registration_fee > 0)
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-dollar-sign text-green-400 w-6"></i>
                                <span>₵{{ number_format($event->registration_fee, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('events.destroy', $event) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline" onclick="return confirm('Delete this event?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Stats -->
    @if($event->requires_registration)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="glass-card p-6 rounded-2xl">
                <p class="text-gray-400 text-sm mb-1">Total Registered</p>
                <p class="text-4xl font-black text-white">{{ $event->registration_count }}</p>
            </div>
            <div class="glass-card p-6 rounded-2xl">
                <p class="text-gray-400 text-sm mb-1">Attended</p>
                <p class="text-4xl font-black text-green-300">{{ $event->attendance_count }}</p>
            </div>
            @if($event->capacity)
                <div class="glass-card p-6 rounded-2xl">
                    <p class="text-gray-400 text-sm mb-1">Available Spots</p>
                    <p class="text-4xl font-black text-blue-300">{{ $event->available_spots }}</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Registrations List -->
    @if($event->requires_registration && $event->registrations->count() > 0)
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-white">Registrations ({{ $event->registrations->count() }})</h3>
                <a href="{{ route('events.attendees', $event) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-list"></i> View All
                </a>
            </div>
            <div class="space-y-2">
                @foreach($event->registrations->take(10) as $registration)
                    <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between">
                        <div>
                            <p class="text-white font-semibold">{{ $registration->member->full_name }}</p>
                            <p class="text-gray-400 text-sm">Registered: {{ $registration->registered_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $registration->status == 'registered' ? 'bg-blue-500/20 text-blue-300' : '' }}
                            {{ $registration->status == 'attended' ? 'bg-green-500/20 text-green-300' : '' }}
                            {{ $registration->status == 'cancelled' ? 'bg-red-500/20 text-red-300' : '' }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
