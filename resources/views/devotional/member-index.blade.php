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
            <div class="bg-white/5 p-6 rounded-xl mb-6">
                <h3 class="text-xl font-bold text-white mb-4">Today's Message</h3>
                <div class="text-gray-200 leading-relaxed space-y-3">
                    {!! nl2br(e($today->message)) !!}
                </div>
            </div>

            <!-- Reflection Questions -->
            @if($today->reflection_questions)
                <div class="bg-white/5 p-6 rounded-xl mb-6">
                    <h3 class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-lightbulb mr-2 text-yellow-400"></i>
                        Reflection Questions
                    </h3>
                    <div class="text-gray-200">
                        {!! nl2br(e($today->reflection_questions)) !!}
                    </div>
                </div>
            @endif

            <!-- Prayer -->
            @if($today->prayer)
                <div class="bg-white/5 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-white mb-4">
                        <i class="fas fa-praying-hands mr-2 text-purple-400"></i>
                        Prayer
                    </h3>
                    <p class="text-gray-200 italic">
                        {!! nl2br(e($today->prayer)) !!}
                    </p>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-white/10">
                <a href="{{ route('devotional.archive') }}" class="text-green-300 hover:text-green-200 font-semibold">
                    <i class="fas fa-archive mr-2"></i>
                    View Archive
                </a>
                @if($today->author)
                    <p class="text-gray-400 text-sm">By {{ $today->author }}</p>
                @endif
            </div>
        </div>
    @else
        <div class="glass-card p-12 rounded-2xl text-center">
            <i class="fas fa-bible text-green-400 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-white mb-2">No Devotional for Today</h3>
            <p class="text-gray-300 mb-6">Check back tomorrow or browse our archive</p>
            <a href="{{ route('devotional.archive') }}" class="btn btn-primary inline-block">
                <i class="fas fa-archive mr-2"></i>
                Browse Archive
            </a>
        </div>
    @endif

    <!-- Recent Devotionals -->
    @if($recent->count() > 0)
        <div class="glass-card p-6 rounded-2xl">
            <h3 class="text-2xl font-bold text-white mb-4">
                <i class="fas fa-history mr-2 text-green-400"></i>
                Recent Devotionals
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recent as $devotional)
                    <a href="{{ route('devotional.show', $devotional->devotional_date->format('Y-m-d')) }}" 
                       class="bg-white/5 hover:bg-white/10 p-4 rounded-xl transition group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-green-300 text-xs font-semibold">
                                {{ $devotional->devotional_date->format('M d, Y') }}
                            </span>
                            <i class="fas fa-arrow-right text-green-400 opacity-0 group-hover:opacity-100 transition"></i>
                        </div>
                        <h4 class="text-white font-bold mb-1">{{ $devotional->title }}</h4>
                        <p class="text-gray-400 text-sm">{{ $devotional->scripture_reference }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
