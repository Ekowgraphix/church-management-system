@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2 flex items-center">
                    <i class="fas fa-archive mr-3 text-green-400"></i>
                    Devotional Archive
                </h1>
                <p class="text-gray-300">Browse past devotionals</p>
            </div>
            <a href="{{ route('devotional.index') }}" class="text-green-400 hover:text-green-300 flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Today</span>
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex items-center space-x-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    <option value="">All Months</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                <select name="year" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    <option value="">All Years</option>
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="pt-6">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Devotionals List -->
    @if($devotionals->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($devotionals as $devotional)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-6 text-white">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold">
                                {{ $devotional->devotional_date->format('M d, Y') }}
                            </span>
                            <i class="fas fa-bible text-2xl opacity-50"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ $devotional->title }}</h3>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <p class="text-indigo-600 font-semibold mb-3 text-sm">
                            <i class="fas fa-book-open mr-1"></i>
                            {{ $devotional->scripture_reference }}
                        </p>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ Str::limit($devotional->message, 120) }}
                        </p>
                        <a href="{{ route('devotional.show', $devotional->devotional_date->format('Y-m-d')) }}" 
                           class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Read Devotional
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $devotionals->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <i class="fas fa-bible text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No Devotionals Found</h3>
            <p class="text-gray-600">Try adjusting your filter criteria</p>
        </div>
    @endif
</div>
@endsection
