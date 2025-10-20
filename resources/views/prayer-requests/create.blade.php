@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="glass-card p-8 rounded-2xl">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-white mb-2">
                <i class="fas fa-praying-hands mr-3 text-neon-green"></i>
                Submit Prayer Request
            </h1>
            <p class="text-gray-300">Share your prayer need with the church family</p>
        </div>

        <form method="POST" action="{{ route('prayer-requests.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Member (Optional)</label>
                <select name="member_id" class="input-field">
                    <option value="">Anonymous</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Leave blank for anonymous requests</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Title *</label>
                <input type="text" name="title" required class="input-field" placeholder="Brief description of prayer need">
                @error('title')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Category *</label>
                <select name="category" required class="input-field">
                    <option value="">Select category...</option>
                    <option value="health">Health & Healing</option>
                    <option value="family">Family & Relationships</option>
                    <option value="financial">Financial</option>
                    <option value="spiritual">Spiritual Growth</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Description *</label>
                <textarea name="description" rows="5" required class="input-field" placeholder="Provide details about your prayer request..."></textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center space-x-6">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_public" value="1" class="w-5 h-5 rounded border-gray-600 bg-white/10 text-neon-green focus:ring-neon-green">
                    <span class="text-gray-300">Make this request public</span>
                </label>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_urgent" value="1" class="w-5 h-5 rounded border-gray-600 bg-white/10 text-red-500 focus:ring-red-500">
                    <span class="text-gray-300">Mark as urgent</span>
                </label>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Prayer Request
                </button>
                <a href="{{ route('prayer-requests.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
