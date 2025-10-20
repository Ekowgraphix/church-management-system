@extends('layouts.app')

@section('page-title', 'Volunteer Assignments')
@section('page-subtitle', 'Schedule and manage volunteer assignments')

@section('content')
<div>
    <div class="glass-card p-6 rounded-2xl mb-6">
        <h3 class="text-lg font-bold text-white mb-4">Schedule New Assignment</h3>
        <form method="POST" action="{{ route('volunteers.schedule') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @csrf
            <div>
                <select name="volunteer_role_id" required class="w-full px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="member_id" required class="w-full px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    <option value="">Select Member</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="date" name="assignment_date" required class="w-full px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
            </div>
            <div>
                <input type="time" name="start_time" class="w-full px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-full">
                    <i class="fas fa-plus"></i> Schedule
                </button>
            </div>
        </form>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Upcoming Assignments</h3>
        <div class="space-y-3">
            @forelse($assignments as $assignment)
                <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-white font-semibold">{{ $assignment->member->full_name }}</p>
                        <p class="text-gray-400 text-sm">{{ $assignment->role->name }} - {{ $assignment->assignment_date->format('M d, Y') }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $assignment->status == 'scheduled' ? 'bg-blue-500/20 text-blue-300' : '' }}
                        {{ $assignment->status == 'confirmed' ? 'bg-green-500/20 text-green-300' : '' }}
                        {{ $assignment->status == 'completed' ? 'bg-gray-500/20 text-gray-300' : '' }}
                        {{ $assignment->status == 'cancelled' ? 'bg-red-500/20 text-red-300' : '' }}">
                        {{ ucfirst($assignment->status) }}
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8">No assignments scheduled</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
