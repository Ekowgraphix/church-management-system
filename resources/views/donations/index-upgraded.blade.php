@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Enhanced Financial Dashboard Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-green-400/10 to-emerald-400/10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">💰 Financial Dashboard</h1>
                <p class="text-green-200 text-lg">Comprehensive church financial management</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showBudgetPlanner()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-calculator mr-2"></i>Budget
                </button>
                <button onclick="generateReport()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-file-invoice mr-2"></i>Reports
                </button>
                <button onclick="exportFinancial()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
                <a href="{{ route('donations.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-green-500/20 to-emerald-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Transaction
                </a>
            </div>
        </div>

        <!-- Enhanced Financial Stats -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="glass-card rounded-2xl p-6 gradient-green">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-arrow-up text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">GH₵{{ number_format($stats['total_income'] ?? 0, 2) }}</span>
                </div>
                <p class="text-white/90 font-medium">Total Income</p>
                <p class="text-white/60 text-xs mt-1">
                    <i class="fas fa-arrow-up text-green-300 mr-1"></i>
                    +15% this month
                </p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-red">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-arrow-down text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">GH₵{{ number_format($stats['total_expenses'] ?? 0, 2) }}</span>
                </div>
                <p class="text-white/90 font-medium">Total Expenses</p>
                <p class="text-white/60 text-xs mt-1">
                    <i class="fas fa-arrow-down text-red-300 mr-1"></i>
                    -5% from last month
                </p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-{{ ($stats['balance'] ?? 0) >= 0 ? 'blue' : 'orange' }}">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-wallet text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">GH₵{{ number_format($stats['balance'] ?? 0, 2) }}</span>
                </div>
                <p class="text-white/90 font-medium">Current Balance</p>
                <p class="text-white/60 text-xs mt-1">Available funds</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-purple">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-hand-holding-heart text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">GH₵{{ number_format($stats['tithes'] ?? 0, 2) }}</span>
                </div>
                <p class="text-white/90 font-medium">Tithes & Offerings</p>
                <p class="text-white/60 text-xs mt-1">This month</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-cyan">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-gift text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">GH₵{{ number_format($stats['pledges'] ?? 0, 2) }}</span>
                </div>
                <p class="text-white/90 font-medium">Pledges</p>
                <p class="text-white/60 text-xs mt-1">{{ $stats['pledge_count'] ?? 0 }} active</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-orange">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['expense_ratio'] ?? 0 }}%</span>
                </div>
                <p class="text-white/90 font-medium">Expense Ratio</p>
                <p class="text-white/60 text-xs mt-1">Of total income</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Income vs Expenses Chart -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-white">📈 Financial Trends</h2>
                <div class="flex space-x-2">
                    <button onclick="changePeriod('month')" class="glass-card px-4 py-2 rounded-lg text-white text-sm bg-white/10">Month</button>
                    <button onclick="changePeriod('quarter')" class="glass-card px-4 py-2 rounded-lg text-white text-sm hover:bg-white/10 transition">Quarter</button>
                    <button onclick="changePeriod('year')" class="glass-card px-4 py-2 rounded-lg text-white text-sm hover:bg-white/10 transition">Year</button>
                </div>
            </div>
            
            <div class="h-64 flex items-end justify-between space-x-2">
                @for($i = 0; $i < 12; $i++)
                    @php
                        $income = rand(5000, 15000);
                        $expense = rand(3000, 10000);
                        $maxHeight = max($income, $expense);
                        $incomeHeight = ($income / 20000) * 100;
                        $expenseHeight = ($expense / 20000) * 100;
                    @endphp
                    <div class="flex-1 flex flex-col items-center space-y-1">
                        <div class="w-full flex space-x-1">
                            <div class="flex-1 bg-gradient-to-t from-green-500 to-emerald-500 rounded-t-lg hover:from-green-400 hover:to-emerald-400 transition cursor-pointer" 
                                style="height: {{ $incomeHeight }}px"
                                title="Income: GH₵{{ number_format($income, 0) }}"></div>
                            <div class="flex-1 bg-gradient-to-t from-red-500 to-orange-500 rounded-t-lg hover:from-red-400 hover:to-orange-400 transition cursor-pointer" 
                                style="height: {{ $expenseHeight }}px"
                                title="Expense: GH₵{{ number_format($expense, 0) }}"></div>
                        </div>
                        <p class="text-white/60 text-xs">{{ ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][$i] }}</p>
                    </div>
                @endfor
            </div>

            <div class="flex items-center justify-center space-x-6 mt-6">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-gradient-to-r from-green-500 to-emerald-500 rounded"></div>
                    <span class="text-white/80 text-sm">Income</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-gradient-to-r from-red-500 to-orange-500 rounded"></div>
                    <span class="text-white/80 text-sm">Expenses</span>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-xl font-black text-white mb-4">💼 Top Categories</h3>
            
            <div class="space-y-4">
                @foreach(['Tithes' => 45, 'Offerings' => 25, 'Pledges' => 15, 'Donations' => 10, 'Others' => 5] as $category => $percentage)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white font-semibold">{{ $category }}</span>
                        <span class="text-white font-bold">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all duration-500" 
                            style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <button onclick="viewAllCategories()" class="w-full mt-6 glass-card px-4 py-3 rounded-xl text-white font-semibold hover:bg-white/10 transition">
                View All Categories
            </button>
        </div>
    </div>

    <!-- Recent Transactions & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-white">💳 Recent Transactions</h2>
                <div class="flex space-x-2">
                    <select onchange="filterTransactions(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-green-400 transition">
                        <option value="all">All Types</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                    <input type="date" onchange="filterByDate(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-green-400 transition">
                </div>
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto">
                @for($i = 0; $i < 10; $i++)
                    @php
                        $isIncome = rand(0, 1);
                        $amount = rand(100, 5000);
                        $categories = ['Tithes', 'Offerings', 'Rent', 'Utilities', 'Salaries', 'Maintenance'];
                        $category = $categories[array_rand($categories)];
                    @endphp
                    <div class="glass-card p-4 rounded-xl hover:bg-white/10 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="w-12 h-12 gradient-{{ $isIncome ? 'green' : 'red' }} rounded-xl flex items-center justify-center">
                                    <i class="fas fa-{{ $isIncome ? 'arrow-up' : 'arrow-down' }} text-white text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white font-bold">{{ $category }}</p>
                                    <p class="text-white/60 text-sm">{{ $isIncome ? 'Income' : 'Expense' }} • {{ ['Cash', 'Mobile Money', 'Bank Transfer', 'Cheque'][rand(0, 3)] }}</p>
                                    <p class="text-white/40 text-xs">{{ rand(1, 30) }} days ago</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-black text-{{ $isIncome ? 'green' : 'red' }}-400">
                                    {{ $isIncome ? '+' : '-' }}GH₵{{ number_format($amount, 2) }}
                                </p>
                                <button onclick="viewTransaction({{ $i }})" class="text-white/60 hover:text-white text-xs mt-1">
                                    <i class="fas fa-eye mr-1"></i>Details
                                </button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">⚡ Quick Actions</h3>
                <div class="space-y-3">
                    <button onclick="recordTithe()" class="w-full glass-card p-4 rounded-xl text-left hover:bg-white/10 transition group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-green rounded-lg flex items-center justify-center">
                                <i class="fas fa-hand-holding-heart text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold group-hover:text-green-400 transition">Record Tithe</p>
                                <p class="text-white/60 text-xs">Quick tithe entry</p>
                            </div>
                            <i class="fas fa-chevron-right text-white/40"></i>
                        </div>
                    </button>

                    <button onclick="recordOffering()" class="w-full glass-card p-4 rounded-xl text-left hover:bg-white/10 transition group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-blue rounded-lg flex items-center justify-center">
                                <i class="fas fa-gift text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold group-hover:text-blue-400 transition">Record Offering</p>
                                <p class="text-white/60 text-xs">Service offering</p>
                            </div>
                            <i class="fas fa-chevron-right text-white/40"></i>
                        </div>
                    </button>

                    <button onclick="recordExpense()" class="w-full glass-card p-4 rounded-xl text-left hover:bg-white/10 transition group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-red rounded-lg flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold group-hover:text-red-400 transition">Record Expense</p>
                                <p class="text-white/60 text-xs">Church expenses</p>
                            </div>
                            <i class="fas fa-chevron-right text-white/40"></i>
                        </div>
                    </button>

                    <button onclick="viewPledges()" class="w-full glass-card p-4 rounded-xl text-left hover:bg-white/10 transition group">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-purple rounded-lg flex items-center justify-center">
                                <i class="fas fa-handshake text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold group-hover:text-purple-400 transition">Manage Pledges</p>
                                <p class="text-white/60 text-xs">Track commitments</p>
                            </div>
                            <i class="fas fa-chevron-right text-white/40"></i>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Upcoming Bills -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">📅 Upcoming Bills</h3>
                <div class="space-y-3">
                    @foreach(['Rent' => ['date' => '25 Oct', 'amount' => 5000], 'Utilities' => ['date' => '28 Oct', 'amount' => 1200], 'Salaries' => ['date' => '31 Oct', 'amount' => 8500]] as $bill => $details)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-semibold">{{ $bill }}</p>
                            <p class="text-white/60 text-xs">Due: {{ $details['date'] }}</p>
                        </div>
                        <p class="text-orange-400 font-bold">GH₵{{ number_format($details['amount'], 0) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function showBudgetPlanner() {
    alert('Budget Planner:\n\n• Create annual budgets\n• Track spending vs budget\n• Set department allocations\n• Budget alerts\n\nFeature coming soon!');
}

function generateReport() {
    alert('Financial Reports:\n\n• Monthly/Quarterly/Annual\n• Income statements\n• Expense reports\n• Donor reports\n• Tax documents\n\nFeature coming soon!');
}

function exportFinancial() {
    alert('Export Options:\n\n• Export to Excel\n• Export to PDF\n• QuickBooks format\n• Custom date range\n\nFeature coming soon!');
}

function changePeriod(period) {
    console.log('Changing to ' + period + ' view');
}

function filterTransactions(type) {
    console.log('Filtering by: ' + type);
}

function filterByDate(date) {
    console.log('Filtering by date: ' + date);
}

function viewTransaction(id) {
    alert('Transaction Details:\n\nID: ' + id + '\nAmount: GH₵500.00\nCategory: Tithes\nPayment: Mobile Money\nDate: Oct 15, 2025\nReference: TXN00' + id);
}

function recordTithe() {
    alert('Quick Tithe Entry:\n\n• Member selection\n• Amount entry\n• Payment method\n• Receipt generation\n\nFeature coming soon!');
}

function recordOffering() {
    alert('Quick Offering Entry:\n\n• Service selection\n• Amount entry\n• Category selection\n• Auto-tracking\n\nFeature coming soon!');
}

function recordExpense() {
    alert('Quick Expense Entry:\n\n• Category selection\n• Amount entry\n• Approval workflow\n• Receipt upload\n\nFeature coming soon!');
}

function viewPledges() {
    alert('Pledge Management:\n\n• Active pledges\n• Payment tracking\n• Reminders\n• Fulfillment status\n\nFeature coming soon!');
}

function viewAllCategories() {
    alert('All Categories:\n\nIncome:\n• Tithes: 45%\n• Offerings: 25%\n• Pledges: 15%\n\nExpenses:\n• Salaries: 30%\n• Rent: 20%\n• Utilities: 15%');
}
</script>
@endsection
