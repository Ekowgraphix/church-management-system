@extends('layouts.app')

@section('page-title', 'Analytics Dashboard')
@section('page-subtitle', 'Real-time insights and trends')

@section('content')
<div>
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Members -->
        <div class="glass-card p-6 rounded-2xl hover:scale-105 transition-transform cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 gradient-blue rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <span class="text-xs text-green-300 font-semibold">
                    <i class="fas fa-arrow-up"></i> {{ $stats['new_members_this_month'] }} this month
                </span>
            </div>
            <p class="text-gray-400 text-sm mb-1">Total Members</p>
            <p class="text-4xl font-black text-white">{{ number_format($stats['total_members']) }}</p>
            <div class="mt-3 flex items-center text-xs text-gray-400">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                {{ $stats['active_members'] }} Active
            </div>
        </div>

        <!-- Total Donations -->
        <div class="glass-card p-6 rounded-2xl hover:scale-105 transition-transform cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 gradient-green rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
                <span class="text-xs text-green-300 font-semibold">
                    <i class="fas fa-arrow-up"></i> ₵{{ number_format($stats['donations_this_month']) }}
                </span>
            </div>
            <p class="text-gray-400 text-sm mb-1">Total Donations</p>
            <p class="text-4xl font-black text-white">₵{{ number_format($stats['total_donations']) }}</p>
            <div class="mt-3 text-xs text-gray-400">
                This month: ₵{{ number_format($stats['donations_this_month']) }}
            </div>
        </div>

        <!-- Avg Attendance -->
        <div class="glass-card p-6 rounded-2xl hover:scale-105 transition-transform cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 gradient-purple rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-white text-xl"></i>
                </div>
                <span class="text-xs text-green-300 font-semibold">
                    <i class="fas fa-chart-line"></i> Trending
                </span>
            </div>
            <p class="text-gray-400 text-sm mb-1">Avg Attendance</p>
            <p class="text-4xl font-black text-white">{{ number_format($stats['avg_attendance']) }}</p>
            <div class="mt-3 text-xs text-gray-400">
                Per day this month
            </div>
        </div>

        <!-- Total Visitors -->
        <div class="glass-card p-6 rounded-2xl hover:scale-105 transition-transform cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 gradient-cyan rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <span class="text-xs text-green-300 font-semibold">
                    <i class="fas fa-arrow-up"></i> {{ $stats['visitors_this_month'] }} new
                </span>
            </div>
            <p class="text-gray-400 text-sm mb-1">Total Visitors</p>
            <p class="text-4xl font-black text-white">{{ number_format($stats['total_visitors']) }}</p>
            <div class="mt-3 text-xs text-gray-400">
                {{ $stats['visitors_this_month'] }} this month
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Attendance Trend -->
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">Attendance Trend</h3>
                    <p class="text-sm text-gray-400">Last 30 days</p>
                </div>
                <select id="attendancePeriod" onchange="updateChart('attendance')" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:border-green-500">
                    <option value="7">7 Days</option>
                    <option value="30" selected>30 Days</option>
                    <option value="90">90 Days</option>
                </select>
            </div>
            <canvas id="attendanceChart" height="250"></canvas>
        </div>

        <!-- Donation Trend -->
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">Donation Trend</h3>
                    <p class="text-sm text-gray-400">Last 30 days</p>
                </div>
                <select id="donationPeriod" onchange="updateChart('donations')" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:border-green-500">
                    <option value="7">7 Days</option>
                    <option value="30" selected>30 Days</option>
                    <option value="90">90 Days</option>
                </select>
            </div>
            <canvas id="donationChart" height="250"></canvas>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Member Growth -->
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">Member Growth</h3>
                    <p class="text-sm text-gray-400">Cumulative growth</p>
                </div>
                <select id="memberPeriod" onchange="updateChart('members')" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:border-green-500">
                    <option value="7">7 Days</option>
                    <option value="30" selected>30 Days</option>
                    <option value="90">90 Days</option>
                </select>
            </div>
            <canvas id="memberChart" height="250"></canvas>
        </div>

        <!-- Visitor Trend -->
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">Visitor Trend</h3>
                    <p class="text-sm text-gray-400">New visitors</p>
                </div>
                <select id="visitorPeriod" onchange="updateChart('visitors')" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm focus:outline-none focus:border-green-500">
                    <option value="7">7 Days</option>
                    <option value="30" selected>30 Days</option>
                    <option value="90">90 Days</option>
                </select>
            </div>
            <canvas id="visitorChart" height="250"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartConfig = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(255, 255, 255, 0.1)'
            },
            ticks: {
                color: '#9ca3af'
            }
        },
        x: {
            grid: {
                color: 'rgba(255, 255, 255, 0.05)'
            },
            ticks: {
                color: '#9ca3af'
            }
        }
    }
};

let charts = {};

function createChart(id, type, data, color) {
    const ctx = document.getElementById(id).getContext('2d');
    
    if (charts[id]) {
        charts[id].destroy();
    }
    
    charts[id] = new Chart(ctx, {
        type: type,
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: color + '40',
                borderColor: color,
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: chartConfig
    });
}

function updateChart(type) {
    const periodSelect = document.getElementById(type + 'Period');
    const period = periodSelect ? periodSelect.value : '30';
    
    fetch(`{{ route('analytics.data') }}?type=${type}&period=${period}`)
        .then(response => response.json())
        .then(data => {
            switch(type) {
                case 'attendance':
                    createChart('attendanceChart', 'line', data, '#a855f7');
                    break;
                case 'donations':
                    createChart('donationChart', 'bar', data, '#22c55e');
                    break;
                case 'members':
                    createChart('memberChart', 'line', data, '#3b82f6');
                    break;
                case 'visitors':
                    createChart('visitorChart', 'bar', data, '#06b6d4');
                    break;
            }
        });
}

// Initialize all charts
document.addEventListener('DOMContentLoaded', function() {
    updateChart('attendance');
    updateChart('donations');
    updateChart('members');
    updateChart('visitors');
});
</script>
@endsection
