@extends('layouts.app')

@section('content')
<div>
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2 flex items-center">
                    <i class="fas fa-video mr-3 text-neon-green"></i>
                    Media Teams
                </h1>
                <p class="text-gray-300">Manage media department volunteers</p>
            </div>
            <a href="{{ route('media-teams.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Add Member
            </a>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        @if($mediaTeams->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-white/10">
                        <th class="pb-4">Member</th>
                        <th class="pb-4">Role</th>
                        <th class="pb-4">Availability</th>
                        <th class="pb-4">Assigned Program</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-white">
                    @foreach($mediaTeams as $team)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-4">{{ $team->member->full_name }}</td>
                        <td>
                            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">
                                {{ $team->role }}
                            </span>
                        </td>
                        <td>{{ $team->availability ?? 'Not specified' }}</td>
                        <td>{{ $team->assigned_program ?? 'Not assigned' }}</td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('media-teams.edit', $team) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('media-teams.destroy', $team) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-red-500/20 text-red-400">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-6">{{ $mediaTeams->links() }}</div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-video text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg">No media team members yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
