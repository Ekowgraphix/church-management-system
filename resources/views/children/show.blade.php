@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-4xl font-black text-white">👶 {{ $child->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('children.edit', $child) }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('children.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-card rounded-3xl p-8">
            <h2 class="text-2xl font-black text-white mb-6">Child Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-white/60 text-sm mb-2">Name</p>
                    <p class="text-white text-lg font-semibold">{{ $child->name }}</p>
                </div>

                <div>
                    <p class="text-white/60 text-sm mb-2">Date of Birth</p>
                    <p class="text-white text-lg font-semibold">{{ $child->dob }} (Age: {{ \Carbon\Carbon::parse($child->dob)->age }})</p>
                </div>

                <div>
                    <p class="text-white/60 text-sm mb-2">Guardian Name</p>
                    <p class="text-white text-lg font-semibold">{{ $child->guardian_name }}</p>
                </div>

                @if($child->guardian_contact)
                <div>
                    <p class="text-white/60 text-sm mb-2">Guardian Contact</p>
                    <p class="text-white text-lg font-semibold">
                        <i class="fas fa-phone mr-2 text-green-400"></i>{{ $child->guardian_contact }}
                    </p>
                </div>
                @endif

                @if($child->guardian_email)
                <div>
                    <p class="text-white/60 text-sm mb-2">Guardian Email</p>
                    <p class="text-white text-lg font-semibold">
                        <i class="fas fa-envelope mr-2 text-blue-400"></i>{{ $child->guardian_email }}
                    </p>
                </div>
                @endif

                @if($child->class_group)
                <div>
                    <p class="text-white/60 text-sm mb-2">Class/Group</p>
                    <p class="text-white text-lg font-semibold">{{ $child->class_group }}</p>
                </div>
                @endif
            </div>

            @if($child->medical_info)
            <div class="mt-6 glass-card rounded-2xl p-6 bg-red-500/10 border border-red-500/30">
                <p class="text-red-300 text-sm mb-2">
                    <i class="fas fa-medkit mr-2"></i>Medical Information
                </p>
                <p class="text-white">{{ $child->medical_info }}</p>
            </div>
            @endif

            @if($child->notes)
            <div class="mt-6 glass-card rounded-2xl p-6">
                <p class="text-white/60 text-sm mb-2">Notes</p>
                <p class="text-white">{{ $child->notes }}</p>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            @if($child->photo)
            <div class="glass-card rounded-2xl p-6">
                <img src="{{ Storage::url($child->photo) }}" alt="{{ $child->name }}" class="w-full rounded-xl">
            </div>
            @endif

            <div class="glass-card rounded-2xl p-6">
                <p class="text-white/60 text-sm mb-2">Registered</p>
                <p class="text-white font-semibold">{{ $child->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
