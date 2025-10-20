@extends('layouts.app')

@section('page-title', 'Edit Transaction')
@section('page-subtitle', 'Update welfare transaction')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-4xl font-black text-white">✏️ Edit Welfare Transaction</h1>
        <a href="{{ route('welfare.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <div class="glass-card rounded-3xl p-8">
        <form action="{{ route('welfare.update', $fund) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Date *</label>
                    <input type="date" name="date" value="{{ old('date', $fund->date) }}" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-cyan-400 transition">
                    @error('date')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Transaction Type *</label>
                    <select name="type" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-cyan-400 transition">
                        <option value="">Select Type</option>
                        <option value="income" {{ $fund->type === 'income' ? 'selected' : '' }}>Income (Money In)</option>
                        <option value="expense" {{ $fund->type === 'expense' ? 'selected' : '' }}>Expense (Money Out)</option>
                    </select>
                    @error('type')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-white font-semibold mb-2">Description *</label>
                    <input type="text" name="description" value="{{ old('description', $fund->description) }}" required
                        placeholder="e.g., Member donation, Medical assistance"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-cyan-400 transition">
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Amount (GH₵) *</label>
                    <input type="number" name="amount" value="{{ old('amount', $fund->amount) }}" step="0.01" min="0" required
                        placeholder="0.00"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-cyan-400 transition">
                    @error('amount')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Category</label>
                    <input type="text" name="category" value="{{ old('category', $fund->category) }}"
                        placeholder="e.g., Medical, Education, Funeral"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-cyan-400 transition">
                    @error('category')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-white font-semibold mb-2">Approved By</label>
                    <input type="text" name="approved_by" value="{{ old('approved_by', $fund->approved_by) }}"
                        placeholder="Name of approving authority"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-cyan-400 transition">
                    @error('approved_by')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex space-x-4 pt-6">
                <button type="submit" class="glass-card px-8 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-cyan-500/20 to-blue-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-save mr-2"></i>Update Transaction
                </button>
                <a href="{{ route('welfare.index') }}" class="glass-card px-8 py-4 rounded-xl font-semibold text-white hover:bg-white/10 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
