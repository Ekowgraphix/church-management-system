@extends('layouts.member-portal')

@section('page-title', 'My Attendance')
@section('page-subtitle', 'View your attendance records')

@section('content')
<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">Total Attendance</p>
            <p class="text-4xl font-black text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">This Year</p>
            <p class="text-4xl font-black text-green-300">{{ $stats['this_year'] }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">This Month</p>
            <p class="text-4xl font-black text-blue-300">{{ $stats['this_month'] }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">Streak</p>
            <p class="text-4xl font-black text-purple-300">{{ $stats['streak'] }}</p>
            <p class="text-gray-400 text-xs mt-1">weeks</p>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Attendance History</h3>
        <div class="space-y-3">
            @forelse($attendance as $record)
                <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-white font-semibold">{{ $record->attendance_date->format('l, M d, Y') }}</p>
                        <p class="text-gray-400 text-sm">Check-in: {{ $record->check_in_time ? $record->check_in_time->format('h:i A') : 'N/A' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-300">
                        <i class="fas fa-check mr-1"></i> Present
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8">No attendance records yet</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $attendance->links() }}
        </div>
    </div>
</div>
@endsection
