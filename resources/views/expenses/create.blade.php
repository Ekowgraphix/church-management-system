@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add Expense</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route('expenses.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                    <input type="number" step="0.01" name="amount" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium">
                    Save Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection