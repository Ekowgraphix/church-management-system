@extends('layouts.ministry-leader')

@section('title', 'Prayer Requests')
@section('header', 'Prayer Requests')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-praying-hands text-blue-600 mr-2"></i>
            Prayer Requests
        </h2>
        <div class="flex space-x-2">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>All Requests</option>
                <option>Pending</option>
                <option>Answered</option>
            </select>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($prayers as $prayer)
            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $prayer->title ?? 'Prayer Request' }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-user mr-1"></i>
                            {{ $prayer->member->full_name ?? 'Anonymous' }} • 
                            {{ $prayer->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                        {{ ucfirst($prayer->status ?? 'pending') }}
                    </span>
                </div>
                
                <p class="text-gray-700 mb-4">{{ $prayer->request }}</p>
                
                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                            <i class="fas fa-praying-hands mr-1"></i>Pray for this
                        </button>
                        <button class="text-green-600 hover:text-green-800 text-sm font-semibold">
                            <i class="fas fa-check mr-1"></i>Mark Answered
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-users mr-1"></i>
                        {{ $prayer->prayer_count ?? 0 }} people praying
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-praying-hands text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500">No prayer requests</p>
            </div>
        @endforelse
    </div>

    @if($prayers->hasPages())
        <div class="mt-6">
            {{ $prayers->links() }}
        </div>
    @endif
</div>
@endsection
