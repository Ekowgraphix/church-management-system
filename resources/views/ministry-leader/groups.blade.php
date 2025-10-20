@extends('layouts.ministry-leader')

@section('title', 'Small Groups')
@section('header', 'Small Groups Management')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-user-friends text-green-600 mr-2"></i>
            Small Groups
        </h2>
        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-plus mr-2"></i>Create Group
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($groups as $group)
            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-bold text-gray-800">{{ $group->name }}</h3>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                </div>
                
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($group->description, 80) }}</p>
                
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600">
                        <i class="fas fa-user text-green-600 mr-2"></i>
                        <strong>Leader:</strong> {{ $group->leader->full_name ?? 'Not assigned' }}
                    </p>
                    <p class="text-gray-600">
                        <i class="fas fa-users text-green-600 mr-2"></i>
                        <strong>Members:</strong> {{ $group->members_count ?? 0 }}
                    </p>
                    <p class="text-gray-600">
                        <i class="fas fa-calendar text-green-600 mr-2"></i>
                        <strong>Meeting:</strong> {{ $group->meeting_day ?? 'Not set' }}
                    </p>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                    <button class="text-green-600 hover:text-green-800 text-sm font-semibold">
                        <i class="fas fa-eye mr-1"></i>View Details
                    </button>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-user-friends text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500">No small groups found</p>
            </div>
        @endforelse
    </div>

    @if($groups->hasPages())
        <div class="mt-6">
            {{ $groups->links() }}
        </div>
    @endif
</div>
@endsection
