@extends('layouts.app')

@section('page-title', 'Visitor Details')
@section('page-subtitle', $visitor->first_name . ' ' . $visitor->last_name)

@section('content')
<div class="max-w-6xl">
    <!-- Visitor Info Card -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 gradient-cyan rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-2xl">
                    {{ strtoupper(substr($visitor->first_name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white">{{ $visitor->first_name }} {{ $visitor->last_name }}</h2>
                    <p class="text-green-300 mt-1">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $visitor->visit_type == 'first_time' ? 'bg-blue-500/20 text-blue-300' : '' }}
                            {{ $visitor->visit_type == 'returning' ? 'bg-green-500/20 text-green-300' : '' }}
                            {{ $visitor->visit_type == 'guest' ? 'bg-purple-500/20 text-purple-300' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $visitor->visit_type)) }} Visitor
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('visitors.edit', $visitor) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form method="POST" action="{{ route('visitors.destroy', $visitor) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-gray-400 text-sm mb-1">Phone</p>
                <p class="text-white font-semibold">{{ $visitor->phone }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Email</p>
                <p class="text-white font-semibold">{{ $visitor->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Visit Date</p>
                <p class="text-white font-semibold">{{ $visitor->visit_date ? $visitor->visit_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Follow-up Status</p>
                <p class="text-white font-semibold">
                    <span class="px-3 py-1 rounded-full text-sm
                        {{ $visitor->followup_status == 'pending' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                        {{ $visitor->followup_status == 'contacted' ? 'bg-blue-500/20 text-blue-300' : '' }}
                        {{ $visitor->followup_status == 'completed' ? 'bg-green-500/20 text-green-300' : '' }}
                        {{ $visitor->followup_status == 'no_response' ? 'bg-red-500/20 text-red-300' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $visitor->followup_status ?? 'pending')) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Service Attended</p>
                <p class="text-white font-semibold">{{ $visitor->service_attended ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Wants Follow-up</p>
                <p class="text-white font-semibold">{{ $visitor->wants_followup ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        @if($visitor->address)
            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-gray-400 text-sm mb-1">Address</p>
                <p class="text-white">{{ $visitor->address }}</p>
            </div>
        @endif

        @if($visitor->notes)
            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-gray-400 text-sm mb-1">Notes</p>
                <p class="text-white">{{ $visitor->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Convert to Member Button -->
    @if(!$visitor->converted_to_member)
        <div class="glass-card p-6 rounded-2xl mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white mb-2">Convert to Member</h3>
                    <p class="text-gray-400">Convert this visitor to a church member</p>
                </div>
                <button onclick="document.getElementById('convertModal').classList.remove('hidden')" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Convert to Member
                </button>
            </div>
        </div>
    @else
        <div class="glass-card p-6 rounded-2xl mb-6 border-l-4 border-green-500">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-300 text-2xl"></i>
                <div>
                    <p class="text-white font-bold">Converted to Member</p>
                    <p class="text-gray-400 text-sm">This visitor has been converted to a church member</p>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Convert to Member Modal -->
<div id="convertModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="glass-card max-w-md w-full mx-4 p-8 rounded-2xl">
        <h3 class="text-2xl font-bold text-white mb-6">Convert to Member</h3>
        <form method="POST" action="{{ route('visitors.convert', $visitor) }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-semibold text-green-300 mb-2">Membership Date *</label>
                <input type="date" name="membership_date" value="{{ date('Y-m-d') }}" required 
                       class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="btn btn-success flex-1">
                    <i class="fas fa-check"></i> Convert
                </button>
                <button type="button" onclick="document.getElementById('convertModal').classList.add('hidden')" class="btn btn-outline">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
