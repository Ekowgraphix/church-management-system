@extends('layouts.ministry-leader')

@section('title', 'Members')
@section('header', 'Ministry Members')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-users text-purple-600 mr-2"></i>
            Members Directory
        </h2>
        <div class="flex space-x-3">
            <input type="text" placeholder="Search members..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($members as $member)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                <div class="flex items-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->full_name) }}&background=9333ea&color=fff" 
                         alt="{{ $member->full_name }}" 
                         class="w-12 h-12 rounded-full mr-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $member->full_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $member->email }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600">
                        <i class="fas fa-phone text-purple-600 mr-2"></i>
                        {{ $member->phone ?? 'N/A' }}
                    </p>
                    <p class="text-gray-600">
                        <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                        {{ $member->address ?? 'No address' }}
                    </p>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <button class="text-purple-600 hover:text-purple-800 text-sm font-semibold">
                        <i class="fas fa-eye mr-1"></i>View Details
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500">No members found</p>
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
