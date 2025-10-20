@extends('layouts.app')

@section('page-title', 'Children Ministry')
@section('page-subtitle', 'Manage children registrations')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-pink-400/10 to-purple-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">👧 Children Ministry</h1>
                <p class="text-pink-200 text-lg">Manage children registrations and attendance</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('children.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>Register Child
                </a>
                <a href="{{ route('children.attendance') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-check-circle mr-2"></i>Attendance
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-card rounded-2xl p-4 bg-green-500/20 border border-green-500/30">
            <i class="fas fa-check-circle text-green-300 mr-2"></i>
            <span class="text-white font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="glass-card rounded-3xl p-8">
        <h2 class="text-2xl font-black text-white mb-6">All Children ({{ $children->total() }})</h2>
        
        @if($children->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($children as $child)
                    <div class="glass-card rounded-2xl p-6 hover:bg-white/10 transition">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 gradient-pink rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($child->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">{{ $child->name }}</h3>
                                <p class="text-white/60 text-sm">
                                    @if($child->dob)
                                        Age: {{ \Carbon\Carbon::parse($child->dob)->age }} years
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <p class="text-white/70 text-sm">
                                <i class="fas fa-user mr-2 text-blue-400"></i>{{ $child->guardian_name }}
                            </p>
                            @if($child->guardian_contact)
                                <p class="text-white/70 text-sm">
                                    <i class="fas fa-phone mr-2 text-green-400"></i>{{ $child->guardian_contact }}
                                </p>
                            @endif
                            @if($child->class_group)
                                <p class="text-white/70 text-sm">
                                    <i class="fas fa-users mr-2 text-purple-400"></i>{{ $child->class_group }}
                                </p>
                            @endif
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('children.show', $child) }}" class="flex-1 glass-card px-3 py-2 rounded-lg text-center text-white hover:bg-white/10 transition text-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('children.edit', $child) }}" class="flex-1 glass-card px-3 py-2 rounded-lg text-center text-white hover:bg-white/10 transition text-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $children->links() }}</div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-child text-white/20 text-6xl mb-4"></i>
                <p class="text-white/60 text-lg mb-6">No children registered yet</p>
                <a href="{{ route('children.create') }}" class="glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all inline-block">
                    <i class="fas fa-plus mr-2"></i>Register First Child
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
