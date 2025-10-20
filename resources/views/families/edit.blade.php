@extends('layouts.app')

@section('page-title', 'Edit Family')
@section('page-subtitle', 'Update family information')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('families.update', $family) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Family Name *</label>
                    <input type="text" name="family_name" value="{{ old('family_name', $family->family_name) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Head of Family *</label>
                    <select name="head_of_family_id" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $family->head_of_family_id == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Address</label>
                    <textarea name="address" rows="2" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">{{ old('address', $family->address) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">City</label>
                        <input type="text" name="city" value="{{ old('city', $family->city) }}" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">State</label>
                        <input type="text" name="state" value="{{ old('state', $family->state) }}" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $family->phone) }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Family
                </button>
                <a href="{{ route('families.show', $family) }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
