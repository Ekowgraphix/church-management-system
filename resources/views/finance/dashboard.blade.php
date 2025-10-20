@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">💵 Finance Management</h1>
                <p class="text-white/80">Real-time financial insights and management</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('donations.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition inline-block">
                    + Add Income/Donation
                </a>
                <a href="{{ route('expenses.create') }}" class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition inline-block">
                    + Add Expense
                </a>
                <button onclick="window.print()" class="bg-white/20 backdrop-blur text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/30 transition">
                    📤 Print Report
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Income -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-2xl transform hover:scale-105 transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-arrow-down text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">This Month</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">${{ number_format($stats['total_income'] ?? 0, 2) }}</h3>
                <p class="text-sm opacity-90">Total Income</p>
                <div class="mt-3 flex items-center text-xs">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $stats['income_change'] ?? 0 }}% from last month</span>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl p-6 text-white shadow-2xl transform hover:scale-105 transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-arrow-up text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">This Month</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">${{ number_format($stats['total_expenses'] ?? 0, 2) }}</h3>
                <p class="text-sm opacity-90">Total Expenses</p>
                <div class="mt-3 flex items-center text-xs">
                    <i class="fas fa-arrow-down mr-1"></i>
                    <span>{{ $stats['expense_change'] ?? 0 }}% from last month</span>
                </div>
            </div>

            <!-- Net Balance -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-2xl transform hover:scale-105 transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">Balance</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">${{ number_format(($stats['total_income'] ?? 0) - ($stats['total_expenses'] ?? 0), 2) }}</h3>
                <p class="text-sm opacity-90">Net Balance</p>
                <div class="mt-3 flex items-center text-xs">
                    <i class="fas fa-check-circle mr-1"></i>
                    <span>{{ ($stats['total_income'] ?? 0) > ($stats['total_expenses'] ?? 0) ? 'Surplus' : 'Deficit' }}</span>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="bg-gradient-to-br from-orange-500 to-yellow-600 rounded-2xl p-6 text-white shadow-2xl transform hover:scale-105 transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <span class="text-sm opacity-90">Outstanding</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $stats['pending_count'] ?? 0 }}</h3>
                <p class="text-sm opacity-90">Pending Payments</p>
                <div class="mt-3 flex items-center text-xs">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <span>Needs attention</span>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Income vs Expenses Chart -->
            <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">📈 Income vs Expenses (Last 6 Months)</h3>
                <canvas id="incomeExpenseChart" height="250"></canvas>
            </div>

            <!-- Category Breakdown -->
            <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">🎯 Expense by Category</h3>
                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Transactions List -->
            <div class="lg:col-span-2 bg-white/10 backdrop-blur rounded-2xl p-6">
                <!-- Tabs -->
                <div class="flex space-x-4 mb-6 border-b border-white/20">
                    <button onclick="switchTab('all')" class="tab-btn active px-4 py-2 text-white font-semibold border-b-2 border-white">
                        All Transactions
                    </button>
                    <button onclick="switchTab('income')" class="tab-btn px-4 py-2 text-white/70 font-semibold border-b-2 border-transparent hover:text-white">
                        Income Only
                    </button>
                    <button onclick="switchTab('expense')" class="tab-btn px-4 py-2 text-white/70 font-semibold border-b-2 border-transparent hover:text-white">
                        Expenses Only
                    </button>
                </div>

                <!-- Filters -->
                <div class="flex space-x-3 mb-6">
                    <select class="flex-1 bg-white/20 text-white rounded-xl px-4 py-2 border border-white/30">
                        <option>All Categories</option>
                        <option>Tithes & Offerings</option>
                        <option>Donations</option>
                        <option>Salaries</option>
                        <option>Utilities</option>
                        <option>Maintenance</option>
                        <option>Events</option>
                        <option>Welfare</option>
                    </select>
                    <input type="date" class="bg-white/20 text-white rounded-xl px-4 py-2 border border-white/30">
                    <button class="bg-white/20 px-6 py-2 rounded-xl text-white font-semibold hover:bg-white/30">
                        🔍 Filter
                    </button>
                </div>

                <!-- Transaction Table -->
                <div class="space-y-3 max-h-[600px] overflow-y-auto">
                    @forelse($transactions ?? [] as $transaction)
                    <div class="bg-white/10 rounded-xl p-4 hover:bg-white/20 transition cursor-pointer" onclick="viewTransaction({{ $transaction->id }})">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                    {{ $transaction->type === 'Income' ? 'bg-green-500' : 'bg-red-500' }}">
                                    <i class="fas fa-{{ $transaction->type === 'Income' ? 'arrow-down' : 'arrow-up' }} text-white"></i>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold">{{ $transaction->title }}</h4>
                                    <p class="text-white/60 text-sm">{{ $transaction->category }} • {{ $transaction->date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold {{ $transaction->type === 'Income' ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $transaction->type === 'Income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                </p>
                                <p class="text-white/60 text-sm">{{ $transaction->payment_method }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-white/60">
                        <i class="fas fa-inbox text-5xl mb-4"></i>
                        <p>No transactions yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- AI Financial Analyst -->
                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl p-6 text-white">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-robot mr-2"></i>
                        AI Financial Analyst
                    </h3>
                    <p class="text-sm mb-4 opacity-90">Ask me anything about your finances!</p>
                    <input type="text" 
                           id="aiFinanceQuery"
                           placeholder="e.g., What was our biggest expense?"
                           class="w-full bg-white/20 rounded-xl px-4 py-3 text-white placeholder-white/60 mb-3">
                    <button onclick="askFinanceAI()" class="w-full bg-white text-purple-600 px-4 py-2 rounded-xl font-semibold hover:bg-white/90">
                        Ask AI
                    </button>
                    <div id="aiResponse" class="mt-4 hidden bg-white/20 rounded-xl p-4 text-sm"></div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('donations.index') }}" class="w-full bg-white/20 text-white px-4 py-3 rounded-xl hover:bg-white/30 text-left block">
                            <i class="fas fa-list mr-2"></i>
                            View All Donations
                        </a>
                        <a href="{{ route('expenses.index') }}" class="w-full bg-white/20 text-white px-4 py-3 rounded-xl hover:bg-white/30 text-left block">
                            <i class="fas fa-receipt mr-2"></i>
                            View All Expenses
                        </a>
                        <button onclick="window.print()" class="w-full bg-white/20 text-white px-4 py-3 rounded-xl hover:bg-white/30 text-left">
                            <i class="fas fa-print mr-2"></i>
                            Print Report
                        </button>
                        <a href="{{ route('dashboard') }}" class="w-full bg-white/20 text-white px-4 py-3 rounded-xl hover:bg-white/30 text-left block">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Main Dashboard
                        </a>
                    </div>
                </div>

                <!-- Budget Tracker -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">📊 Budget Tracker</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-white text-sm mb-2">
                                <span>Salaries</span>
                                <span>75%</span>
                            </div>
                            <div class="bg-white/20 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-white text-sm mb-2">
                                <span>Events</span>
                                <span>92%</span>
                            </div>
                            <div class="bg-white/20 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-white text-sm mb-2">
                                <span>Maintenance</span>
                                <span>45%</span>
                            </div>
                            <div class="bg-white/20 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Income Modal -->
<div id="addIncomeModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-8">
        <h2 class="text-2xl font-bold mb-6">Add Income</h2>
        <form action="{{ route('finance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="Income">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Category</label>
                    <select name="category" required class="w-full px-4 py-2 border rounded-xl">
                        <option>Tithes & Offerings</option>
                        <option>Donations</option>
                        <option>Fundraising</option>
                        <option>Partnerships</option>
                        <option>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" required class="w-full px-4 py-2 border rounded-xl">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Title/Description</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border rounded-xl">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Date</label>
                    <input type="date" name="date" required class="w-full px-4 py-2 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Payment Method</label>
                    <select name="payment_method" class="w-full px-4 py-2 border rounded-xl">
                        <option>Cash</option>
                        <option>Mobile Money</option>
                        <option>Bank Transfer</option>
                        <option>Check</option>
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-xl"></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-xl font-semibold">
                    Add Income
                </button>
                <button type="button" onclick="closeModal('addIncomeModal')" class="px-6 py-3 bg-gray-200 rounded-xl font-semibold">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Expense Modal (similar structure) -->
<div id="addExpenseModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-8">
        <h2 class="text-2xl font-bold mb-6">Add Expense</h2>
        <form action="{{ route('finance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="Expense">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Category</label>
                    <select name="category" required class="w-full px-4 py-2 border rounded-xl">
                        <option>Salaries</option>
                        <option>Utilities</option>
                        <option>Maintenance</option>
                        <option>Events</option>
                        <option>Welfare</option>
                        <option>Media</option>
                        <option>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" required class="w-full px-4 py-2 border rounded-xl">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Title/Description</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border rounded-xl">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Date</label>
                    <input type="date" name="date" required class="w-full px-4 py-2 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Payment Method</label>
                    <select name="payment_method" class="w-full px-4 py-2 border rounded-xl">
                        <option>Cash</option>
                        <option>Mobile Money</option>
                        <option>Bank Transfer</option>
                        <option>Check</option>
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-xl"></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="flex-1 bg-red-600 text-white px-6 py-3 rounded-xl font-semibold">
                    Add Expense
                </button>
                <button type="button" onclick="closeModal('addExpenseModal')" class="px-6 py-3 bg-gray-200 rounded-xl font-semibold">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/finance-ai.js') }}"></script>
<script>
// Chart.js configurations
const ctx1 = document.getElementById('incomeExpenseChart');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Income',
            data: [12000, 15000, 13000, 17000, 16000, 18000],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4
        }, {
            label: 'Expenses',
            data: [8000, 9000, 10000, 11000, 10500, 12000],
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { color: 'white' }
            }
        },
        scales: {
            y: {
                ticks: { color: 'white' },
                grid: { color: 'rgba(255,255,255,0.1)' }
            },
            x: {
                ticks: { color: 'white' },
                grid: { color: 'rgba(255,255,255,0.1)' }
            }
        }
    }
});

