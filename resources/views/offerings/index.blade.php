@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500">
                    💰 Offering Management
                </h1>
                <p class="text-gray-400 mt-2">Track and analyze church offerings with AI-powered insights</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="openAISummary()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-robot mr-2"></i> AI Summary
                </button>
                <a href="{{ route('offerings.create') }}" class="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus-circle mr-2"></i> Add Offering
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/50 text-green-300 px-6 py-4 rounded-xl mb-6 animate-fade-in">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="glass-card p-6 rounded-2xl border border-green-500/20 hover:border-green-500/50 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 font-medium">Today's Offering</h3>
                    <i class="fas fa-calendar-day text-green-400 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-white">GHS {{ number_format($stats['today'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ now()->format('l, M d, Y') }}</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-blue-500/20 hover:border-blue-500/50 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 font-medium">This Week</h3>
                    <i class="fas fa-calendar-week text-blue-400 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-white">GHS {{ number_format($stats['week'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">Week of {{ now()->startOfWeek()->format('M d') }}</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-purple-500/20 hover:border-purple-500/50 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 font-medium">This Month</h3>
                    <i class="fas fa-calendar-alt text-purple-400 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-white">GHS {{ number_format($stats['month'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ now()->format('F Y') }}</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-yellow-500/20 hover:border-yellow-500/50 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 font-medium">Year to Date</h3>
                    <i class="fas fa-chart-line text-yellow-400 text-2xl"></i>
                </div>
                <p class="text-3xl font-bold text-white">GHS {{ number_format($stats['year'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ now()->format('Y') }} Total</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Trend Chart -->
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-xl font-bold text-white mb-4">
                    <i class="fas fa-chart-bar text-blue-400 mr-2"></i> Monthly Trend (Last 12 Months)
                </h3>
                <canvas id="monthlyTrendChart" height="300"></canvas>
            </div>

            <!-- Category Breakdown Chart -->
            <div class="glass-card p-6 rounded-2xl">
                <h3 class="text-xl font-bold text-white mb-4">
                    <i class="fas fa-chart-pie text-green-400 mr-2"></i> Category Breakdown (This Month)
                </h3>
                <canvas id="categoryChart" height="300"></canvas>
            </div>
        </div>

        <!-- Service Analysis -->
        @if($serviceAnalysis->count() > 0)
        <div class="glass-card p-6 rounded-2xl mb-8">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-church text-purple-400 mr-2"></i> Service Type Analysis (This Year)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($serviceAnalysis as $service)
                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                    <h4 class="text-green-400 font-semibold">{{ $service->service_name ?: 'Not Specified' }}</h4>
                    <p class="text-2xl font-bold text-white mt-2">GHS {{ number_format($service->total, 2) }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ $service->count }} service{{ $service->count != 1 ? 's' : '' }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Filters -->
        <div class="glass-card p-6 rounded-2xl mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Service</label>
                    <input type="text" name="service_name" value="{{ request('service_name') }}" placeholder="Service name..." class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                    <select name="category" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
                        <option value="">All Categories</option>
                        <option value="Sunday Offering" {{ request('category') == 'Sunday Offering' ? 'selected' : '' }}>Sunday Offering</option>
                        <option value="Thanksgiving" {{ request('category') == 'Thanksgiving' ? 'selected' : '' }}>Thanksgiving</option>
                        <option value="Harvest" {{ request('category') == 'Harvest' ? 'selected' : '' }}>Harvest</option>
                        <option value="Special" {{ request('category') == 'Special' ? 'selected' : '' }}>Special</option>
                        <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Payment Method</label>
                    <select name="payment_method" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
                        <option value="">All Methods</option>
                        <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Mobile Money" {{ request('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="Bank Transfer" {{ request('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Cheque" {{ request('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="Card" {{ request('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('offerings.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>

            <!-- Export Buttons -->
            <div class="flex space-x-3 mt-4">
                <a href="{{ route('offerings.export.pdf', request()->all()) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-all">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
                <a href="{{ route('offerings.export.excel', request()->all()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </a>
            </div>
        </div>

        <!-- Offerings Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-green-600/20 to-blue-600/20 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-green-400">Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-green-400">Service</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-green-400">Category</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-green-400">Amount</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-green-400">Payment</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-green-400">Collected By</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-green-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($offerings as $offering)
                        <tr class="hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 text-white">{{ $offering->date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $offering->service_name ?: 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block bg-purple-500/20 text-purple-300 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $offering->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-green-400 font-bold">GHS {{ number_format($offering->amount, 2) }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $offering->payment_method }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $offering->collected_by ?: 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('offerings.receipt', $offering) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm transition-all" title="Download Receipt">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                    <a href="{{ route('offerings.edit', $offering) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded-lg text-sm transition-all">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('offerings.destroy', $offering) }}" method="POST" onsubmit="return confirm('Delete this offering record?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm transition-all">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>No offering records found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-800">
                {{ $offerings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- AI Summary Modal -->
<div id="aiModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-gradient-to-br from-gray-900 to-purple-900 rounded-2xl p-8 max-w-2xl w-full mx-4 border border-purple-500/30">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-white">
                <i class="fas fa-robot text-purple-400 mr-2"></i> AI Financial Summary
            </h3>
            <button onclick="closeAISummary()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div id="aiContent" class="text-white">
            <div class="flex items-center justify-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-purple-400"></i>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Trend Chart
const monthlyData = @json($monthlyTrend);
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const monthlyLabels = monthlyData.map(d => monthNames[d.month - 1] + ' ' + d.year);
const monthlyValues = monthlyData.map(d => d.total);

new Chart(document.getElementById('monthlyTrendChart'), {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Monthly Offerings',
            data: monthlyValues,
            backgroundColor: 'rgba(34, 197, 94, 0.5)',
            borderColor: 'rgba(34, 197, 94, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: '#9ca3af' },
                grid: { color: 'rgba(75, 85, 99, 0.3)' }
            },
            x: {
                ticks: { color: '#9ca3af' },
                grid: { display: false }
            }
        }
    }
});

// Category Chart
const categoryData = @json($categoryBreakdown);
const categoryLabels = categoryData.map(d => d.category);
const categoryValues = categoryData.map(d => d.total);
const categoryColors = ['#22c55e', '#3b82f6', '#a855f7', '#f59e0b', '#ef4444', '#06b6d4'];

new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryValues,
            backgroundColor: categoryColors,
            borderWidth: 2,
            borderColor: '#1f2937'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: '#9ca3af', padding: 15 }
            }
        }
    }
});

// AI Summary Modal
function openAISummary() {
    document.getElementById('aiModal').classList.remove('hidden');
    document.getElementById('aiModal').classList.add('flex');
    
    fetch('{{ route("offerings.ai-summary") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('aiContent').innerHTML = `
                <div class="space-y-4">
                    <div class="bg-gray-800/50 p-4 rounded-lg">
                        <h4 class="text-lg font-semibold text-green-400 mb-2">Period: ${data.period}</h4>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-sm text-gray-400">Total Collected</p>
                                <p class="text-2xl font-bold text-white">GHS ${data.total_amount}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Total Services</p>
                                <p class="text-2xl font-bold text-white">${data.total_services}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Avg Per Service</p>
                                <p class="text-2xl font-bold text-white">GHS ${data.average_per_service}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800/50 p-4 rounded-lg">
                        <h4 class="text-lg font-semibold text-purple-400 mb-3">Category Breakdown</h4>
                        ${Object.entries(data.breakdown).map(([category, amount]) => `
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-300">${category}</span>
                                <span class="text-green-400 font-semibold">GHS ${parseFloat(amount).toFixed(2)}</span>
                            </div>
                        `).join('')}
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('chat.index') }}" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-4 py-3 rounded-lg font-medium transition-all text-center">
                            <i class="fas fa-comments mr-2"></i> Ask AI for Detailed Analysis
                        </a>
                    </div>
                </div>
            `;
        });
}

function closeAISummary() {
    document.getElementById('aiModal').classList.add('hidden');
    document.getElementById('aiModal').classList.remove('flex');
}
</script>

<style>
.glass-card {
    background: rgba(17, 24, 39, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(75, 85, 99, 0.3);
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>
@endsection
