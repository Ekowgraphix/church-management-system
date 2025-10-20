@extends('layouts.app')

@section('page-title', $smallGroup->name . ' - Attendance')
@section('page-subtitle', 'Track group meeting attendance')

@section('content')
<div>
    <div class="glass-card p-6 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-white mb-2">{{ $smallGroup->name }}</h3>
                <p class="text-gray-400">Attendance Records</p>
            </div>
            <a href="{{ route('small-groups.show', $smallGroup) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Group
            </a>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Meeting Attendance</h3>
        <div class="space-y-3">
            @forelse($attendance as $record)
                <div class="bg-white/5 p-4 rounded-xl">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-white font-semibold">{{ $record->meeting_date->format('M d, Y') }}</p>
                            <p class="text-gray-400 text-sm">{{ $record->member->full_name }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $record->status == 'present' ? 'bg-green-500/20 text-green-300' : '' }}
                            {{ $record->status == 'absent' ? 'bg-red-500/20 text-red-300' : '' }}
                            {{ $record->status == 'excused' ? 'bg-yellow-500/20 text-yellow-300' : '' }}">
                            {{ ucfirst($record->status) }}
                        </span>
                    </div>
                    @if($record->notes)
                        <p class="text-gray-400 text-sm">{{ $record->notes }}</p>
                    @endif
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
