@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-blue-400/10 to-purple-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">📅 Counselling Calendar</h1>
                <p class="text-blue-200 text-lg">View all scheduled counselling sessions</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('counselling.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>Book Session
                </a>
                <a href="{{ route('counselling.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-list mr-2"></i>List View
                </a>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-3xl p-8">
        <div class="text-center py-16">
            <i class="fas fa-calendar-alt text-white/20 text-6xl mb-4"></i>
            <p class="text-white/60 text-lg">Calendar View Coming Soon!</p>
            <p class="text-white/40 text-sm mt-2">A visual calendar of all counselling sessions will be displayed here</p>
            <a href="{{ route('counselling.index') }}" class="mt-6 inline-block glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-list mr-2"></i>View List Instead
            </a>
        </div>
    </div>
</div>
@endsection
