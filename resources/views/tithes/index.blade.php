@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500">
                    💎 Tithe Management
                </h1>
                <p class="text-gray-400 mt-2">Track and manage members' tithes with AI-powered insights</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="openAIInsights()" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-robot mr-2"></i> AI Insights
                </button>
                <a href="{{ route('tithes.export') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-file-excel mr-2"></i> Export
                </a>
                <a href="{{ route('tithes.create') }}" class="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus-circle mr-2"></i> Add Tithe
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/50 text-green-300 px-6 py-4 rounded-xl mb-6 animate-fade-in">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Analytics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="glass-card p-6 rounded-2xl border border-green-500/20 hover:border-green-500/50 transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 font-medium">Total Tithes</h3>
                    <i class="fas fa-money-bill-wave text-green-400 text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <p class="text-3xl font-bold text-white">GHS {{ number_format($stats['total'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $stats['count'] }} records</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-purple-500/20 hover:border-purple-500/50 transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 font-medium">This Month</h3>
                    <i class="fas fa-calendar-alt text-purple-400 text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <p class="text-3xl font-bold text-white">GHS {{ number_format($stats['month'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ now()->format('F Y') }}</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-blue-500/20 hover:border-blue-500/50 transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 font-medium">This Year</h3>
                    <i class="fas fa-chart-line text-blue-400 text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <p class="text-3xl font-bold text-white">GHS {{ number_format($stats['year'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ now()->format('Y') }} Total</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-yellow-500/20 hover:border-yellow-500/50 transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-gray-400 font-medium">Top Givers</h3>
                    <i class="fas fa-trophy text-yellow-400 text-2xl group-hover:scale-110 transition-transform"></i>
                </div>
                <p class="text-3xl font-bold text-white">{{ $topGivers->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">Faithful Partners</p>
            </div>
        </div>

        @include('tithes.partials.charts')
        @include('tithes.partials.top-givers')
        @include('tithes.partials.filters')
        @include('tithes.partials.table')
    </div>
</div>

@include('tithes.partials.ai-modal')
@include('tithes.partials.scripts')

@endsection
