@extends('layouts.app')

@section('page-title', $event->title . ' - Attendees')
@section('page-subtitle', 'Manage event registrations and attendance')

@section('content')
<div>
    <div class="glass-card p-6 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-white mb-2">{{ $event->title }}</h3>
                <p class="text-gray-400">{{ $event->start_date->format('M d, Y - h:i A') }}</p>
            </div>
            <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Event
            </a>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">All Registrations ({{ $registrations->count() }})</h3>
        <div class="space-y-3">
            @forelse($registrations as $registration)
                <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 gradient-green rounded-xl flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($registration->member->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $registration->member->full_name }}</p>
                            <p class="text-gray-400 text-sm">{{ $registration->member->phone }}</p>
                            <p class="text-gray-500 text-xs">Registered: {{ $registration->registered_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $registration->status == 'registered' ? 'bg-blue-500/20 text-blue-300' : '' }}
                            {{ $registration->status == 'attended' ? 'bg-green-500/20 text-green-300' : '' }}
                            {{ $registration->status == 'cancelled' ? 'bg-red-500/20 text-red-300' : '' }}">
                            {{ ucfirst($registration->status) }}
                        </span>
                        @if($registration->status == 'registered')
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-check"></i> Mark Attended
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8">No registrations yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
