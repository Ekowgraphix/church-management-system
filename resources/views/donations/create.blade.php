@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add Donation/Transaction</h1>
        <p class="mt-2">Record a new donation or financial transaction</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('donations.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member (Optional)</label>
                    <select name="member_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Anonymous / Select Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->phone }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="donation_category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (GH₵) *</label>
                    <input type="number" step="0.01" name="amount" required value="{{ old('amount') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="donation_date" required value="{{ old('donation_date', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i> Save Donation
                    </button>
                    <a href="{{ route('donations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection