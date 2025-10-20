@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500">
                    💰 Add New Offering
                </h1>
                <p class="text-gray-400 mt-2">Record offering details with voice entry support</p>
            </div>
            <a href="{{ route('offerings.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>

        <div class="glass-card p-8 rounded-2xl">
            <form method="POST" action="{{ route('offerings.store') }}" id="offeringForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">
                            <i class="fas fa-calendar mr-1"></i> Date *
                        </label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Name -->
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">
                            <i class="fas fa-church mr-1"></i> Service Name
                            <button type="button" onclick="startVoiceRecognition('service_name')" class="ml-2 text-purple-400 hover:text-purple-300">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </label>
                        <input type="text" name="service_name" id="service_name" value="{{ old('service_name') }}" 
                            placeholder="e.g., Sunday Morning Service, Midweek Service"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('service_name') border-red-500 @enderror">
                        @error('service_name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">
                            <i class="fas fa-tag mr-1"></i> Category *
                        </label>
                        <select name="category" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="Sunday Offering" {{ old('category') == 'Sunday Offering' ? 'selected' : '' }}>Sunday Offering</option>
                            <option value="Thanksgiving" {{ old('category') == 'Thanksgiving' ? 'selected' : '' }}>Thanksgiving</option>
                            <option value="Harvest" {{ old('category') == 'Harvest' ? 'selected' : '' }}>Harvest</option>
                            <option value="Special" {{ old('category') == 'Special' ? 'selected' : '' }}>Special</option>
                            <option value="Missions" {{ old('category') == 'Missions' ? 'selected' : '' }}>Missions</option>
                            <option value="Building Fund" {{ old('category') == 'Building Fund' ? 'selected' : '' }}>Building Fund</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">
                            <i class="fas fa-money-bill-wave mr-1"></i> Amount (GHS) *
                        </label>
                        <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" required
                            placeholder="0.00"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('amount') border-red-500 @enderror">
                        @error('amount')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">
                            <i class="fas fa-credit-card mr-1"></i> Payment Method *
                        </label>
                        <select name="payment_method" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('payment_method') border-red-500 @enderror">
                            <option value="">Select Method</option>
                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Mobile Money" {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Collected By -->
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">
                            <i class="fas fa-user mr-1"></i> Collected By
                            <button type="button" onclick="startVoiceRecognition('collected_by')" class="ml-2 text-purple-400 hover:text-purple-300">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </label>
                        <input type="text" name="collected_by" id="collected_by" value="{{ old('collected_by') }}" 
                            placeholder="Name of collector"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('collected_by') border-red-500 @enderror">
                        @error('collected_by')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Remarks -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-green-300 mb-2">
                        <i class="fas fa-comment mr-1"></i> Remarks / Notes
                        <button type="button" onclick="startVoiceRecognition('remarks')" class="ml-2 text-purple-400 hover:text-purple-300">
                            <i class="fas fa-microphone"></i>
                        </button>
                    </label>
                    <textarea name="remarks" id="remarks" rows="4" 
                        placeholder="Additional notes about this offering..."
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('remarks') border-red-500 @enderror">{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-4 mt-8">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save mr-2"></i> Save Offering
                    </button>
                    <a href="{{ route('offerings.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Voice Recognition Info -->
        <div class="mt-6 bg-purple-500/10 border border-purple-500/30 rounded-lg p-4">
            <p class="text-purple-300 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Voice Entry:</strong> Click the <i class="fas fa-microphone"></i> icon next to any field to use voice input (requires browser support)
            </p>
        </div>
    </div>
</div>

<script>
// Voice Recognition
function startVoiceRecognition(fieldId) {
    if (!('webkitSpeechRecognition' in window)) {
        alert('Voice recognition is not supported in this browser. Please use Chrome or Edge.');
        return;
    }

    const recognition = new webkitSpeechRecognition();
    recognition.lang = 'en-US';
    recognition.continuous = false;
    recognition.interimResults = false;

    const field = document.getElementById(fieldId);
    const originalPlaceholder = field.placeholder;
    field.placeholder = '🎤 Listening...';
    field.style.borderColor = '#a855f7';

    recognition.start();

    recognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        field.value = transcript;
        field.placeholder = originalPlaceholder;
        field.style.borderColor = '';
    };

    recognition.onerror = function(event) {
        alert('Voice recognition error: ' + event.error);
        field.placeholder = originalPlaceholder;
        field.style.borderColor = '';
    };

    recognition.onend = function() {
        field.placeholder = originalPlaceholder;
        field.style.borderColor = '';
    };
}
</script>

<style>
.glass-card {
    background: rgba(17, 24, 39, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(75, 85, 99, 0.3);
}
</style>
@endsection
