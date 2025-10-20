@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">✅ Task Manager</h1>
                <p class="text-gray-600">View and complete your assigned tasks</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Completion Rate</p>
                <p class="text-4xl font-bold text-green-600">80%</p>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="space-y-4">
        @foreach($tasks as $task)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-800">{{ $task->title }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $task->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-3">{{ $task->instructions }}</p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span><i class="fas fa-calendar mr-1"></i>Due: {{ $task->deadline->format('M d, Y') }}</span>
                            <span><i class="fas fa-clock mr-1"></i>{{ $task->deadline->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex items-center space-x-3">
                        <button class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Mark Complete
                        </button>
                        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                            <i class="fas fa-upload mr-2"></i>Upload Proof
                        </button>
                        <button class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700">
                            <i class="fas fa-robot mr-2"></i>AI Help
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Badges Section -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white mt-6">
        <h3 class="text-xl font-bold mb-4">🎖️ Your Badges</h3>
        <div class="flex space-x-4">
            <div class="bg-white/20 rounded-lg p-4 text-center">
                <i class="fas fa-medal text-3xl mb-2"></i>
                <p class="text-sm font-semibold">5 Tasks</p>
            </div>
            <div class="bg-white/20 rounded-lg p-4 text-center">
                <i class="fas fa-star text-3xl mb-2"></i>
                <p class="text-sm font-semibold">Punctual</p>
            </div>
            <div class="bg-white/20 rounded-lg p-4 text-center">
                <i class="fas fa-trophy text-3xl mb-2"></i>
                <p class="text-sm font-semibold">Top Volunteer</p>
            </div>
        </div>
    </div>
</div>
@endsection
