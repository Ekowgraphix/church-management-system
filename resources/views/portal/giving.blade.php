@extends('layouts.member-portal')

@section('page-title', 'My Giving')
@section('page-subtitle', 'View your donation history')

@section('content')
<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">Total Giving</p>
            <p class="text-4xl font-black text-white">₵{{ number_format($stats['total']) }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">This Year</p>
            <p class="text-4xl font-black text-green-300">₵{{ number_format($stats['this_year']) }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">This Month</p>
            <p class="text-4xl font-black text-blue-300">₵{{ number_format($stats['this_month']) }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">Total Donations</p>
            <p class="text-4xl font-black text-purple-300">{{ $stats['count'] }}</p>
        </div>
    </div>

    <!-- Donations List -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Donation History</h3>
        <div class="space-y-3">
            @forelse($donations as $donation)
                <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-white font-semibold">₵{{ number_format($donation->amount, 2) }}</p>
                        <p class="text-gray-400 text-sm">{{ $donation->donation_date->format('M d, Y') }} • {{ $donation->donation_type }}</p>
                        @if($donation->payment_method)
                            <p class="text-gray-500 text-xs">{{ ucfirst($donation->payment_method) }}</p>
                        @endif
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-300">
                        Received
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-center py-8">No donations recorded yet</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection
