@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-cyan-400/10 to-blue-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">📜 Chat History</h1>
                <p class="text-cyan-200 text-lg">Your conversation history with AI Assistant</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('ai.chat') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-comment mr-2"></i>New Chat
                </a>
                <form action="{{ route('ai.chat.clear') }}" method="POST" onsubmit="return confirm('Clear all chat history?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:bg-red-500/20 transition-all">
                        <i class="fas fa-trash mr-2"></i>Clear History
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-3xl p-8">
        <div class="text-center py-16">
            <i class="fas fa-robot text-white/20 text-6xl mb-4"></i>
            <p class="text-white/60 text-lg">AI Chat History Coming Soon!</p>
            <p class="text-white/40 text-sm mt-2">Your conversation history will appear here</p>
        </div>
    </div>
</div>
@endsection
