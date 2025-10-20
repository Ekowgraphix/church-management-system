@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500">
                    📊 Member Tithe History
                </h1>
                <p class="text-gray-400 mt-2">Complete giving timeline and statistics</p>
            </div>
            <a href="{{ route('tithes.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>

        <!-- Member Profile Card -->
        <div class="glass-card rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-green-500 to-blue-500 flex items-center justify-center text-white text-3xl font-bold mr-6">
                        {{ substr($member->first_name ?? 'U', 0, 1) }}{{ substr($member->last_name ?? 'N', 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white">{{ $member->first_name }} {{ $member->last_name }}</h2>
                        <p class="text-gray-400 mt-1">
                            <i class="fas fa-phone mr-2"></i>{{ $member->phone ?? 'N/A' }}
                            @if($member->email)
                                <span class="ml-4"><i class="fas fa-envelope mr-2"></i>{{ $member->email }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <!-- Loyalty Badge -->
                <div class="text-center">
                    <div class="text-6xl mb-2">{{ $badge['icon'] }}</div>
                    <div class="px-6 py-2 rounded-full bg-{{ $badge['color'] }}-600/20 border border-{{ $badge['color'] }}-500/50">
                        <p class="text-{{ $badge['color'] }}-400 font-bold">{{ $badge['name'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
            <div class="glass-card p-6 rounded-2xl border border-green-500/20">
                <div class="text-center">
                    <i class="fas fa-donate text-green-400 text-3xl mb-3"></i>
                    <h3 class="text-sm text-gray-400 mb-2">Total Given</h3>
                    <p class="text-2xl font-bold text-white">GHS {{ number_format($stats['total'], 2) }}</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-blue-500/20">
                <div class="text-center">
                    <i class="fas fa-calendar-check text-blue-400 text-3xl mb-3"></i>
                    <h3 class="text-sm text-gray-400 mb-2">Total Tithes</h3>
                    <p class="text-2xl font-bold text-white">{{ $stats['count'] }}</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-purple-500/20">
                <div class="text-center">
                    <i class="fas fa-chart-line text-purple-400 text-3xl mb-3"></i>
                    <h3 class="text-sm text-gray-400 mb-2">This Year</h3>
                    <p class="text-2xl font-bold text-white">GHS {{ number_format($stats['this_year'], 2) }}</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-yellow-500/20">
                <div class="text-center">
                    <i class="fas fa-calendar-alt text-yellow-400 text-3xl mb-3"></i>
                    <h3 class="text-sm text-gray-400 mb-2">This Month</h3>
                    <p class="text-2xl font-bold text-white">GHS {{ number_format($stats['this_month'], 2) }}</p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-pink-500/20">
                <div class="text-center">
                    <i class="fas fa-clock text-pink-400 text-3xl mb-3"></i>
                    <h3 class="text-sm text-gray-400 mb-2">First Tithe</h3>
                    <p class="text-xl font-bold text-white">
                        @if($stats['first_tithe'])
                            {{ \Carbon\Carbon::parse($stats['first_tithe'])->format('M Y') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-indigo-500/20">
                <div class="text-center">
                    <i class="fas fa-history text-indigo-400 text-3xl mb-3"></i>
                    <h3 class="text-sm text-gray-400 mb-2">Last Tithe</h3>
                    <p class="text-xl font-bold text-white">
                        @if($stats['last_tithe'])
                            {{ \Carbon\Carbon::parse($stats['last_tithe'])->format('M Y') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Average & Recognition -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-xl font-bold text-white mb-4">
                    <i class="fas fa-calculator text-green-400 mr-2"></i> Giving Analysis
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Average per Tithe:</span>
                        <span class="text-xl font-bold text-green-400">
                            GHS {{ $stats['count'] > 0 ? number_format($stats['total'] / $stats['count'], 2) : '0.00' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Frequency:</span>
                        <span class="text-xl font-bold text-blue-400">{{ $stats['count'] }} times</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Member Since:</span>
                        <span class="text-xl font-bold text-purple-400">
                            @if($stats['first_tithe'])
                                {{ \Carbon\Carbon::parse($stats['first_tithe'])->diffForHumans() }}
                            @else
                                New
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl bg-gradient-to-br from-{{ $badge['color'] }}-600/10 to-{{ $badge['color'] }}-800/10">
                <h3 class="text-xl font-bold text-white mb-4">
                    <i class="fas fa-award text-yellow-400 mr-2"></i> Recognition & Loyalty Status
                </h3>
                <div class="text-center py-6">
                    <div class="text-8xl mb-4">{{ $badge['icon'] }}</div>
                    <h2 class="text-3xl font-bold text-{{ $badge['color'] }}-400 mb-2">{{ $badge['name'] }}</h2>
                    <p class="text-gray-300">
                        @if($badge['name'] == 'Diamond Partner')
                            Outstanding supporter! Total giving exceeds GHS 50,000
                        @elseif($badge['name'] == 'Gold Giver')
                            Exceptional generosity! Total giving exceeds GHS 30,000
                        @elseif($badge['name'] == 'Silver Supporter')
                            Great supporter! Total giving exceeds GHS 15,000
                        @elseif($badge['name'] == 'Faithful Partner')
                            Consistent giver with 12+ tithe contributions
                        @else
                            Keep growing in faithfulness and generosity
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Tithe History Timeline -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-700">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-history text-blue-400 mr-2"></i> Complete Tithe History
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Payment Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Received By</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Remarks</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($tithes as $index => $tithe)
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $tithes->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                <i class="fas fa-calendar-day text-blue-400 mr-2"></i>
                                {{ $tithe->date->format('M d, Y') }}
                                <span class="text-xs text-gray-500 block mt-1">{{ $tithe->date->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-green-400">
                                GHS {{ number_format($tithe->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $tithe->payment_method == 'Cash' ? 'bg-green-500/20 text-green-400' : '' }}
                                    {{ $tithe->payment_method == 'Mobile Money' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                    {{ $tithe->payment_method == 'Bank Transfer' ? 'bg-purple-500/20 text-purple-400' : '' }}
                                    {{ $tithe->payment_method == 'Cheque' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                    {{ $tithe->payment_method == 'Card' ? 'bg-pink-500/20 text-pink-400' : '' }}">
                                    {{ $tithe->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                {{ $tithe->received_by ?: 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ Str::limit($tithe->remarks, 30) ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('tithes.receipt', $tithe->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all text-xs" title="Download Receipt">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-inbox text-5xl mb-4 opacity-50"></i>
                                    <p class="text-lg">No tithe records found for this member</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($tithes->total() > 0)
                    <tfoot class="bg-gray-800/50">
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-sm font-bold text-white">TOTAL</td>
                            <td class="px-6 py-4 text-sm font-bold text-green-400">GHS {{ number_format($stats['total'], 2) }}</td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @if($tithes->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $tithes->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endsection
