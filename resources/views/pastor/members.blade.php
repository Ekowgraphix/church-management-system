@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">👥 Church Members</h1>
                <p class="text-gray-600">View and manage member information</p>
            </div>
            <div class="flex space-x-3">
                <input type="text" placeholder="Search members..." class="border border-gray-300 rounded-lg px-4 py-2 w-64">
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>
    </div>

    <!-- Members Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($members as $member)
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($member->first_name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800">{{ $member->full_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $member->member_id }}</p>
                    </div>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <span>{{ $member->phone }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <span>{{ $member->email }}</span>
                    </div>
                    @if($member->address)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                            <span>{{ Str::limit($member->address, 30) }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex space-x-2">
                    <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
                        <i class="fas fa-eye mr-1"></i>View Profile
                    </button>
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                        <i class="fas fa-envelope"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">No members found</p>
            </div>
        @endforelse
    </div>

    @if($members->hasPages())
        <div class="mt-6">
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection
