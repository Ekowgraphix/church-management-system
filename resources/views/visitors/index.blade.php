@extends('layouts.app')

@section('page-title', 'Visitors Management')
@section('page-subtitle', 'Track and manage church visitors')

@section('content')
<div>
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-3">
            <form method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search visitors..." 
                       class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                <select name="followup_status" onchange="this.form.submit()" 
                        class="px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('followup_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="contacted" {{ request('followup_status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="completed" {{ request('followup_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="no_response" {{ request('followup_status') == 'no_response' ? 'selected' : '' }}>No Response</option>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
        <a href="{{ route('visitors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Visitor
        </a>
    </div>

    <!-- Visitors List -->
    <div class="glass-card p-6 rounded-2xl">
        @if($visitors->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="px-6 py-4 text-left text-sm font-bold text-green-300">Visitor</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-green-300">Contact</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-green-300">Visit Date</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-green-300">Type</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-green-300">Follow-up</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-green-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($visitors as $visitor)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 gradient-cyan rounded-xl flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($visitor->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ $visitor->first_name }} {{ $visitor->last_name }}</p>
                                            <p class="text-sm text-gray-400">{{ $visitor->service_attended ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-white text-sm">{{ $visitor->phone }}</p>
                                    <p class="text-gray-400 text-xs">{{ $visitor->email ?? 'No email' }}</p>
                                </td>
                                <td class="px-6 py-4 text-white text-sm">
                                    {{ $visitor->visit_date ? $visitor->visit_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $visitor->visit_type == 'first_time' ? 'bg-blue-500/20 text-blue-300' : '' }}
                                        {{ $visitor->visit_type == 'returning' ? 'bg-green-500/20 text-green-300' : '' }}
                                        {{ $visitor->visit_type == 'guest' ? 'bg-purple-500/20 text-purple-300' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $visitor->visit_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $visitor->followup_status == 'pending' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                                        {{ $visitor->followup_status == 'contacted' ? 'bg-blue-500/20 text-blue-300' : '' }}
                                        {{ $visitor->followup_status == 'completed' ? 'bg-green-500/20 text-green-300' : '' }}
                                        {{ $visitor->followup_status == 'no_response' ? 'bg-red-500/20 text-red-300' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $visitor->followup_status ?? 'pending')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('visitors.show', $visitor) }}" class="btn btn-secondary btn-sm btn-icon">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('visitors.edit', $visitor) }}" class="btn btn-primary btn-sm btn-icon">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('visitors.destroy', $visitor) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-icon">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $visitors->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-user-friends text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg mb-2">No visitors found</p>
                <p class="text-gray-500 text-sm mb-6">Start by adding your first visitor</p>
                <a href="{{ route('visitors.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Visitor
                </a>
            </div>
        @endif
    </div>
</div>
@endsection