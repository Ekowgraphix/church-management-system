@extends('layouts.app')

@section('page-title', 'Welfare Requests')
@section('page-subtitle', 'Manage support requests from members')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-purple-400/10 to-pink-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">🤲 Welfare Requests</h1>
                <p class="text-purple-200 text-lg">Review and manage member support requests</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('welfare.dashboard') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-chart-line mr-2"></i>Dashboard
                </a>
                <a href="{{ route('welfare.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-list mr-2"></i>Funds
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="glass-card rounded-2xl p-4 bg-green-500/20 border border-green-500/30">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-300 text-xl"></i>
                <span class="text-white font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Requests List -->
    <div class="glass-card rounded-3xl p-8">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-black text-white">All Requests ({{ $requests->total() }})</h2>
        </div>

        @if($requests->count() > 0)
            <div class="space-y-4">
                @foreach($requests as $request)
                    <div class="glass-card rounded-2xl p-6 hover:bg-white/10 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 gradient-purple rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($request->member_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ $request->member_name }}</h3>
                                        @if($request->member_contact)
                                            <p class="text-white/60 text-sm">
                                                <i class="fas fa-phone mr-1"></i>{{ $request->member_contact }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <p class="text-white/60 text-sm mb-1">Request Type</p>
                                        <p class="text-white font-semibold">
                                            <i class="fas fa-tag mr-2 text-blue-400"></i>{{ $request->request_type }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-white/60 text-sm mb-1">Amount Requested</p>
                                        <p class="text-white font-semibold">
                                            <i class="fas fa-money-bill mr-2 text-green-400"></i>GH₵{{ number_format($request->amount_requested ?? 0, 2) }}
                                        </p>
                                    </div>

                                    @if($request->amount_approved)
                                        <div>
                                            <p class="text-white/60 text-sm mb-1">Amount Approved</p>
                                            <p class="text-white font-semibold">
                                                <i class="fas fa-check-circle mr-2 text-purple-400"></i>GH₵{{ number_format($request->amount_approved, 2) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="glass-card rounded-xl p-4 mb-4">
                                    <p class="text-white/60 text-sm mb-1">Description</p>
                                    <p class="text-white">{{ $request->description }}</p>
                                </div>

                                @if($request->admin_notes)
                                    <div class="glass-card rounded-xl p-4 bg-blue-500/10 border border-blue-500/30 mb-4">
                                        <p class="text-blue-300 text-sm mb-1">
                                            <i class="fas fa-sticky-note mr-1"></i>Admin Notes
                                        </p>
                                        <p class="text-white">{{ $request->admin_notes }}</p>
                                    </div>
                                @endif

                                <p class="text-white/50 text-xs">
                                    <i class="fas fa-clock mr-1"></i>Submitted {{ $request->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="ml-6 text-right">
                                <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold text-white mb-4
                                    {{ $request->status === 'pending' ? 'bg-yellow-500' : '' }}
                                    {{ $request->status === 'approved' ? 'bg-green-500' : '' }}
                                    {{ $request->status === 'completed' ? 'bg-blue-500' : '' }}
                                    {{ $request->status === 'declined' ? 'bg-red-500' : '' }}">
                                    {{ strtoupper($request->status) }}
                                </span>

                                @if($request->status === 'pending')
                                    <div class="flex flex-col space-y-2">
                                        <form action="{{ route('welfare.requests.approve', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full glass-card px-4 py-2 rounded-lg text-white bg-green-500/20 hover:bg-green-500/30 transition text-sm">
                                                <i class="fas fa-check mr-1"></i>Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('welfare.requests.decline', $request->id) }}" method="POST" onsubmit="return confirm('Decline this request?')">
                                            @csrf
                                            <button type="submit" class="w-full glass-card px-4 py-2 rounded-lg text-white bg-red-500/20 hover:bg-red-500/30 transition text-sm">
                                                <i class="fas fa-times mr-1"></i>Decline
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $requests->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-hand-holding-heart text-white/20 text-6xl mb-4"></i>
                <p class="text-white/60 text-lg">No welfare requests yet</p>
            </div>
        @endif
    </div>

</div>
@endsection
