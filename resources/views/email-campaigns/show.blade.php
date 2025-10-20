@extends('layouts.app')

@section('page-title', $emailCampaign->name)
@section('page-subtitle', 'Campaign details and analytics')

@section('content')
<div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">Total Recipients</p>
            <p class="text-4xl font-black text-white">{{ $emailCampaign->total_recipients }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">Sent</p>
            <p class="text-4xl font-black text-green-300">{{ $emailCampaign->sent_count }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">Open Rate</p>
            <p class="text-4xl font-black text-blue-300">{{ $emailCampaign->open_rate }}%</p>
        </div>
        <div class="glass-card p-6 rounded-2xl">
            <p class="text-gray-400 text-sm mb-1">Click Rate</p>
            <p class="text-4xl font-black text-purple-300">{{ $emailCampaign->click_rate }}%</p>
        </div>
    </div>

    <div class="glass-card p-8 rounded-2xl mb-6">
        <h3 class="text-lg font-bold text-white mb-4">Campaign Details</h3>
        <div class="space-y-4">
            <div>
                <p class="text-gray-400 text-sm">Subject</p>
                <p class="text-white text-lg">{{ $emailCampaign->subject }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Content</p>
                <div class="bg-white/5 p-4 rounded-xl text-white whitespace-pre-wrap">{{ $emailCampaign->content }}</div>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Status</p>
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    {{ $emailCampaign->status == 'draft' ? 'bg-gray-500/20 text-gray-300' : '' }}
                    {{ $emailCampaign->status == 'scheduled' ? 'bg-blue-500/20 text-blue-300' : '' }}
                    {{ $emailCampaign->status == 'sending' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                    {{ $emailCampaign->status == 'sent' ? 'bg-green-500/20 text-green-300' : '' }}">
                    {{ ucfirst($emailCampaign->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Recipients ({{ $emailCampaign->recipients->count() }})</h3>
        <div class="space-y-2">
            @foreach($emailCampaign->recipients as $recipient)
                <div class="bg-white/5 p-3 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-white">{{ $recipient->member->full_name }}</p>
                        <p class="text-gray-400 text-sm">{{ $recipient->email }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $recipient->status == 'pending' ? 'bg-gray-500/20 text-gray-300' : '' }}
                        {{ $recipient->status == 'sent' ? 'bg-green-500/20 text-green-300' : '' }}
                        {{ $recipient->status == 'failed' ? 'bg-red-500/20 text-red-300' : '' }}">
                        {{ ucfirst($recipient->status) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
