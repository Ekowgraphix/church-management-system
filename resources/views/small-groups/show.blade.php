@extends('layouts.app')

@section('page-title', $smallGroup->name)
@section('page-subtitle', 'Group details and members')

@section('content')
<div>
    <!-- Group Info -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $smallGroup->category == 'bible_study' ? 'bg-purple-500/20 text-purple-300' : '' }}
                        {{ $smallGroup->category == 'prayer' ? 'bg-blue-500/20 text-blue-300' : '' }}
                        {{ $smallGroup->category == 'youth' ? 'bg-green-500/20 text-green-300' : '' }}
                        {{ $smallGroup->category == 'men' ? 'bg-cyan-500/20 text-cyan-300' : '' }}
                        {{ $smallGroup->category == 'women' ? 'bg-pink-500/20 text-pink-300' : '' }}
                        {{ $smallGroup->category == 'couples' ? 'bg-red-500/20 text-red-300' : '' }}
                        {{ $smallGroup->category == 'children' ? 'bg-yellow-500/20 text-yellow-300' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $smallGroup->category)) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $smallGroup->is_active ? 'bg-green-500/20 text-green-300' : 'bg-gray-500/20 text-gray-400' }}">
                        {{ $smallGroup->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                @if($smallGroup->description)
                    <p class="text-gray-300 mb-4">{{ $smallGroup->description }}</p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($smallGroup->leader)
                        <div>
                            <p class="text-gray-400 text-sm">Leader</p>
                            <p class="text-white font-semibold">{{ $smallGroup->leader->full_name }}</p>
                        </div>
                    @endif
                    @if($smallGroup->meeting_day)
                        <div>
                            <p class="text-gray-400 text-sm">Meeting Schedule</p>
                            <p class="text-white font-semibold">{{ ucfirst($smallGroup->meeting_day) }}s at {{ $smallGroup->meeting_time ? $smallGroup->meeting_time->format('g:i A') : 'TBD' }}</p>
                        </div>
                    @endif
                    @if($smallGroup->location)
                        <div>
                            <p class="text-gray-400 text-sm">Location</p>
                            <p class="text-white font-semibold">{{ $smallGroup->location }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-400 text-sm">Members</p>
                        <p class="text-white font-semibold">{{ $smallGroup->member_count }}{{ $smallGroup->max_members ? '/' . $smallGroup->max_members : '' }}</p>
                    </div>
                </div>
            </div>

            <div class="flex space-x-2">
                <a href="{{ route('small-groups.edit', $smallGroup) }}" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form method="POST" action="{{ route('small-groups.destroy', $smallGroup) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline" onclick="return confirm('Delete this group?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Member Form -->
    <div class="glass-card p-6 rounded-2xl mb-6">
        <h3 class="text-lg font-bold text-white mb-4">Add Member to Group</h3>
        <form method="POST" action="{{ route('small-groups.join', $smallGroup) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <select name="member_id" required class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                <option value="">Select Member</option>
                @foreach($availableMembers as $member)
                    <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Member
            </button>
        </form>
    </div>

    <!-- Members List -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Group Members ({{ $smallGroup->activeMembers->count() }})</h3>
        <div class="space-y-3">
            @forelse($smallGroup->activeMembers as $member)
                <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 gradient-green rounded-xl flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($member->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $member->full_name }}</p>
                            <p class="text-gray-400 text-sm">{{ ucfirst($member->pivot->role) }} • Joined {{ $member->pivot->joined_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('small-groups.leave', $smallGroup) }}" class="inline">
                        @csrf
                        <input type="hidden" name="member_id" value="{{ $member->id }}">
                        <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Remove this member?')">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8">No members in this group yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
