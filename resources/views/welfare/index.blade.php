@extends('layouts.app')

@section('page-title', 'Welfare Funds')
@section('page-subtitle', 'Track welfare income and expenses')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-cyan-400/10 to-blue-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">💞 Welfare Funds</h1>
                <p class="text-cyan-200 text-lg">Track income, expenses, and manage support</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('welfare.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Transaction
                </a>
                <a href="{{ route('welfare.dashboard') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-chart-line mr-2"></i>Dashboard
                </a>
                <a href="{{ route('welfare.requests') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-hand-holding-heart mr-2"></i>Requests
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

    <!-- Transactions List -->
    <div class="glass-card rounded-3xl p-8">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-black text-white">All Transactions ({{ $funds->total() }})</h2>
        </div>

        @if($funds->count() > 0)
            <div class="space-y-3">
                @foreach($funds as $fund)
                    <div class="glass-card rounded-2xl p-6 hover:bg-white/10 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="w-12 h-12 rounded-lg gradient-{{ $fund->type === 'income' ? 'green' : 'red' }} flex items-center justify-center">
                                    <i class="fas fa-{{ $fund->type === 'income' ? 'arrow-up' : 'arrow-down' }} text-white text-xl"></i>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-bold text-white">{{ $fund->description }}</h3>
                                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $fund->type === 'income' ? 'bg-green-500' : 'bg-red-500' }} text-white">
                                            {{ strtoupper($fund->type) }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div class="text-white/70">
                                            <i class="fas fa-calendar mr-2 text-blue-400"></i>{{ $fund->date }}
                                        </div>

                                        @if($fund->category)
                                            <div class="text-white/70">
                                                <i class="fas fa-tag mr-2 text-purple-400"></i>{{ $fund->category }}
                                            </div>
                                        @endif

                                        @if($fund->approved_by)
                                            <div class="text-white/70">
                                                <i class="fas fa-user-check mr-2 text-green-400"></i>{{ $fund->approved_by }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <p class="text-3xl font-black text-{{ $fund->type === 'income' ? 'green' : 'red' }}-400">
                                    GH₵{{ number_format($fund->amount, 2) }}
                                </p>

                                <div class="flex space-x-2">
                                    <a href="{{ route('welfare.edit', $fund) }}" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-white/10 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('welfare.destroy', $fund) }}" method="POST" onsubmit="return confirm('Delete this transaction?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-red-500/20 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $funds->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-hand-holding-heart text-white/20 text-6xl mb-4"></i>
                <p class="text-white/60 text-lg mb-6">No welfare transactions yet</p>
                <a href="{{ route('welfare.create') }}" class="glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all inline-block">
                    <i class="fas fa-plus mr-2"></i>Add First Transaction
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
