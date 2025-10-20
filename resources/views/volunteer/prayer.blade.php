@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🕊️ Prayer Requests</h1>
                <p class="text-gray-600">Submit and pray for others</p>
            </div>
            <button class="bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-700">
                <i class="fas fa-plus mr-2"></i>Submit Prayer Request
            </button>
        </div>
    </div>

    <!-- Submit Prayer Form -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Submit Your Prayer Request</h3>
        <form>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Prayer Request</label>
                <textarea rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Share your prayer need..."></textarea>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" class="mr-2">
                    <span class="text-gray-700">Keep this request confidential (Pastoral team only)</span>
                </label>
            </div>
            <button type="submit" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-700">
                <i class="fas fa-paper-plane mr-2"></i>Submit Prayer
            </button>
        </form>
    </div>

    <!-- Prayer Wall -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">💫 Prayer Wall</h3>
        <div class="space-y-4">
            @forelse($prayers as $prayer)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $prayer->member->full_name ?? 'Anonymous' }}</p>
                            <p class="text-sm text-gray-500">{{ $prayer->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold">
                            {{ $prayer->category ?? 'General' }}
                        </span>
                    </div>
                    <p class="text-gray-700 mb-3">{{ $prayer->request }}</p>
                    <div class="flex items-center space-x-3">
                        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700">
                            <i class="fas fa-praying-hands mr-1"></i>I Prayed ({{ rand(5, 20) }})
                        </button>
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                            <i class="fas fa-bible mr-1"></i>AI Scripture
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No prayer requests at this time</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
