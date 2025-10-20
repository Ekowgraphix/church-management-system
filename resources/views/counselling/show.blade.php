@extends('layouts.app')

@section('page-title', 'Session Details')
@section('page-subtitle', 'View counselling session information')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white mb-2">🧠 Session Details</h1>
            <p class="text-green-200">{{ $counselling->member_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('counselling.edit', $counselling) }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('counselling.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-card rounded-3xl p-8">
            <h2 class="text-2xl font-black text-white mb-6">Session Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-white/60 text-sm mb-2">Member Name</p>
                    <p class="text-white text-lg font-semibold">{{ $counselling->member_name }}</p>
                </div>

                @if($counselling->member_contact)
                <div>
                    <p class="text-white/60 text-sm mb-2">Contact</p>
                    <p class="text-white text-lg font-semibold">
                        <i class="fas fa-phone mr-2 text-green-400"></i>{{ $counselling->member_contact }}
                    </p>
                </div>
                @endif

                <div>
                    <p class="text-white/60 text-sm mb-2">Counsellor</p>
                    <p class="text-white text-lg font-semibold">{{ $counselling->counsellor_name }}</p>
                </div>

                <div>
                    <p class="text-white/60 text-sm mb-2">Topic</p>
                    <p class="text-white text-lg font-semibold">{{ $counselling->topic }}</p>
                </div>

                <div>
                    <p class="text-white/60 text-sm mb-2">Session Date</p>
                    <p class="text-white text-lg font-semibold">{{ $counselling->session_date }}</p>
                </div>

                @if($counselling->session_time)
                <div>
                    <p class="text-white/60 text-sm mb-2">Time</p>
                    <p class="text-white text-lg font-semibold">{{ $counselling->session_time }}</p>
                </div>
                @endif
            </div>

            @if($counselling->notes)
            <div class="glass-card rounded-2xl p-6 {{ $counselling->is_confidential ? 'bg-red-500/10 border border-red-500/30' : '' }} mb-6">
                @if($counselling->is_confidential)
                    <p class="text-red-400 text-sm mb-3 flex items-center">
                        <i class="fas fa-lock mr-2"></i>CONFIDENTIAL SESSION NOTES
                    </p>
                @else
                    <p class="text-white/60 text-sm mb-3">Session Notes</p>
                @endif
                <p class="text-white">{{ $counselling->notes }}</p>
            </div>
            @endif

            @if($counselling->follow_up_date)
            <div class="glass-card rounded-2xl p-6 bg-blue-500/10 border border-blue-500/30">
                <p class="text-blue-300 text-sm mb-2">
                    <i class="fas fa-calendar-plus mr-2"></i>Follow-up Scheduled
                </p>
                <p class="text-white font-semibold">{{ $counselling->follow_up_date }}</p>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="glass-card rounded-2xl p-6">
                <p class="text-white/60 text-sm mb-2">Status</p>
                <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold text-white
                    {{ $counselling->status === 'pending' ? 'bg-yellow-500' : '' }}
                    {{ $counselling->status === 'completed' ? 'bg-green-500' : '' }}
                    {{ $counselling->status === 'follow_up' ? 'bg-blue-500' : '' }}
                    {{ $counselling->status === 'cancelled' ? 'bg-red-500' : '' }}">
                    {{ strtoupper(str_replace('_', ' ', $counselling->status)) }}
                </span>
            </div>

            <div class="glass-card rounded-2xl p-6">
                <p class="text-white/60 text-sm mb-2">Confidentiality</p>
                <p class="text-white font-semibold">
                    @if($counselling->is_confidential)
                        <i class="fas fa-lock mr-2 text-red-400"></i>Confidential
                    @else
                        <i class="fas fa-unlock mr-2 text-green-400"></i>Not Confidential
                    @endif
                </p>
            </div>

            <div class="glass-card rounded-2xl p-6">
                <p class="text-white/60 text-sm mb-2">Created</p>
                <p class="text-white font-semibold">{{ $counselling->created_at->format('M d, Y g:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
