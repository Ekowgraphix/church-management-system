@extends('layouts.app')

@section('page-title', 'Partnership Details')
@section('page-subtitle', 'View partner information')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white mb-2">🤝 {{ $partnership->partner_name }}</h1>
            <p class="text-green-200">Partnership Details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('partnerships.edit', $partnership) }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('partnerships.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-3xl p-8">
                <h2 class="text-2xl font-black text-white mb-6">Partner Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-white/60 text-sm mb-2">Partner Name</p>
                        <p class="text-white text-lg font-semibold">{{ $partnership->partner_name }}</p>
                    </div>

                    <div>
                        <p class="text-white/60 text-sm mb-2">Partnership Type</p>
                        <p class="text-white text-lg font-semibold">{{ $partnership->partnership_type }}</p>
                    </div>

                    @if($partnership->contact)
                    <div>
                        <p class="text-white/60 text-sm mb-2">Contact</p>
                        <p class="text-white text-lg font-semibold">
                            <i class="fas fa-phone mr-2 text-green-400"></i>{{ $partnership->contact }}
                        </p>
                    </div>
                    @endif

                    @if($partnership->email)
                    <div>
                        <p class="text-white/60 text-sm mb-2">Email</p>
                        <p class="text-white text-lg font-semibold">
                            <i class="fas fa-envelope mr-2 text-blue-400"></i>{{ $partnership->email }}
                        </p>
                    </div>
                    @endif

                    <div>
                        <p class="text-white/60 text-sm mb-2">Start Date</p>
                        <p class="text-white text-lg font-semibold">{{ $partnership->start_date }}</p>
                    </div>

                    @if($partnership->renewal_date)
                    <div>
                        <p class="text-white/60 text-sm mb-2">Renewal Date</p>
                        <p class="text-white text-lg font-semibold">{{ $partnership->renewal_date }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-white/60 text-sm mb-2">Status</p>
                        <span class="px-4 py-2 rounded-xl text-sm font-bold text-white
                            {{ $partnership->status === 'active' ? 'bg-green-500' : '' }}
                            {{ $partnership->status === 'pending' ? 'bg-yellow-500' : '' }}
                            {{ $partnership->status === 'expired' ? 'bg-red-500' : '' }}">
                            {{ strtoupper($partnership->status) }}
                        </span>
                    </div>
                </div>

                @if($partnership->notes)
                <div class="mt-6 glass-card rounded-2xl p-6">
                    <p class="text-white/60 text-sm mb-2">Notes</p>
                    <p class="text-white">{{ $partnership->notes }}</p>
                </div>
                @endif
            </div>

            <div class="glass-card rounded-3xl p-8">
                <h2 class="text-2xl font-black text-white mb-6">Record Payment</h2>
                <form action="{{ route('partnerships.payment', $partnership) }}" method="POST" class="flex space-x-4">
                    @csrf
                    <input type="number" name="payment_amount" step="0.01" min="0" required
                        placeholder="Amount"
                        class="flex-1 px-6 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-green-400 transition">
                    <button type="submit" class="glass-card px-8 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-green-500/20 to-blue-500/20 hover:scale-105 transition-all">
                        <i class="fas fa-plus mr-2"></i>Add Payment
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass-card rounded-2xl p-6 gradient-green">
                <p class="text-white/80 text-sm mb-2">Pledged Amount</p>
                <p class="text-4xl font-black text-white">GH₵{{ number_format($partnership->pledged_amount, 2) }}</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-blue">
                <p class="text-white/80 text-sm mb-2">Amount Paid</p>
                <p class="text-4xl font-black text-white">GH₵{{ number_format($partnership->amount_paid, 2) }}</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-orange">
                <p class="text-white/80 text-sm mb-2">Balance</p>
                <p class="text-4xl font-black text-white">GH₵{{ number_format($partnership->pledged_amount - $partnership->amount_paid, 2) }}</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-purple">
                <p class="text-white/80 text-sm mb-2">Payment Progress</p>
                <p class="text-4xl font-black text-white">{{ number_format(($partnership->amount_paid / $partnership->pledged_amount) * 100, 1) }}%</p>
                <div class="w-full bg-white/20 rounded-full h-3 mt-4">
                    <div class="bg-white h-3 rounded-full" style="width: {{ ($partnership->amount_paid / $partnership->pledged_amount) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
