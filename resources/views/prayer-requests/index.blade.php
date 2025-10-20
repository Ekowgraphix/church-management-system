@extends('layouts.app')

@section('content')
<div>
    <!-- Header -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2 flex items-center">
                    <i class="fas fa-praying-hands mr-3 text-neon-green"></i>
                    Prayer Requests
                </h1>
                <p class="text-gray-300">Manage and track prayer needs</p>
            </div>
            <a href="{{ route('prayer-requests.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> New Prayer Request
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Total Requests</p>
                    <p class="text-3xl font-black text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="neon-icon-box">
                    <i class="fas fa-list text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Pending</p>
                    <p class="text-3xl font-black text-yellow-400">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-yellow-500/20 rounded-full p-4">
                    <i class="fas fa-clock text-2xl text-yellow-400"></i>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Answered</p>
                    <p class="text-3xl font-black text-neon-green">{{ $stats['answered'] }}</p>
                </div>
                <div class="neon-icon-box">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Public</p>
                    <p class="text-3xl font-black text-white">{{ $stats['public'] }}</p>
                </div>
                <div class="bg-blue-500/20 rounded-full p-4">
                    <i class="fas fa-globe text-2xl text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Prayer Requests List -->
    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-2xl font-bold text-white mb-6">All Prayer Requests</h2>

        @if($prayerRequests->count() > 0)
            <div class="space-y-4">
                @foreach($prayerRequests as $request)
                    <div class="bg-white/5 rounded-xl p-6 hover:bg-white/10 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-bold text-white">{{ $request->title }}</h3>
                                    @if($request->is_urgent)
                                        <span class="px-3 py-1 bg-red-500/20 text-red-400 text-xs rounded-full font-semibold">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Urgent
                                        </span>
                                    @endif
                                    @if($request->is_public)
                                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full font-semibold">
                                            <i class="fas fa-globe mr-1"></i> Public
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-300 mb-3">{{ Str::limit($request->description, 150) }}</p>
                                <div class="flex items-center space-x-4 text-sm text-gray-400">
                                    <span><i class="fas fa-user mr-1"></i> {{ $request->member ? $request->member->full_name : 'Anonymous' }}</span>
                                    <span><i class="fas fa-tag mr-1"></i> {{ ucfirst($request->category) }}</span>
                                    <span><i class="fas fa-calendar mr-1"></i> {{ $request->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="px-4 py-2 rounded-lg text-sm font-semibold
                                    {{ $request->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                    {{ $request->status === 'praying' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                    {{ $request->status === 'answered' ? 'bg-green-500/20 text-neon-green' : '' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                                <a href="{{ route('prayer-requests.show', $request) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $prayerRequests->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-praying-hands text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg">No prayer requests yet</p>
                <a href="{{ route('prayer-requests.create') }}" class="btn btn-primary mt-4">
                    <i class="fas fa-plus mr-2"></i> Submit First Request
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
