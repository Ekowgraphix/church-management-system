@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass-card p-8 rounded-2xl">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <h1 class="text-3xl font-black text-white">{{ $prayerRequest->title }}</h1>
                    @if($prayerRequest->is_urgent)
                        <span class="px-3 py-1 bg-red-500/20 text-red-400 text-sm rounded-full font-semibold">
                            <i class="fas fa-exclamation-circle mr-1"></i> Urgent
                        </span>
                    @endif
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <span><i class="fas fa-user mr-1"></i> {{ $prayerRequest->member ? $prayerRequest->member->full_name : 'Anonymous' }}</span>
                    <span><i class="fas fa-tag mr-1"></i> {{ ucfirst($prayerRequest->category) }}</span>
                    <span><i class="fas fa-calendar mr-1"></i> {{ $prayerRequest->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <span class="px-4 py-2 rounded-lg text-sm font-semibold
                {{ $prayerRequest->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                {{ $prayerRequest->status === 'praying' ? 'bg-blue-500/20 text-blue-400' : '' }}
                {{ $prayerRequest->status === 'answered' ? 'bg-green-500/20 text-neon-green' : '' }}">
                {{ ucfirst($prayerRequest->status) }}
            </span>
        </div>

        <div class="bg-white/5 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-bold text-white mb-3">Prayer Request</h3>
            <p class="text-gray-300 leading-relaxed">{{ $prayerRequest->description }}</p>
        </div>

        <div class="flex space-x-3">
            <form method="POST" action="{{ route('prayer-requests.update', $prayerRequest) }}" class="flex-1">
                @csrf
                @method('PUT')
                <div class="flex space-x-3">
                    <select name="status" class="input-field flex-1">
                        <option value="pending" {{ $prayerRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="praying" {{ $prayerRequest->status === 'praying' ? 'selected' : '' }}>Praying</option>
                        <option value="answered" {{ $prayerRequest->status === 'answered' ? 'selected' : '' }}>Answered</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Update Status
                    </button>
                </div>
            </form>
            <a href="{{ route('prayer-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
