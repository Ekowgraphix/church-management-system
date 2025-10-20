@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">📊 Equipment Analytics</h1>
                    <p class="text-purple-100 text-lg">Comprehensive insights into your church assets</p>
                </div>
                <a href="{{ route('equipment.index') }}" class="bg-white text-purple-600 px-6 py-3 rounded-xl font-semibold hover:bg-purple-50 transition-all shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Equipment
                </a>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-boxes text-blue-500 text-2xl"></i>
                    <span class="text-sm text-gray-500">Total</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalEquipment }}</h3>
                <p class="text-sm text-gray-600 mt-1">Equipment Items</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-dollar-sign text-green-500 text-2xl"></i>
                    <span class="text-sm text-gray-500">Value</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">GH₵{{ number_format($totalValue, 2) }}</h3>
                <p class="text-sm text-gray-600 mt-1">Total Investment</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-emerald-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-check-circle text-emerald-500 text-2xl"></i>
                    <span class="text-sm text-gray-500">Available</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ $availableEquipment }}</h3>
                <p class="text-sm text-gray-600 mt-1">Ready to Use</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-yellow-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                    <span class="text-sm text-gray-500">In Use</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ $inUseEquipment }}</h3>
                <p class="text-sm text-gray-600 mt-1">Currently Assigned</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-tools text-red-500 text-2xl"></i>
                    <span class="text-sm text-gray-500">Maintenance</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ $maintenanceDue }}</h3>
                <p class="text-sm text-gray-600 mt-1">Due Soon</p>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Equipment by Category -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Equipment by Category</h3>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($equipmentByCategory as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium text-gray-700">{{ $item->name }}</span>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $item->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Equipment by Condition -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Equipment Condition</h3>
                <div class="h-64">
                    <canvas id="conditionChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($equipmentByCondition as $item)
                        @php
                            $conditionColors = [
                                'excellent' => 'bg-green-100 text-green-800',
                                'good' => 'bg-blue-100 text-blue-800',
                                'fair' => 'bg-yellow-100 text-yellow-800',
                                'poor' => 'bg-orange-100 text-orange-800',
                                'damaged' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium text-gray-700 capitalize">{{ $item->condition }}</span>
                            <span class="{{ $conditionColors[$item->condition] ?? 'bg-gray-100 text-gray-800' }} px-3 py-1 rounded-full text-sm font-semibold">{{ $item->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Value by Category -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Total Value by Category</h3>
            <div class="h-80">
                <canvas id="valueChart"></canvas>
            </div>
        </div>

        <!-- Maintenance Alerts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Overdue Maintenance -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">⚠️ Overdue Maintenance</h3>
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $maintenanceOverdue }} Items</span>
                </div>
                @if($maintenanceOverdue > 0)
                    <div class="space-y-2">
                        <p class="text-red-600 font-medium">Immediate attention required!</p>
                        <a href="{{ route('equipment.maintenance') }}" class="inline-block mt-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all">
                            <i class="fas fa-tools mr-2"></i>View Maintenance Schedule
                        </a>
                    </div>
                @else
                    <p class="text-gray-600">All maintenance is up to date! 🎉</p>
                @endif
            </div>

            <!-- Upcoming Maintenance -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">📅 Upcoming Maintenance</h3>
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $maintenanceDue }} Items</span>
                </div>
                @if($maintenanceDue > 0)
                    <div class="space-y-2">
                        <p class="text-yellow-700 font-medium">Schedule maintenance in the next 7 days</p>
                        <a href="{{ route('equipment.maintenance') }}" class="inline-block mt-2 bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-all">
                            <i class="fas fa-calendar-alt mr-2"></i>View Schedule
                        </a>
                    </div>
                @else
                    <p class="text-gray-600">No maintenance scheduled soon</p>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Equipment by Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($equipmentByCategory->pluck('name')),
            datasets: [{
                data: @json($equipmentByCategory->pluck('total')),
                backgroundColor: [
                    '#3B82F6', '#8B5CF6', '#10B981', '#F59E0B', '#EF4444',
                    '#06B6D4', '#EC4899', '#6366F1', '#14B8A6', '#F97316'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Equipment Condition Chart
    const conditionCtx = document.getElementById('conditionChart').getContext('2d');
    new Chart(conditionCtx, {
        type: 'pie',
        data: {
            labels: @json($equipmentByCondition->pluck('condition')->map(fn($c) => ucfirst($c))),
            datasets: [{
                data: @json($equipmentByCondition->pluck('total')),
                backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#F97316', '#EF4444'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Value by Category Chart
    const valueCtx = document.getElementById('valueChart').getContext('2d');
    new Chart(valueCtx, {
        type: 'bar',
        data: {
            labels: @json($valueByCategory->pluck('name')),
            datasets: [{
                label: 'Total Value ($)',
                data: @json($valueByCategory->pluck('total_value')),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'GH₵' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'GH₵' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
