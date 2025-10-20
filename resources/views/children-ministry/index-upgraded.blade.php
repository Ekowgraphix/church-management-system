@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Enhanced Children Ministry Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-yellow-400/10 to-orange-400/10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">👧🏾 Children Ministry</h1>
                <p class="text-yellow-200 text-lg">Nurturing the next generation of believers</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showAttendanceTracker()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-clipboard-check mr-2"></i>Attendance
                </button>
                <button onclick="showTeachers()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>Teachers
                </button>
                <button onclick="showEvents()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-calendar-star mr-2"></i>Events
                </button>
                <button onclick="exportChildren()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
                <a href="{{ route('children-ministry.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-yellow-500/20 to-orange-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>Register Child
                </a>
            </div>
        </div>

        <!-- Enhanced Statistics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="glass-card rounded-2xl p-6 gradient-blue">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-users text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['total'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Total Children</p>
                <p class="text-white/60 text-xs mt-1">Registered</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-green">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-check-circle text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['present_today'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Present Today</p>
                <p class="text-white/60 text-xs mt-1">{{ $stats['attendance_rate'] ?? 0 }}% rate</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-purple">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-layer-group text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['classes'] ?? 5 }}</span>
                </div>
                <p class="text-white/90 font-medium">Classes</p>
                <p class="text-white/60 text-xs mt-1">Active groups</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-orange">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['teachers'] ?? 8 }}</span>
                </div>
                <p class="text-white/90 font-medium">Teachers</p>
                <p class="text-white/60 text-xs mt-1">Serving</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-pink">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-birthday-cake text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['birthdays'] ?? 3 }}</span>
                </div>
                <p class="text-white/90 font-medium">Birthdays</p>
                <p class="text-white/60 text-xs mt-1">This month</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-cyan">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-trophy text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['milestones'] ?? 12 }}</span>
                </div>
                <p class="text-white/90 font-medium">Milestones</p>
                <p class="text-white/60 text-xs mt-1">Achieved</p>
            </div>
        </div>
    </div>

    @include('children-ministry.partials.age-groups')
    @include('children-ministry.partials.children-list')
    @include('children-ministry.partials.sidebar')

</div>

@include('children-ministry.partials.scripts')
@endsection
