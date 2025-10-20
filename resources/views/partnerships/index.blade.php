@extends('layouts.app')

@section('content')
<div>
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2 flex items-center">
                    <i class="fas fa-handshake mr-3 text-neon-green"></i>
                    Partnerships
                </h1>
                <p class="text-gray-300">Manage church partnerships and sponsors</p>
            </div>
            <a href="{{ route('partnerships.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> New Partnership
            </a>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        @if($partnerships->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-white/10">
                        <th class="pb-4">Organization</th>
                        <th class="pb-4">Contact Person</th>
                        <th class="pb-4">Phone</th>
                        <th class="pb-4">Start Date</th>
                        <th class="pb-4">Status</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-white">
                    @foreach($partnerships as $partnership)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-4">{{ $partnership->organization }}</td>
                        <td>{{ $partnership->contact_person }}</td>
                        <td>{{ $partnership->phone }}</td>
                        <td>{{ $partnership->start_date->format('M d, Y') }}</td>
                        <td>
                            <span class="px-3 py-1 rounded-full text-sm
                                {{ $partnership->status === 'Active' ? 'bg-green-500/20 text-neon-green' : '' }}
                                {{ $partnership->status === 'Inactive' ? 'bg-gray-500/20 text-gray-400' : '' }}
                                {{ $partnership->status === 'Pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}">
                                {{ $partnership->status }}
                            </span>
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('partnerships.edit', $partnership) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('partnerships.destroy', $partnership) }}" class="inline" onsubmit="return confirm('Are you sure?')">
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
            <div class="mt-6">{{ $partnerships->links() }}</div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-handshake text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg">No partnerships yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
