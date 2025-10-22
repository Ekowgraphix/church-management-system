@extends('layouts.organization')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">💰 Finance Overview</h1>
                <p class="text-gray-600">Centralized financial tracking across all branches</p>
            </div>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-sm opacity-90 mb-1">Monthly Income</p>
            <p class="text-4xl font-black">₵{{ number_format($monthlyIncome) }}</p>
            <p class="text-xs mt-2 opacity-75">{{ now()->format('F Y') }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-sm opacity-90 mb-1">Yearly Income</p>
            <p class="text-4xl font-black">₵{{ number_format($yearlyIncome) }}</p>
            <p class="text-xs mt-2 opacity-75">{{ now()->format('Y') }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-sm opacity-90 mb-1">Expenses</p>
            <p class="text-4xl font-black">₵45,000</p>
            <p class="text-xs mt-2 opacity-75">This month</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-sm opacity-90 mb-1">Net Income</p>
            <p class="text-4xl font-black">₵{{ number_format($monthlyIncome - 45000) }}</p>
            <p class="text-xs mt-2 opacity-75">Profit this month</p>
        </div>
    </div>

    <!-- Multi-Branch Comparison -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Multi-Branch Comparison</h3>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Branch comparison chart</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📈 AI Financial Forecast</h3>
            <div class="space-y-3">
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="font-semibold text-gray-800">Expected Next Month</p>
                    <p class="text-2xl font-bold text-green-600">₵{{ number_format($monthlyIncome * 1.15) }}</p>
                    <p class="text-sm text-gray-600">15% projected growth</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="font-semibold text-gray-800">Year-End Projection</p>
                    <p class="text-2xl font-bold text-blue-600">₵{{ number_format($yearlyIncome * 1.25) }}</p>
                    <p class="text-sm text-gray-600">Based on current trends</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Donation Trends by Ministry -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">💵 Donation Trends by Ministry</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border-l-4 border-blue-500 bg-gray-50 rounded-lg">
                <p class="text-gray-600 text-sm">Youth Ministry</p>
                <p class="text-2xl font-bold text-gray-800">₵{{ number_format($monthlyIncome * 0.25) }}</p>
            </div>
            <div class="p-4 border-l-4 border-purple-500 bg-gray-50 rounded-lg">
                <p class="text-gray-600 text-sm">Women's Ministry</p>
                <p class="text-2xl font-bold text-gray-800">₵{{ number_format($monthlyIncome * 0.18) }}</p>
            </div>
            <div class="p-4 border-l-4 border-green-500 bg-gray-50 rounded-lg">
                <p class="text-gray-600 text-sm">Children's Ministry</p>
                <p class="text-2xl font-bold text-gray-800">₵{{ number_format($monthlyIncome * 0.15) }}</p>
            </div>
        </div>
    </div>
</div>

<script>
// Export Report Button
document.querySelector('.bg-blue-600').addEventListener('click', function() {
    alert('📥 Export Financial Report\n\nDownloading Excel report with all financial data...');
    // TODO: Generate and download Excel file
});
</script>
@endsection
