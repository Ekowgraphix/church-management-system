@extends('layouts.app')

@section('content')
<div>
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-users-cog mr-3"></i>
                    Group Management
                </h1>
                <p class="mt-2">Manage church groups, clusters, and cell groups</p>
            </div>
            <a href="{{ route('groups.create') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Create New Group
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Groups</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_groups'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-layer-group text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Members</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_members'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">In Groups</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['members_in_groups'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-user-check text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Without Group</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['members_without_group'] }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-user-minus text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups List -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">All Groups</h2>

        @if($groups->count() > 0)
            <div class="grid grid-cols-3 gap-6">
                @foreach($groups as $group)
                    <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800">{{ $group->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($group->description, 60) }}</p>
                            </div>
                            <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-user-tie w-5 text-gray-400"></i>
                                <span class="ml-2">Leader: {{ $group->leader ? $group->leader->full_name : 'Not assigned' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users w-5 text-gray-400"></i>
                                <span class="ml-2">{{ $group->members_count ?? 0 }} Members</span>
                            </div>
                            @if($group->meeting_day)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar w-5 text-gray-400"></i>
                                    <span class="ml-2">{{ $group->meeting_day }} at {{ $group->meeting_time }}</span>
                                </div>
                            @endif
                            @if($group->location)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                                    <span class="ml-2">{{ $group->location }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('groups.show', $group) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <a href="{{ route('groups.edit', $group) }}" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $groups->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-users-slash text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No groups created yet</p>
                <p class="text-gray-400 text-sm mb-4">Start by creating your first group</p>
                <a href="{{ route('groups.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                    <i class="fas fa-plus-circle mr-2"></i> Create First Group
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
