@extends('layouts.organization')

@section('content')
<div class="p-8">
    <!-- Welcome Header (Matching ChurchMang Style) -->
    <div class="bg-gradient-to-br from-teal-800/40 to-slate-800/40 backdrop-blur-sm rounded-3xl p-8 mb-8 border border-teal-500/20 shadow-2xl shadow-teal-500/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-3">Welcome Back, Admin User! 👋</h1>
                <p class="text-teal-100 text-lg">Here's what's happening in your church today</p>
            </div>
            <div class="bg-gradient-to-br from-teal-600/30 to-teal-700/30 backdrop-blur-md border-2 border-teal-400/40 rounded-2xl px-6 py-4 shadow-lg shadow-teal-500/20">
                <div class="flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-teal-300 text-2xl"></i>
                    <p class="text-white font-bold text-lg">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards (Matching ChurchMang Style) -->
    <div style="display: grid; grid-template-columns: repeat(5, 1fr);" class="gap-6 mb-8">
        <div class="group bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/50 border border-blue-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-code-branch text-3xl"></i>
                </div>
                <span class="text-xs font-extrabold bg-blue-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Total</span>
            </div>
            <p class="text-lg font-bold opacity-90 mb-2">Total Branches</p>
            <p class="text-6xl font-black">{{ $stats['total_branches'] }}</p>
        </div>

        <div class="group bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-green-500/50 border border-green-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-users text-3xl"></i>
                </div>
                <span class="text-xs font-extrabold bg-green-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Active</span>
            </div>
            <p class="text-lg font-bold opacity-90 mb-2">Total Members</p>
            <p class="text-6xl font-black">{{ number_format($stats['total_members']) }}</p>
        </div>

        <div class="group bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/50 border border-purple-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-hands-helping text-3xl"></i>
                </div>
                <span class="text-xs font-extrabold bg-purple-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Volunteers</span>
            </div>
            <p class="text-lg font-bold opacity-90 mb-2">Total Volunteers</p>
            <p class="text-6xl font-black">{{ $stats['total_volunteers'] }}</p>
        </div>

        <div class="group bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/50 border border-orange-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-calendar-alt text-3xl"></i>
                </div>
                <span class="text-xs font-extrabold bg-orange-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Upcoming</span>
            </div>
            <p class="text-lg font-bold opacity-90 mb-2">Current Events</p>
            <p class="text-6xl font-black">{{ $stats['current_events'] }}</p>
        </div>

        <div class="group bg-gradient-to-br from-yellow-500 via-yellow-600 to-yellow-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-yellow-500/50 border border-yellow-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-hand-holding-usd text-3xl"></i>
                </div>
                <span class="text-xs font-extrabold bg-yellow-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Net Income</span>
            </div>
            <p class="text-lg font-bold opacity-90 mb-2">Monthly Giving</p>
            <p class="text-5xl font-black">₵{{ number_format($stats['monthly_giving']) }}</p>
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
