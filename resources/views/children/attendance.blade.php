@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-pink-400/10 to-purple-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">✅ Children Attendance</h1>
                <p class="text-pink-200 text-lg">Mark attendance for today: {{ now()->format('l, F d, Y') }}</p>
            </div>
            <a href="{{ route('children.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-card rounded-2xl p-4 bg-green-500/20 border border-green-500/30">
            <i class="fas fa-check-circle text-green-300 mr-2"></i>
            <span class="text-white font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="glass-card rounded-3xl p-8">
        <form action="{{ route('children.attendance.mark') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">

            <div class="space-y-4">
                @forelse($children as $child)
                    <div class="glass-card rounded-2xl p-6 flex items-center justify-between hover:bg-white/10 transition">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 gradient-pink rounded-full flex items-center justify-center text-white text-xl font-bold">
                                {{ strtoupper(substr($child->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">{{ $child->name }}</h3>
                                <p class="text-white/60 text-sm">
                                    Age: {{ \Carbon\Carbon::parse($child->dob)->age }} • {{ $child->class_group ?? 'No group' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <label class="flex items-center space-x-2 glass-card px-4 py-2 rounded-lg cursor-pointer hover:bg-green-500/20 transition">
                                <input type="radio" name="attendance[{{ $child->id }}]" value="present" class="text-green-500" required>
                                <span class="text-white font-semibold">Present</span>
                            </label>

                            <label class="flex items-center space-x-2 glass-card px-4 py-2 rounded-lg cursor-pointer hover:bg-red-500/20 transition">
                                <input type="radio" name="attendance[{{ $child->id }}]" value="absent" class="text-red-500">
                                <span class="text-white font-semibold">Absent</span>
                            </label>

                            <label class="flex items-center space-x-2 glass-card px-4 py-2 rounded-lg cursor-pointer hover:bg-yellow-500/20 transition">
                                <input type="radio" name="attendance[{{ $child->id }}]" value="excused" class="text-yellow-500">
                                <span class="text-white font-semibold">Excused</span>
                            </label>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <i class="fas fa-child text-white/20 text-6xl mb-4"></i>
                        <p class="text-white/60 text-lg">No children registered</p>
                    </div>
                @endforelse
            </div>

            @if($children->count() > 0)
                <div class="mt-8 flex justify-center">
                    <button type="submit" class="glass-card px-12 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-green-500/20 to-blue-500/20 hover:scale-105 transition-all text-lg">
                        <i class="fas fa-save mr-2"></i>Save Attendance
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
