@extends('layouts.app')

@section('page-title', $family->family_name)
@section('page-subtitle', 'Family details and members')

@section('content')
<div>
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-green-300 mb-2">Head of Family</h3>
                <p class="text-white text-lg">{{ $family->headOfFamily->full_name ?? 'Not assigned' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-green-300 mb-2">Total Members</h3>
                <p class="text-white text-lg">{{ $family->member_count }}</p>
            </div>
            @if($family->address)
                <div>
                    <h3 class="text-sm font-semibold text-green-300 mb-2">Address</h3>
                    <p class="text-white">{{ $family->address }}</p>
                    @if($family->city || $family->state)
                        <p class="text-gray-400">{{ $family->city }}{{ $family->city && $family->state ? ', ' : '' }}{{ $family->state }}</p>
                    @endif
                </div>
            @endif
            @if($family->phone)
                <div>
                    <h3 class="text-sm font-semibold text-green-300 mb-2">Phone</h3>
                    <p class="text-white">{{ $family->phone }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl mb-6">
        <h3 class="text-lg font-bold text-white mb-4">Add Family Member</h3>
        <form method="POST" action="{{ route('families.add-member', $family) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <select name="member_id" required class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                <option value="">Select Member</option>
                @foreach($availableMembers as $member)
                    <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                @endforeach
            </select>
            <select name="relationship" required class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                <option value="">Select Relationship</option>
                <option value="head">Head</option>
                <option value="spouse">Spouse</option>
                <option value="child">Child</option>
                <option value="parent">Parent</option>
                <option value="sibling">Sibling</option>
                <option value="other">Other</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Member
            </button>
        </form>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Family Members</h3>
        <div class="space-y-3">
            @forelse($family->members as $member)
                <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 gradient-green rounded-xl flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($member->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $member->full_name }}</p>
                            <p class="text-gray-400 text-sm">{{ ucfirst($member->pivot->relationship) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('families.remove-member', $family) }}" class="inline">
                        @csrf
                        <input type="hidden" name="member_id" value="{{ $member->id }}">
                        <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Remove this member?')">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8">No family members added yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
