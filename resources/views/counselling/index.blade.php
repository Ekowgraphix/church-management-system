@extends('layouts.app')

@section('content')
<div>
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2 flex items-center">
                    <i class="fas fa-comments mr-3 text-neon-green"></i>
                    Counselling Sessions
                </h1>
                <p class="text-gray-300">Manage counselling records</p>
            </div>
            <a href="{{ route('counselling.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> New Session
            </a>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        @if($counsellings->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-white/10">
                        <th class="pb-4">Counsellor</th>
                        <th class="pb-4">Person</th>
                        <th class="pb-4">Session Date</th>
                        <th class="pb-4">Confidentiality</th>
                        <th class="pb-4">Follow-up</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-white">
                    @foreach($counsellings as $counselling)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-4">{{ $counselling->counsellor }}</td>
                        <td>
                            @if($counselling->member)
                                {{ $counselling->member->full_name }}
                            @elseif($counselling->visitor)
                                {{ $counselling->visitor->full_name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $counselling->session_date->format('M d, Y') }}</td>
                        <td>
                            <span class="px-3 py-1 rounded-full text-sm
                                {{ $counselling->confidentiality === 'Normal' ? 'bg-green-500/20 text-neon-green' : '' }}
                                {{ $counselling->confidentiality === 'Private' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                {{ $counselling->confidentiality === 'Strict' ? 'bg-red-500/20 text-red-400' : '' }}">
                                {{ $counselling->confidentiality }}
                            </span>
                        </td>
                        <td>{{ $counselling->follow_up_date ? $counselling->follow_up_date->format('M d, Y') : 'None' }}</td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('counselling.edit', $counselling) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('counselling.destroy', $counselling) }}" class="inline" onsubmit="return confirm('Are you sure?')">
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
            <div class="mt-6">{{ $counsellings->links() }}</div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-comments text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg">No counselling sessions yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
