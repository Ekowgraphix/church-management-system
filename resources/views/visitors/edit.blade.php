@extends('layouts.app')

@section('page-title', 'Edit Visitor')
@section('page-subtitle', 'Update visitor information')

@section('content')
<div class="max-w-4xl">
    <div class="glass-card p-8 rounded-2xl mb-6">
        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-300 px-6 py-4 rounded-xl mb-6">
                <p class="font-bold mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('visitors.update', $visitor) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $visitor->first_name) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $visitor->last_name) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Phone *</label>
                    <input type="tel" name="phone" value="{{ old('phone', $visitor->phone) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $visitor->email) }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Visit Date -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Visit Date *</label>
                    <input type="date" name="visit_date" value="{{ old('visit_date', $visitor->visit_date ? $visitor->visit_date->format('Y-m-d') : '') }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Visit Type -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Visit Type *</label>
                    <select name="visit_type" required class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500 transition-all">
                        <option value="first_time" {{ old('visit_type', $visitor->visit_type) == 'first_time' ? 'selected' : '' }}>First Time</option>
                        <option value="returning" {{ old('visit_type', $visitor->visit_type) == 'returning' ? 'selected' : '' }}>Returning</option>
                        <option value="guest" {{ old('visit_type', $visitor->visit_type) == 'guest' ? 'selected' : '' }}>Guest</option>
                    </select>
                </div>

                <!-- Follow-up Status -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Follow-up Status *</label>
                    <select name="followup_status" required class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500 transition-all">
                        <option value="pending" {{ old('followup_status', $visitor->followup_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="contacted" {{ old('followup_status', $visitor->followup_status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="completed" {{ old('followup_status', $visitor->followup_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="no_response" {{ old('followup_status', $visitor->followup_status) == 'no_response' ? 'selected' : '' }}>No Response</option>
                    </select>
                </div>

                <!-- Service Attended -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Service Attended</label>
                    <input type="text" name="service_attended" value="{{ old('service_attended', $visitor->service_attended) }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">{{ old('address', $visitor->address) }}</textarea>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">{{ old('notes', $visitor->notes) }}</textarea>
                </div>

                <!-- Wants Follow-up -->
                <div class="md:col-span-2">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="wants_followup" value="1" {{ old('wants_followup', $visitor->wants_followup) ? 'checked' : '' }} 
                               class="w-5 h-5 text-green-500 focus:ring-green-500 border-gray-400 rounded bg-white/10">
                        <span class="text-white font-medium">Wants Follow-up Contact</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Visitor
                </button>
                <a href="{{ route('visitors.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
