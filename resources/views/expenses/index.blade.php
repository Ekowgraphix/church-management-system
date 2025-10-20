@extends('layouts.app')

@section('content')
<div>
    <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Expenses</h1>
        <p class="mt-2">Track church expenses</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Expense List</h2>
        <p class="text-gray-600">No expenses found</p>
    </div>
</div>
@endsection