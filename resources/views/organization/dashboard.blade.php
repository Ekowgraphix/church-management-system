@extends('layouts.organization')

@section('content')
<div>
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🌍 Organization Dashboard</h1>
                <p class="text-gray-600">Multi-branch oversight and management</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-xs text-gray-500">Last updated: {{ now()->format('h:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-code-branch text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Total Branches</p>
            <p class="text-4xl font-black">{{ $stats['total_branches'] }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Total Members</p>
            <p class="text-4xl font-black">{{ number_format($stats['total_members']) }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-hands-helping text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Total Volunteers</p>
            <p class="text-4xl font-black">{{ $stats['total_volunteers'] }}</p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Current Events</p>
            <p class="text-4xl font-black">{{ $stats['current_events'] }}</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
            </div>
            <p class="text-sm opacity-90 mb-1">Monthly Giving</p>
            <p class="text-3xl font-black">₵{{ number_format($stats['monthly_giving']) }}</p>
        </div>
    </div>

    <!-- AI Insights Panel -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <i class="fas fa-brain text-3xl"></i>
            <h3 class="text-2xl font-bold">AI Insights</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-sm opacity-90 mb-2">📈 Growth Alert</p>
                <p class="text-sm font-semibold">Faith Temple showing 23% member growth this quarter</p>
            </div>
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-sm opacity-90 mb-2">⚠️ Attention Needed</p>
                <p class="text-sm font-semibold">Branch X has declining attendance - Consider intervention</p>
            </div>
            <div class="bg-white/10 rounded-xl p-4">
                <p class="text-sm opacity-90 mb-2">🎯 Top Performer</p>
                <p class="text-sm font-semibold">Grace Centre leads in volunteer engagement (92%)</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Charts Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Member Growth Chart -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📈 Member Growth Rate</h3>
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <p class="text-gray-500">Chart.js visualization area</p>
                </div>
            </div>

            <!-- Giving Trends -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Tithes & Offerings Trend</h3>
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <p class="text-gray-500">Financial trend chart area</p>
                </div>
            </div>
        </div>

        <!-- Real-time Activity Feed -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Real-time Activity</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <p class="font-semibold text-gray-800 text-sm">Faith Temple</p>
                        <p class="text-xs text-gray-600">Sunday attendance: 450</p>
                        <p class="text-xs text-gray-500">2 hours ago</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="font-semibold text-gray-800 text-sm">Grace Centre</p>
                        <p class="text-xs text-gray-600">New member registration: 12</p>
                        <p class="text-xs text-gray-500">3 hours ago</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <p class="font-semibold text-gray-800 text-sm">Hope Sanctuary</p>
                        <p class="text-xs text-gray-600">Youth event completed</p>
                        <p class="text-xs text-gray-500">5 hours ago</p>
                    </div>
                </div>
            </div>

            <!-- Volunteer Engagement Index -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-4">🤝 Volunteer Engagement</h3>
                <div class="text-center">
                    <p class="text-6xl font-black">87%</p>
                    <p class="text-sm opacity-90 mt-2">Overall Engagement Rate</p>
                    <div class="mt-4 bg-white/20 rounded-full h-2">
                        <div class="bg-white rounded-full h-2" style="width: 87%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
