@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('devotional.index') }}" class="text-green-400 hover:text-green-300 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Devotionals</span>
        </a>
    </div>

    <!-- Devotional Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-8 text-white">
            <div class="flex items-center justify-between mb-4">
                <span class="px-4 py-2 bg-white/20 rounded-full text-sm font-semibold">
                    <i class="fas fa-calendar mr-2"></i>
                    {{ $devotional->devotional_date->format('l, F j, Y') }}
                </span>
                @if($devotional->author)
                    <span class="text-sm">By {{ $devotional->author }}</span>
                @endif
            </div>
            <h1 class="text-4xl font-bold mb-2">{{ $devotional->title }}</h1>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Scripture Reference -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-8">
                <p class="text-blue-600 font-semibold mb-3 flex items-center">
                    <i class="fas fa-book-open mr-2"></i>
                    {{ $devotional->scripture_reference }}
                </p>
                <p class="text-gray-800 text-lg italic leading-relaxed">
                    "{{ $devotional->scripture_text }}"
                </p>
            </div>

            <!-- Message -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Devotional Message</h3>
                <div class="text-gray-700 leading-relaxed space-y-4">
                    {!! nl2br(e($devotional->message)) !!}
                </div>
            </div>

            <!-- Reflection Questions -->
            @if($devotional->reflection_questions)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-lightbulb mr-2 text-yellow-600"></i>
                        Reflection Questions
                    </h3>
                    <div class="text-gray-700">
                        {!! nl2br(e($devotional->reflection_questions)) !!}
                    </div>
                </div>
            @endif

            <!-- Prayer -->
            @if($devotional->prayer)
                <div class="bg-purple-50 border-l-4 border-purple-500 p-6 rounded-lg mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-praying-hands mr-2 text-purple-600"></i>
                        Prayer
                    </h3>
                    <p class="text-gray-700 italic">
                        {!! nl2br(e($devotional->prayer)) !!}
                    </p>
                </div>
            @endif
        </div>

        <!-- Navigation -->
        <div class="border-t border-gray-200 p-6 bg-gray-50">
            <div class="flex items-center justify-between">
                @if($previous)
                    <a href="{{ route('devotional.show', $previous->devotional_date->format('Y-m-d')) }}" 
                       class="flex items-center space-x-2 text-indigo-600 hover:text-indigo-800 font-semibold">
                        <i class="fas fa-arrow-left"></i>
                        <span>Previous Day</span>
                    </a>
                @else
                    <div></div>
                @endif

                <a href="{{ route('devotional.archive') }}" 
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition">
                    <i class="fas fa-archive mr-2"></i>
                    Archive
                </a>

                @if($next)
                    <a href="{{ route('devotional.show', $next->devotional_date->format('Y-m-d')) }}" 
                       class="flex items-center space-x-2 text-indigo-600 hover:text-indigo-800 font-semibold">
                        <span>Next Day</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                @else
                    <div></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
