@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Enhanced Header with Stats -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-purple-400/10 to-pink-400/10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">👥 Visitors Management</h1>
                <p class="text-purple-200 text-lg">Track, engage, and convert visitors to members</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showBulkActions()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-tasks mr-2"></i>Bulk Actions
                </button>
                <button onclick="exportVisitors()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
                <a href="{{ route('visitors.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-purple-500/20 to-pink-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Visitor
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="glass-card rounded-2xl p-6 gradient-blue">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-users text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['total'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Total Visitors</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-green">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['this_month'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">This Month</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-purple">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-star text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['first_time'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">First Timers</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-orange">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-clock text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['pending_followup'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Pending Follow-up</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-cyan">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-user-check text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['converted'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Converted</p>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="glass-card rounded-2xl p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-white font-semibold mb-2 text-sm">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Name, phone, email..." 
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-purple-400 transition">
            </div>

            <div>
                <label class="block text-white font-semibold mb-2 text-sm">Visit Type</label>
                <select name="visit_type" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-purple-400 transition">
                    <option value="">All Types</option>
                    <option value="first_time">First Time</option>
                    <option value="returning">Returning</option>
                    <option value="guest">Guest</option>
                </select>
            </div>

            <div>
                <label class="block text-white font-semibold mb-2 text-sm">Follow-up Status</label>
                <select name="followup_status" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-purple-400 transition">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="contacted">Contacted</option>
                    <option value="completed">Completed</option>
                    <option value="no_response">No Response</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 glass-card px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-purple-500/20 to-pink-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('visitors.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:bg-white/10 transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Visitors Grid/List Toggle -->
    <div class="flex items-center justify-between">
        <div class="flex space-x-2">
            <button onclick="toggleView('grid')" id="gridViewBtn" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-white/10 transition">
                <i class="fas fa-th mr-2"></i>Grid
            </button>
            <button onclick="toggleView('list')" id="listViewBtn" class="glass-card px-4 py-2 rounded-lg text-white bg-white/10">
                <i class="fas fa-list mr-2"></i>List
            </button>
        </div>
        
        <p class="text-white/60">Showing {{ $visitors->count() }} of {{ $visitors->total() }} visitors</p>
    </div>

    <!-- Grid View -->
    <div id="gridView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($visitors as $visitor)
        <div class="glass-card rounded-2xl p-6 hover:bg-white/10 transition-all hover:scale-105">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-14 h-14 gradient-purple rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($visitor->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">{{ $visitor->first_name }} {{ $visitor->last_name }}</h3>
                        <p class="text-white/60 text-sm">{{ $visitor->service_attended ?? 'N/A' }}</p>
                    </div>
                </div>
                
                <span class="px-3 py-1 rounded-full text-xs font-bold 
                    {{ $visitor->visit_type == 'first_time' ? 'bg-blue-500' : '' }}
                    {{ $visitor->visit_type == 'returning' ? 'bg-green-500' : '' }}
                    {{ $visitor->visit_type == 'guest' ? 'bg-purple-500' : '' }} text-white">
                    {{ ucfirst(str_replace('_', ' ', $visitor->visit_type)) }}
                </span>
            </div>

            <div class="space-y-2 mb-4">
                <div class="flex items-center text-white/80 text-sm">
                    <i class="fas fa-phone w-5 text-blue-400"></i>
                    <span>{{ $visitor->phone }}</span>
                </div>
                @if($visitor->email)
                <div class="flex items-center text-white/80 text-sm">
                    <i class="fas fa-envelope w-5 text-green-400"></i>
                    <span>{{ $visitor->email }}</span>
                </div>
                @endif
                <div class="flex items-center text-white/80 text-sm">
                    <i class="fas fa-calendar w-5 text-purple-400"></i>
                    <span>{{ $visitor->visit_date ? $visitor->visit_date->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-white/10">
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $visitor->followup_status == 'pending' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                    {{ $visitor->followup_status == 'contacted' ? 'bg-blue-500/20 text-blue-300' : '' }}
                    {{ $visitor->followup_status == 'completed' ? 'bg-green-500/20 text-green-300' : '' }}
                    {{ $visitor->followup_status == 'no_response' ? 'bg-red-500/20 text-red-300' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $visitor->followup_status ?? 'pending')) }}
                </span>

                <div class="flex space-x-2">
                    <a href="{{ route('visitors.show', $visitor) }}" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-white/10 transition" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('visitors.edit', $visitor) }}" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-white/10 transition" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- List View -->
    <div id="listView" class="glass-card rounded-3xl overflow-hidden">
        @if($visitors->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/5">
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" onclick="toggleAll(this)" class="rounded">
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-purple-300">Visitor</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-purple-300">Contact</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-purple-300">Visit Date</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-purple-300">Type</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-purple-300">Follow-up</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-purple-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="visitor-checkbox rounded" value="{{ $visitor->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 gradient-purple rounded-xl flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($visitor->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-white">{{ $visitor->first_name }} {{ $visitor->last_name }}</p>
                                        <p class="text-sm text-white/60">{{ $visitor->service_attended ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-white text-sm font-semibold">{{ $visitor->phone }}</p>
                                <p class="text-white/60 text-xs">{{ $visitor->email ?? 'No email' }}</p>
                            </td>
                            <td class="px-6 py-4 text-white text-sm font-semibold">
                                {{ $visitor->visit_date ? $visitor->visit_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $visitor->visit_type == 'first_time' ? 'bg-blue-500' : '' }}
                                    {{ $visitor->visit_type == 'returning' ? 'bg-green-500' : '' }}
                                    {{ $visitor->visit_type == 'guest' ? 'bg-purple-500' : '' }} text-white">
                                    {{ ucfirst(str_replace('_', ' ', $visitor->visit_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $visitor->followup_status == 'pending' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                                    {{ $visitor->followup_status == 'contacted' ? 'bg-blue-500/20 text-blue-300' : '' }}
                                    {{ $visitor->followup_status == 'completed' ? 'bg-green-500/20 text-green-300' : '' }}
                                    {{ $visitor->followup_status == 'no_response' ? 'bg-red-500/20 text-red-300' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $visitor->followup_status ?? 'pending')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('visitors.show', $visitor) }}" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-white/10 transition" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('visitors.edit', $visitor) }}" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-white/10 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="sendFollowup({{ $visitor->id }})" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-green-500/20 transition" title="Send Follow-up">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                    <form method="POST" action="{{ route('visitors.destroy', $visitor) }}" class="inline" onsubmit="return confirm('Delete this visitor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-red-500/20 transition" title="Delete">
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

            <!-- Pagination -->
            <div class="p-6 border-t border-white/10">
                {{ $visitors->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-user-friends text-white/20 text-6xl mb-4"></i>
                <p class="text-white/60 text-lg mb-6">No visitors found</p>
                <a href="{{ route('visitors.create') }}" class="glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all inline-block">
                    <i class="fas fa-plus mr-2"></i>Add First Visitor
                </a>
            </div>
        @endif
    </div>

</div>

<script>
let currentView = 'list';

function toggleView(view) {
    currentView = view;
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');

    if (view === 'grid') {
        gridView.classList.remove('hidden');
        gridView.classList.add('grid');
        listView.classList.add('hidden');
        gridBtn.classList.add('bg-white/10');
        listBtn.classList.remove('bg-white/10');
    } else {
        gridView.classList.add('hidden');
        gridView.classList.remove('grid');
        listView.classList.remove('hidden');
        listBtn.classList.add('bg-white/10');
        gridBtn.classList.remove('bg-white/10');
    }
}

function toggleAll(checkbox) {
    document.querySelectorAll('.visitor-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

function showBulkActions() {
    const selected = document.querySelectorAll('.visitor-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select visitors first!');
        return;
    }
    
    const action = confirm(`Perform bulk action on ${selected.length} visitor(s)?\n\nOptions:\n1. Send Follow-up Messages\n2. Update Status\n3. Export Selected\n4. Delete Selected`);
    if (action) {
        alert('Bulk actions feature coming soon!');
    }
}

function exportVisitors() {
    alert('Export feature:\n\n• Export to CSV\n• Export to PDF\n• Export to Excel\n\nComing soon!');
}

function sendFollowup(id) {
    if (confirm('Send follow-up message to this visitor?')) {
        alert('Follow-up sent! (Feature will integrate with SMS/Email system)');
    }
}
</script>
@endsection
