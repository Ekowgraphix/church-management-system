@extends('layouts.app')

@section('page-title', 'Partnership Report')
@section('page-subtitle', 'Overview of all partnerships')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-orange-400/10 to-red-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">📊 Partnership Report</h1>
                <p class="text-orange-200 text-lg">Complete partnership overview and statistics</p>
            </div>
            <a href="{{ route('partnerships.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-list mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card rounded-2xl p-6 gradient-green">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-dollar-sign text-white text-2xl"></i>
                <span class="text-3xl font-black text-white">GH₵{{ number_format($totalPledged, 2) }}</span>
            </div>
            <p class="text-white/90 font-medium">Total Pledged</p>
        </div>

        <div class="glass-card rounded-2xl p-6 gradient-blue">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-check-circle text-white text-2xl"></i>
                <span class="text-3xl font-black text-white">GH₵{{ number_format($totalPaid, 2) }}</span>
            </div>
            <p class="text-white/90 font-medium">Total Paid</p>
        </div>

        <div class="glass-card rounded-2xl p-6 gradient-purple">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-handshake text-white text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ $active }}</span>
            </div>
            <p class="text-white/90 font-medium">Active Partners</p>
        </div>
    </div>

    <div class="glass-card rounded-3xl p-8">
        <h2 class="text-2xl font-black text-white mb-6">All Partnerships</h2>
        
        @if($partnerships->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-left text-white/70 font-semibold py-3 px-4">Partner</th>
                            <th class="text-left text-white/70 font-semibold py-3 px-4">Type</th>
                            <th class="text-right text-white/70 font-semibold py-3 px-4">Pledged</th>
                            <th class="text-right text-white/70 font-semibold py-3 px-4">Paid</th>
                            <th class="text-right text-white/70 font-semibold py-3 px-4">Balance</th>
                            <th class="text-center text-white/70 font-semibold py-3 px-4">Progress</th>
                            <th class="text-center text-white/70 font-semibold py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partnerships as $partnership)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition">
                                <td class="py-4 px-4">
                                    <p class="text-white font-semibold">{{ $partnership->partner_name }}</p>
                                    @if($partnership->contact)
                                        <p class="text-white/60 text-sm">{{ $partnership->contact }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-white">{{ $partnership->partnership_type }}</td>
                                <td class="py-4 px-4 text-right text-white font-semibold">GH₵{{ number_format($partnership->pledged_amount, 2) }}</td>
                                <td class="py-4 px-4 text-right text-green-400 font-semibold">GH₵{{ number_format($partnership->amount_paid, 2) }}</td>
                                <td class="py-4 px-4 text-right text-orange-400 font-semibold">GH₵{{ number_format($partnership->pledged_amount - $partnership->amount_paid, 2) }}</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-center">
                                        <div class="w-20 bg-white/20 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($partnership->amount_paid / $partnership->pledged_amount) * 100 }}%"></div>
                                        </div>
                                        <span class="ml-2 text-white text-sm">{{ number_format(($partnership->amount_paid / $partnership->pledged_amount) * 100, 0) }}%</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-3 py-1 rounded-lg text-xs font-bold text-white
                                        {{ $partnership->status === 'active' ? 'bg-green-500' : '' }}
                                        {{ $partnership->status === 'pending' ? 'bg-yellow-500' : '' }}
                                        {{ $partnership->status === 'expired' ? 'bg-red-500' : '' }}">
                                        {{ strtoupper($partnership->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-white/60 text-center py-8">No partnerships to display</p>
        @endif
    </div>
</div>
@endsection
