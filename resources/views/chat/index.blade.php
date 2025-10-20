@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-robot me-2"></i>AI Chat Assistant
                    </h3>
                    <a href="{{ route('chat.new') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Chat
                    </a>
                </div>
                <div class="card-body">
                    @if($conversations->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                            <h4>No conversations yet</h4>
                            <p class="text-muted">Start a new conversation with the AI assistant</p>
                            <a href="{{ route('chat.new') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Start Chatting
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($conversations as $conversation)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 conversation-card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $conversation->title }}</h5>
                                            <p class="card-text text-muted small">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $conversation->last_message_at->diffForHumans() }}
                                            </p>
                                            <p class="card-text text-muted small">
                                                <i class="fas fa-comment me-1"></i>
                                                {{ $conversation->messages->count() }} messages
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="{{ route('chat.show', $conversation->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-arrow-right me-1"></i>Open
                                            </a>
                                            <form action="{{ route('chat.delete', $conversation->id) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this conversation?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.conversation-card {
    transition: all 0.3s ease;
}

.conversation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
@endsection
