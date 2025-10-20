@extends('layouts.app')

@section('page-title', 'Small Groups')
@section('page-subtitle', 'Manage and track small groups')

@section('content')
<div>
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-3">
            <form method="GET" class="flex space-x-2">
                <select name="category" onchange="this.form.submit()" 
                        class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    <option value="">All Categories</option>
                    <option value="bible_study" {{ request('category') == 'bible_study' ? 'selected' : '' }}>Bible Study</option>
                    <option value="prayer" {{ request('category') == 'prayer' ? 'selected' : '' }}>Prayer</option>
                    <option value="youth" {{ request('category') == 'youth' ? 'selected' : '' }}>Youth</option>
                    <option value="men" {{ request('category') == 'men' ? 'selected' : '' }}>Men</option>
                    <option value="women" {{ request('category') == 'women' ? 'selected' : '' }}>Women</option>
                    <option value="couples" {{ request('category') == 'couples' ? 'selected' : '' }}>Couples</option>
                    <option value="children" {{ request('category') == 'children' ? 'selected' : '' }}>Children</option>
                </select>
                <select name="status" onchange="this.form.submit()" 
                        class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </form>
        </div>
        <a href="{{ route('small-groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Group
        </a>
    </div>

    <!-- Groups Grid -->
    @if($groups->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($groups as $group)
                <div class="glass-card p-6 rounded-2xl hover:scale-105 transition-transform">
                    <!-- Group Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $group->name }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $group->category == 'bible_study' ? 'bg-purple-500/20 text-purple-300' : '' }}
                                {{ $group->category == 'prayer' ? 'bg-blue-500/20 text-blue-300' : '' }}
                                {{ $group->category == 'youth' ? 'bg-green-500/20 text-green-300' : '' }}
                                {{ $group->category == 'men' ? 'bg-cyan-500/20 text-cyan-300' : '' }}
                                {{ $group->category == 'women' ? 'bg-pink-500/20 text-pink-300' : '' }}
                                {{ $group->category == 'couples' ? 'bg-red-500/20 text-red-300' : '' }}
                                {{ $group->category == 'children' ? 'bg-yellow-500/20 text-yellow-300' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $group->category)) }}
                            </span>
                        </div>
                        <div class="w-12 h-12 gradient-blue rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-friends text-white text-xl"></i>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($group->description)
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $group->description }}</p>
                    @endif

                    <!-- Leader Info -->
                    @if($group->leader)
                        <div class="flex items-center space-x-3 mb-4 p-3 bg-white/5 rounded-xl">
                            <div class="w-10 h-10 gradient-green rounded-xl flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($group->leader->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Leader</p>
                                <p class="text-white font-semibold text-sm">{{ $group->leader->full_name }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Meeting Info -->
                    <div class="space-y-2 mb-4">
                        @if($group->meeting_day)
                            <div class="flex items-center text-sm text-gray-300">
                                <i class="fas fa-calendar text-green-400 w-5"></i>
                                <span>{{ ucfirst($group->meeting_day) }}s at {{ $group->meeting_time ? date('g:i A', strtotime($group->meeting_time)) : 'TBD' }}</span>
                            </div>
                        @endif
                        @if($group->location)
                            <div class="flex items-center text-sm text-gray-300">
                                <i class="fas fa-map-marker-alt text-green-400 w-5"></i>
                                <span>{{ $group->location }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Members Count -->
                    <div class="flex items-center justify-between mb-4 p-3 bg-white/5 rounded-xl">
                        <div>
                            <p class="text-gray-400 text-xs">Members</p>
                            <p class="text-white font-bold">{{ $group->member_count }}{{ $group->max_members ? '/' . $group->max_members : '' }}</p>
                        </div>
                        @if($group->max_members)
                            <div class="text-right">
                                <p class="text-gray-400 text-xs">Available</p>
                                <p class="text-green-300 font-bold">{{ $group->available_spots }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Status Badge -->
                    <div class="mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $group->is_active ? 'bg-green-500/20 text-green-300' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $group->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('small-groups.show', $group) }}" class="btn btn-secondary btn-sm flex-1">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('small-groups.edit', $group) }}" class="btn btn-primary btn-sm btn-icon">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $groups->links() }}
        </div>
    @else
        <div class="glass-card p-16 rounded-2xl text-center">
            <i class="fas fa-user-friends text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-lg mb-2">No small groups found</p>
            <p class="text-gray-500 text-sm mb-6">Create your first small group to get started</p>
            <a href="{{ route('small-groups.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create First Group
            </a>
        </div>
    @endif
</div>
@endsection
