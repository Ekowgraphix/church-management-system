@extends('layouts.app')

@section('page-title', 'Email Campaigns')
@section('page-subtitle', 'Manage and send email campaigns')

@section('content')
<div>
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-3">
            <select onchange="filterCampaigns(this.value)" 
                    class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="scheduled">Scheduled</option>
                <option value="sending">Sending</option>
                <option value="sent">Sent</option>
            </select>
        </div>
        <a href="{{ route('email-campaigns.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Campaign
        </a>
    </div>

    <!-- Campaigns List -->
    @if($campaigns->count() > 0)
        <div class="space-y-4">
            @foreach($campaigns as $campaign)
                <div class="glass-card p-6 rounded-2xl">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-xl font-bold text-white">{{ $campaign->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $campaign->status == 'draft' ? 'bg-gray-500/20 text-gray-300' : '' }}
                                    {{ $campaign->status == 'scheduled' ? 'bg-blue-500/20 text-blue-300' : '' }}
                                    {{ $campaign->status == 'sending' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                                    {{ $campaign->status == 'sent' ? 'bg-green-500/20 text-green-300' : '' }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm mb-3">{{ $campaign->subject }}</p>
                            
                            <div class="grid grid-cols-4 gap-4 mb-4">
                                <div class="bg-white/5 p-3 rounded-xl">
                                    <p class="text-gray-400 text-xs mb-1">Recipients</p>
                                    <p class="text-white font-bold">{{ $campaign->total_recipients }}</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl">
                                    <p class="text-gray-400 text-xs mb-1">Sent</p>
                                    <p class="text-green-300 font-bold">{{ $campaign->sent_count }}</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl">
                                    <p class="text-gray-400 text-xs mb-1">Open Rate</p>
                                    <p class="text-blue-300 font-bold">{{ $campaign->open_rate }}%</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl">
                                    <p class="text-gray-400 text-xs mb-1">Click Rate</p>
                                    <p class="text-purple-300 font-bold">{{ $campaign->click_rate }}%</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4 text-sm text-gray-400">
                                @if($campaign->scheduled_at)
                                    <span><i class="fas fa-clock text-green-400 mr-1"></i> Scheduled: {{ $campaign->scheduled_at->format('M d, Y h:i A') }}</span>
                                @endif
                                @if($campaign->sent_at)
                                    <span><i class="fas fa-check text-green-400 mr-1"></i> Sent: {{ $campaign->sent_at->format('M d, Y h:i A') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('email-campaigns.show', $campaign) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($campaign->status == 'draft' || $campaign->status == 'scheduled')
                                <form method="POST" action="{{ route('email-campaigns.send', $campaign) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Send this campaign now?')">
                                        <i class="fas fa-paper-plane"></i> Send
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $campaigns->links() }}
        </div>
    @else
        <div class="glass-card p-16 rounded-2xl text-center">
            <i class="fas fa-envelope text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-lg mb-2">No campaigns found</p>
            <p class="text-gray-500 text-sm mb-6">Create your first email campaign</p>
            <a href="{{ route('email-campaigns.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create First Campaign
            </a>
        </div>
    @endif
</div>
@endsection
