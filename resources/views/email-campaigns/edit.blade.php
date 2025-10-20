@extends('layouts.app')

@section('page-title', 'Edit Email Campaign')
@section('page-subtitle', 'Update campaign details')

@section('content')
<div class="max-w-4xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('email-campaigns.update', $emailCampaign) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Campaign Name *</label>
                    <input type="text" name="name" value="{{ old('name', $emailCampaign->name) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Email Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject', $emailCampaign->subject) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Email Content *</label>
                    <textarea name="content" rows="10" required 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">{{ old('content', $emailCampaign->content) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Status *</label>
                    <select name="status" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="draft" {{ $emailCampaign->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ $emailCampaign->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="sent" {{ $emailCampaign->status == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="cancelled" {{ $emailCampaign->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Campaign
                </button>
                <a href="{{ route('email-campaigns.show', $emailCampaign) }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
