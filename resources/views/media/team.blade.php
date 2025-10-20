@extends('layouts.app')

@section('page-title', 'Media Team')
@section('page-subtitle', 'Manage media team members')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-blue-400/10 to-cyan-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">
                    👥 Media Team
                </h1>
                <p class="text-blue-200 text-lg">Manage photographers, videographers, and media team members</p>
            </div>
            <a href="{{ route('media.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-photo-video mr-2"></i>Media Library
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="glass-card rounded-2xl p-4 bg-green-500/20 border border-green-500/30">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-300 text-xl"></i>
                <span class="text-white font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Add Team Member Form -->
        <div class="glass-card rounded-3xl p-6">
            <h2 class="text-2xl font-black text-white mb-6">
                <i class="fas fa-user-plus mr-2"></i>Add Team Member
            </h2>

            <form action="{{ route('media.team.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Name *</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-blue-400 transition">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Role *</label>
                    <select name="role" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-blue-400 transition">
                        <option value="">Select Role</option>
                        <option value="Photographer">Photographer</option>
                        <option value="Videographer">Videographer</option>
                        <option value="Editor">Editor</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Graphic Designer">Graphic Designer</option>
                        <option value="Team Lead">Team Lead</option>
                    </select>
                    @error('role')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Contact</label>
                    <input type="text" name="contact"
                        placeholder="Phone number"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Email</label>
                    <input type="email" name="email"
                        placeholder="email@example.com"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Assigned Event</label>
                    <input type="text" name="assigned_event"
                        placeholder="Current assignment"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Status *</label>
                    <select name="status" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-blue-400 transition">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="w-full glass-card px-6 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-blue-500/20 to-cyan-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Member
                </button>
            </form>
        </div>

        <!-- Team Members List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-2xl font-black text-white mb-6">
                    Team Members ({{ $team->total() }})
                </h2>

                @if($team->count() > 0)
                    <div class="space-y-4">
                        @foreach($team as $member)
                            <div class="glass-card rounded-2xl p-6 hover:bg-white/10 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-4 flex-1">
                                        <div class="w-16 h-16 gradient-{{ $member->status === 'active' ? 'blue' : 'gray' }} rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="text-xl font-bold text-white">{{ $member->name }}</h3>
                                                <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $member->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }} text-white">
                                                    {{ strtoupper($member->status) }}
                                                </span>
                                            </div>

                                            <p class="text-cyan-300 font-semibold mb-3">
                                                <i class="fas fa-briefcase mr-2"></i>{{ $member->role }}
                                            </p>

                                            <div class="grid grid-cols-2 gap-4 text-sm">
                                                @if($member->contact)
                                                    <div class="text-white/70">
                                                        <i class="fas fa-phone mr-2 text-green-400"></i>{{ $member->contact }}
                                                    </div>
                                                @endif

                                                @if($member->email)
                                                    <div class="text-white/70">
                                                        <i class="fas fa-envelope mr-2 text-blue-400"></i>{{ $member->email }}
                                                    </div>
                                                @endif

                                                @if($member->assigned_event)
                                                    <div class="text-white/70 col-span-2">
                                                        <i class="fas fa-calendar mr-2 text-purple-400"></i>
                                                        <span class="font-semibold">Assignment:</span> {{ $member->assigned_event }}
                                                    </div>
                                                @endif
                                            </div>

                                            <p class="text-white/50 text-xs mt-3">
                                                <i class="fas fa-clock mr-1"></i>Added {{ $member->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <form action="{{ route('media.team.destroy', $member) }}" method="POST" onsubmit="return confirm('Remove this team member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-red-500/20 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $team->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <i class="fas fa-users text-white/20 text-6xl mb-4"></i>
                        <p class="text-white/60 text-lg">No team members yet</p>
                        <p class="text-white/40 text-sm">Add your first team member using the form on the left</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
