@extends('layouts.app')

@section('page-title', 'Create Family')
@section('page-subtitle', 'Add a new family unit')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('families.store') }}">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Family Name *</label>
                    <input type="text" name="family_name" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500"
                           placeholder="e.g., The Johnson Family">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Head of Family *</label>
                    <select name="head_of_family_id" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Address</label>
                    <textarea name="address" rows="2" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">City</label>
                        <input type="text" name="city" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">State</label>
                        <input type="text" name="state" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Phone</label>
                    <input type="tel" name="phone" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Create Family
                </button>
                <a href="{{ route('families.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
