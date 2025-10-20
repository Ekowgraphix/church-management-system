@extends('layouts.app')

@section('page-title', 'Volunteer Management')
@section('page-subtitle', 'Manage volunteer roles and assignments')

@section('content')
<div>
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-3">
            <a href="{{ route('volunteers.assignments') }}" class="btn btn-secondary">
                <i class="fas fa-calendar-alt"></i> View Schedule
            </a>
        </div>
        <a href="{{ route('volunteers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Role
        </a>
    </div>

    <!-- Volunteer Roles Grid -->
    @if($roles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($roles as $role)
                <div class="glass-card p-6 rounded-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $role->name }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $role->department == 'worship' ? 'bg-purple-500/20 text-purple-300' : '' }}
                                {{ $role->department == 'children' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                                {{ $role->department == 'hospitality' ? 'bg-pink-500/20 text-pink-300' : '' }}
                                {{ $role->department == 'media' ? 'bg-blue-500/20 text-blue-300' : '' }}
                                {{ $role->department == 'security' ? 'bg-red-500/20 text-red-300' : '' }}
                                {{ $role->department == 'ushering' ? 'bg-green-500/20 text-green-300' : '' }}">
                                {{ ucfirst($role->department) }}
                            </span>
                        </div>
                        <div class="w-12 h-12 gradient-purple rounded-xl flex items-center justify-center">
                            <i class="fas fa-hands-helping text-white text-xl"></i>
                        </div>
                    </div>

                    @if($role->description)
                        <p class="text-gray-400 text-sm mb-4">{{ $role->description }}</p>
                    @endif

                    <div class="bg-white/5 p-3 rounded-xl mb-4">
                        <p class="text-gray-400 text-xs mb-1">Required Volunteers</p>
                        <p class="text-white font-bold text-lg">{{ $role->required_volunteers }}</p>
                    </div>

                    <div class="bg-white/5 p-3 rounded-xl mb-4">
                        <p class="text-gray-400 text-xs mb-1">Upcoming Assignments</p>
                        <p class="text-green-300 font-bold text-lg">{{ $role->assignments->count() }}</p>
                    </div>

                    <button onclick="scheduleVolunteer({{ $role->id }})" class="btn btn-primary btn-sm w-full">
                        <i class="fas fa-user-plus"></i> Schedule Volunteer
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <div class="glass-card p-16 rounded-2xl text-center">
            <i class="fas fa-hands-helping text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-lg mb-2">No volunteer roles found</p>
            <p class="text-gray-500 text-sm mb-6">Create your first volunteer role</p>
            <a href="{{ route('volunteers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create First Role
            </a>
        </div>
    @endif
</div>

<script>
function scheduleVolunteer(roleId) {
    window.location.href = '/volunteers/assignments?role=' + roleId;
}
</script>
@endsection
