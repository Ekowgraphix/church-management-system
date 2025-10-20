@extends('layouts.member-portal')

@section('page-title', 'Daily Devotional')
@section('page-subtitle', 'Feed your soul with God\'s Word')

@section('content')
<div class="space-y-6">
    @if($today)
        <!-- Today's Devotional -->
        <div class="glass-card p-8 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-3xl font-black text-white mb-2">{{ $today->title }}</h2>
                    <p class="text-green-300 text-sm">
                        <i class="fas fa-calendar-day mr-2"></i>
                        {{ $today->devotional_date->format('l, F j, Y') }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-bible text-2xl text-white"></i>
                </div>
            </div>

            <!-- Scripture -->
            <div class="bg-white/5 p-6 rounded-xl mb-6">
                <p class="text-green-400 font-semibold mb-3">
                    <i class="fas fa-book-open mr-2"></i>
                    {{ $today->scripture_reference }}
                </p>
                <p class="text-white text-lg italic leading-relaxed">
                    "{{ $today->scripture_text }}"
                </p>
            </div>

            <!-- Message -->
            <div class="mb-6">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-comment-alt mr-2 text-green-400"></i>
                    Today's Message
                </h3>
                <div class="text-gray-300 leading-relaxed space-y-4">
                    {!! nl2br(e($today->message)) !!}
                </div>
            </div>

            @if($today->reflection_questions)
                <!-- Reflection Questions -->
                <div class="bg-white/5 p-6 rounded-xl mb-6">
                    <h3 class="text-lg font-bold text-white mb-3 flex items-center">
                        <i class="fas fa-lightbulb mr-2 text-yellow-400"></i>
                        Reflect on This
                    </h3>
                    <div class="text-gray-300">
                        {!! nl2br(e($today->reflection_questions)) !!}
                    </div>
                </div>
            @endif

            @if($today->prayer)
                <!-- Prayer -->
                <div class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 p-6 rounded-xl border border-purple-400/30">
                    <h3 class="text-lg font-bold text-white mb-3 flex items-center">
                        <i class="fas fa-praying-hands mr-2 text-purple-300"></i>
                        Prayer of the Day
                    </h3>
                    <p class="text-white italic">
                        {!! nl2br(e($today->prayer)) !!}
                    </p>
                </div>
            @endif

            @if($today->author)
                <p class="text-gray-400 text-sm mt-6 text-right">
                    <i class="fas fa-user-edit mr-1"></i>
                    By {{ $today->author }}
                </p>
            @endif

            <!-- Share Buttons -->
            <div class="flex items-center justify-center space-x-4 mt-8 pt-6 border-t border-white/10">
                <button onclick="shareDevotional()" class="px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl text-white transition-all">
                    <i class="fas fa-share-alt mr-2"></i>
                    Share
                </button>
                <button onclick="printDevotional()" class="px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl text-white transition-all">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </button>
            </div>
        </div>
    @else
        <!-- No Devotional Today -->
        <div class="glass-card p-12 rounded-2xl text-center">
            <div class="w-20 h-20 bg-gray-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-bible text-3xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-3">No Devotional for Today</h2>
            <p class="text-gray-400 mb-6">Check back later or browse previous devotionals</p>
            <a href="{{ route('devotional.archive') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all">
                <i class="fas fa-archive mr-2"></i>
                Browse Archive
            </a>
        </div>
    @endif

    <!-- Recent Devotionals -->
    @if($recent->count() > 1)
        <div class="glass-card p-6 rounded-2xl">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-history mr-2 text-green-400"></i>
                Recent Devotionals
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recent as $devotional)
                    @if(!$today || $devotional->id != $today->id)
                        <a href="{{ route('devotional.show', $devotional->devotional_date->format('Y-m-d')) }}" 
                           class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition-all">
                            <p class="text-green-400 text-sm mb-2">
                                {{ $devotional->devotional_date->format('M j, Y') }}
                            </p>
                            <h4 class="text-white font-semibold mb-2">{{ Str::limit($devotional->title, 50) }}</h4>
                            <p class="text-gray-400 text-xs">
                                <i class="fas fa-book-open mr-1"></i>
                                {{ $devotional->scripture_reference }}
                            </p>
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('devotional.archive') }}" class="text-green-400 hover:text-green-300 text-sm">
                    View All Devotionals <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function shareDevotional() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $today ? $today->title : "Daily Devotional" }}',
                text: '{{ $today ? Str::limit($today->message, 100) : "" }}',
                url: window.location.href
            });
        } else {
            // Fallback: Copy to clipboard
            navigator.clipboard.writeText(window.location.href);
            alert('Link copied to clipboard!');
        }
    }

    function printDevotional() {
        window.print();
    }
</script>
@endpush
@endsection
