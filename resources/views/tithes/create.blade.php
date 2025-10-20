@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500">
                    💎 Add New Tithe
                </h1>
                <p class="text-gray-400 mt-2">Record a member's tithe contribution</p>
            </div>
            <a href="{{ route('tithes.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl p-8">
            <form action="{{ route('tithes.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Member Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">
                        <i class="fas fa-user text-green-400 mr-2"></i> Select Member *
                    </label>
                    <select name="member_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 @error('member_id') border-red-500 @enderror">
                        <option value="">-- Select a Member --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->first_name }} {{ $member->last_name }} - {{ $member->phone ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Amount Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">
                            <i class="fas fa-calendar text-blue-400 mr-2"></i> Date *
                        </label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">
                            <i class="fas fa-money-bill-wave text-green-400 mr-2"></i> Amount (GHS) *
                        </label>
                        <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" placeholder="0.00" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 @error('amount') border-red-500 @enderror">
                        @error('amount')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method and Received By Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">
                            <i class="fas fa-credit-card text-purple-400 mr-2"></i> Payment Method *
                        </label>
                        <select name="payment_method" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 @error('payment_method') border-red-500 @enderror">
                            <option value="">-- Select Method --</option>
                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="Mobile Money" {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>📱 Mobile Money</option>
                            <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                            <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>📝 Cheque</option>
                            <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>💳 Card</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Received By -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">
                            <i class="fas fa-user-check text-yellow-400 mr-2"></i> Received By
                        </label>
                        <input type="text" name="received_by" value="{{ old('received_by', auth()->user()->name ?? '') }}" placeholder="Name of receiver" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 @error('received_by') border-red-500 @enderror">
                        @error('received_by')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Remarks -->
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">
                        <i class="fas fa-sticky-note text-blue-400 mr-2"></i> Remarks / Notes
                    </label>
                    <textarea name="remarks" rows="4" placeholder="Optional notes about this tithe..." class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 @error('remarks') border-red-500 @enderror">{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Auto Thank-You Feature Info -->
                <div class="bg-blue-600/10 border border-blue-500/30 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-400 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="text-white font-semibold mb-1">Auto Thank-You Feature</h4>
                            <p class="text-sm text-gray-300">
                                After recording this tithe, an automatic thank-you message will be sent to the member 
                                (if SMS/Email is configured).
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                    <a href="{{ route('tithes.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-all">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save mr-2"></i> Record Tithe
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Tips -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-green-600/10 border border-green-500/30 rounded-xl p-4">
                <i class="fas fa-lightbulb text-green-400 text-2xl mb-2"></i>
                <h4 class="text-white font-semibold mb-1">Tip: Quick Entry</h4>
                <p class="text-sm text-gray-400">Use Tab key to quickly navigate between fields</p>
            </div>
            <div class="bg-blue-600/10 border border-blue-500/30 rounded-xl p-4">
                <i class="fas fa-receipt text-blue-400 text-2xl mb-2"></i>
                <h4 class="text-white font-semibold mb-1">Tip: Receipt</h4>
                <p class="text-sm text-gray-400">Download receipts from the tithe records table</p>
            </div>
            <div class="bg-purple-600/10 border border-purple-500/30 rounded-xl p-4">
                <i class="fas fa-chart-line text-purple-400 text-2xl mb-2"></i>
                <h4 class="text-white font-semibold mb-1">Tip: Analytics</h4>
                <p class="text-sm text-gray-400">View member giving history and loyalty badges</p>
            </div>
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endsection
