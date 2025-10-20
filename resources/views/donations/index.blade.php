@extends('layouts.app')

@section('content')
<div>
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Financial Dashboard</h1>
                <p class="mt-2">Track and manage church finances</p>
            </div>
            <a href="{{ route('donations.create') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                <i class="fas fa-plus mr-2"></i> Add Transaction
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Total Income</p>
            <p class="text-3xl font-bold mt-2">GH₵ 0.00</p>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Total Expenses</p>
            <p class="text-3xl font-bold mt-2">GH₵ 0.00</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Current Balance</p>
            <p class="text-3xl font-bold mt-2">GH₵ 0.00</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
        <p class="text-gray-600">No transactions found</p>
    </div>
</div>
@endsection