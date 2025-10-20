@extends('layouts.app')

@section('page-title', 'Welfare Dashboard')
@section('page-subtitle', 'Overview of welfare operations')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-cyan-400/10 to-blue-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">💞 Welfare Dashboard</h1>
                <p class="text-cyan-200 text-lg">Complete welfare overview and statistics</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('welfare.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Transaction
                </a>
                <a href="{{ route('welfare.requests') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-hand-holding-heart mr-2"></i>Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass-card rounded-2xl p-6 gradient-green">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-arrow-up text-white text-2xl"></i>
                <span class="text-3xl font-black text-white">GH₵{{ number_format($totalIncome, 2) }}</span>
            </div>
            <p class="text-white/90 font-medium">Total Income</p>
        </div>

        <div class="glass-card rounded-2xl p-6 gradient-red">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-arrow-down text-white text-2xl"></i>
                <span class="text-3xl font-black text-white">GH₵{{ number_format($totalExpense, 2) }}</span>
            </div>
            <p class="text-white/90 font-medium">Total Expenses</p>
        </div>

        <div class="glass-card rounded-2xl p-6 gradient-{{ $balance >= 0 ? 'blue' : 'orange' }}">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-wallet text-white text-2xl"></i>
                <span class="text-3xl font-black text-white">GH₵{{ number_format($balance, 2) }}</span>
            </div>
            <p class="text-white/90 font-medium">Current Balance</p>
        </div>

        <div class="glass-card rounded-2xl p-6 gradient-purple">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-hand-holding-heart text-white text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ $pendingRequests }}</span>
            </div>
            <p class="text-white/90 font-medium">Pending Requests</p>
        </div>
    </div>

    <!-- Requests Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-clock text-yellow-300 text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ $pendingRequests }}</span>
            </div>
            <p class="text-white/70 font-medium">Pending</p>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-check-circle text-green-300 text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ $approvedRequests }}</span>
            </div>
            <p class="text-white/70 font-medium">Approved</p>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-flag-checkered text-blue-300 text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ $completedRequests }}</span>
            </div>
            <p class="text-white/70 font-medium">Completed</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Transactions -->
        <div class="glass-card rounded-3xl p-6">
            <h2 class="text-2xl font-black text-white mb-6">Recent Transactions</h2>
            
            @if($recentFunds->count() > 0)
                <div class="space-y-3">
                    @foreach($recentFunds as $fund)
                        <div class="glass-card rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg gradient-{{ $fund->type === 'income' ? 'green' : 'red' }} flex items-center justify-center">
                                        <i class="fas fa-{{ $fund->type === 'income' ? 'arrow-up' : 'arrow-down' }} text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ $fund->description }}</p>
                                        <p class="text-white/60 text-sm">{{ $fund->date }}</p>
                                    </div>
                                </div>
                                <p class="text-xl font-bold text-{{ $fund->type === 'income' ? 'green' : 'red' }}-400">
                                    GH₵{{ number_format($fund->amount, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-white/60 text-center py-8">No transactions yet</p>
            @endif
        </div>

        <!-- Recent Requests -->
        <div class="glass-card rounded-3xl p-6">
            <h2 class="text-2xl font-black text-white mb-6">Recent Requests</h2>
            
            @if($recentRequests->count() > 0)
                <div class="space-y-3">
                    @foreach($recentRequests as $request)
                        <div class="glass-card rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-white font-semibold">{{ $request->member_name }}</p>
                                    <p class="text-white/60 text-sm">{{ $request->request_type }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 rounded-lg text-xs font-bold text-white
                                        {{ $request->status === 'pending' ? 'bg-yellow-500' : '' }}
                                        {{ $request->status === 'approved' ? 'bg-green-500' : '' }}
                                        {{ $request->status === 'completed' ? 'bg-blue-500' : '' }}
                                        {{ $request->status === 'declined' ? 'bg-red-500' : '' }}">
                                        {{ strtoupper($request->status) }}
                                    </span>
                                    <p class="text-white/60 text-xs mt-1">GH₵{{ number_format($request->amount_requested, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-white/60 text-center py-8">No requests yet</p>
            @endif
        </div>
    </div>

</div>
@endsection
