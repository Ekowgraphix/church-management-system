@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">💸 Finance Overview</h1>
                <p class="text-gray-600">View donations, tithes, and giving trends</p>
            </div>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">This Month</p>
            <p class="text-4xl font-black">₵{{ number_format($monthlyDonations) }}</p>
            <p class="text-xs mt-2 opacity-75">{{ now()->format('F Y') }}</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">This Year</p>
            <p class="text-4xl font-black">₵{{ number_format($yearlyDonations) }}</p>
            <p class="text-xs mt-2 opacity-75">{{ now()->format('Y') }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Average/Month</p>
            <p class="text-4xl font-black">₵{{ number_format($yearlyDonations / max(now()->month, 1)) }}</p>
            <p class="text-xs mt-2 opacity-75">YTD Average</p>
        </div>
    </div>

    <!-- Breakdown by Type -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Breakdown by Type</h3>
            <div class="space-y-4">
                @forelse($donationsByType as $type)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800">{{ ucfirst($type->donation_type) }}</p>
                            <p class="text-sm text-gray-600">This Month</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-gray-800">₵{{ number_format($type->total) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Donations -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Recent Donations</h3>
            <div class="space-y-3">
                @forelse($recentDonations as $donation)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $donation->member->full_name ?? 'Anonymous' }}</p>
                                <p class="text-xs text-gray-600">{{ $donation->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="font-bold text-green-600">₵{{ number_format($donation->amount) }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent donations</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