const ctx2 = document.getElementById('categoryChart');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Salaries', 'Utilities', 'Events', 'Maintenance', 'Welfare'],
        datasets: [{
            data: [35, 20, 25, 10, 10],
            backgroundColor: [
                'rgb(59, 130, 246)',
                'rgb(251, 191, 36)',
                'rgb(168, 85, 247)',
                'rgb(34, 197, 94)',
                'rgb(239, 68, 68)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { color: 'white' }
            }
        }
    }
});

// Modal functions
function showAddIncomeModal() {
    document.getElementById('addIncomeModal').classList.remove('hidden');
}

function showAddExpenseModal() {
    document.getElementById('addExpenseModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// AI Query function
function askFinanceAI() {
    const query = document.getElementById('aiFinanceQuery').value;
    const responseDiv = document.getElementById('aiResponse');
    
    responseDiv.classList.remove('hidden');
    responseDiv.innerHTML = '<div class="animate-pulse">Analyzing...</div>';
    
    // Mock response - integrate with real AI
    setTimeout(() => {
        responseDiv.innerHTML = `
            <div class="font-semibold mb-2">AI Response:</div>
            <div>Based on analysis, your biggest expense last quarter was Salaries ($45,000), 
            accounting for 38% of total expenses. Consider optimizing staff allocation.</div>
        `;
    }, 1500);
}

// Tab switching
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-white');
        btn.classList.add('text-white/70', 'border-transparent');
    });
    event.target.classList.add('active', 'border-white', 'text-white');
    event.target.classList.remove('text-white/70', 'border-transparent');
    
    // Filter transactions based on tab
    // Add your filtering logic here
}

// Export functions
function exportPDF() {
    alert('Exporting to PDF...');
    // Implement PDF export
}

function exportExcel() {
    alert('Exporting to Excel...');
    // Implement Excel export
}

function exportFinanceReport() {
    alert('Generating report...');
}

function generateInvoice() {
    alert('Opening invoice generator...');
}

function viewAuditLog() {
    window.location.href = '/finance/audit-log';
}

function viewTransaction(id) {
    window.location.href = `/finance/${id}`;
}
</script>
@endsection
