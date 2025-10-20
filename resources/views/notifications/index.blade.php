@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🔔 Notifications</h1>
                <p class="text-gray-600">Stay updated with church activities and announcements</p>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        <i class="fas fa-check-double mr-2"></i>
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($unreadCount > 0)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
            <p class="text-blue-700">
                <i class="fas fa-info-circle mr-2"></i>
                You have <strong>{{ $unreadCount }}</strong> unread notification{{ $unreadCount > 1 ? 's' : '' }}.
            </p>
        </div>
    @endif

    <!-- Notifications List -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @forelse($notifications as $notification)
            <div class="p-6 border-b {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full {{ $notification->is_read ? 'bg-gray-200' : 'bg-blue-500' }} flex items-center justify-center">
                            @switch($notification->type)
                                @case('event')
                                    <i class="fas fa-calendar text-white text-xl"></i>
                                    @break
                                @case('prayer')
                                    <i class="fas fa-praying-hands text-white text-xl"></i>
                                    @break
                                @case('message')
                                    <i class="fas fa-envelope text-white text-xl"></i>
                                    @break
                                @case('announcement')
                                    <i class="fas fa-bullhorn text-white text-xl"></i>
                                    @break
                                @default
                                    <i class="fas fa-bell text-white text-xl"></i>
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    {{ $notification->title }}
                                </h3>
                                <p class="text-gray-700 mb-2">{{ $notification->message }}</p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="ml-4">
                                    @csrf
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                                        Mark as Read
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        @if($notification->action_url)
                            <a href="{{ $notification->action_url }}" class="inline-flex items-center mt-3 text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                                View Details
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No Notifications Yet</h3>
                <p class="text-gray-600">When you have new updates, they'll appear here.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
