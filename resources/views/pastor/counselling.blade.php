@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">💬 Counselling Sessions</h1>
                <p class="text-gray-600">Manage and track counselling appointments</p>
            </div>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                <i class="fas fa-calendar-plus mr-2"></i>Schedule Session
            </button>
        </div>
    </div>

    <!-- Sessions List -->
    <div class="space-y-4">
        @forelse($sessions as $session)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $session->member->full_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $session->session_date->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-bold {{ $session->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ($session->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-700"><strong>Topic:</strong> {{ $session->topic ?? 'General Counselling' }}</p>
                    @if($session->notes)
                        <p class="text-sm text-gray-700 mt-2"><strong>Notes:</strong> {{ Str::limit($session->notes, 100) }}</p>
                    @endif
                </div>

                <div class="flex space-x-3">
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                        <i class="fas fa-check mr-1"></i>Accept
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
                        <i class="fas fa-calendar mr-1"></i>Reschedule
                    </button>
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700">
                        <i class="fas fa-book mr-1"></i>AI Scriptures
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">No counselling sessions scheduled</p>
            </div>
        @endforelse
    </div>

    @if($sessions->hasPages())
        <div class="mt-6">
            {{ $sessions->links() }}
        </div>
    @endif
</div>
@endsection
