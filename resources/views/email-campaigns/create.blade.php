@extends('layouts.app')

@section('page-title', 'Create Email Campaign')
@section('page-subtitle', 'Send email to members')

@section('content')
<div class="max-w-4xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('email-campaigns.store') }}">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Campaign Name *</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500"
                           placeholder="e.g., Christmas Service Announcement">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Email Subject *</label>
                    <input type="text" name="subject" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500"
                           placeholder="Enter email subject">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Email Content *</label>
                    <textarea name="content" rows="10" required 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500"
                              placeholder="Enter your message..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Schedule Send (Optional)</label>
                    <input type="datetime-local" name="scheduled_at" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    <p class="text-gray-400 text-xs mt-1">Leave empty to save as draft</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Recipients *</label>
                    <div class="bg-white/5 border border-white/20 rounded-xl p-4 max-h-64 overflow-y-auto">
                        <div class="space-y-2">
                            @foreach($members as $member)
                                <label class="flex items-center space-x-3 text-white hover:bg-white/5 p-2 rounded cursor-pointer">
                                    <input type="checkbox" name="recipients[]" value="{{ $member->id }}" 
                                           class="w-4 h-4 text-green-500 bg-white/10 border-white/20 rounded focus:ring-green-500">
                                    <span>{{ $member->full_name }} ({{ $member->email }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-gray-400 text-xs mt-1">Select members to receive this email</p>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Create Campaign
                </button>
                <a href="{{ route('email-campaigns.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
