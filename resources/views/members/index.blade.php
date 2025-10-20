@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-3"></i>
                <div>
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Members Directory</h1>
                <p class="mt-2">Manage and view all church members in one place. Search, filter, and perform actions on member records.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('members.create') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 flex items-center">
                    <i class="fas fa-user-plus mr-2"></i> Add New Member
                </a>
                <a href="{{ route('small-groups.index') }}" class="bg-green-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-800 flex items-center">
                    <i class="fas fa-users-cog mr-2"></i> Groups Management
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Members</p>
                    <p class="text-3xl font-bold mt-2">{{ $members->total() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Active</p>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Member::where('membership_status', 'active')->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">New (30d)</p>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Member::where('created_at', '>=', now()->subDays(30))->count() }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('members.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" placeholder="Search members..." 
                   value="{{ request('search') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
            </select>
            
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-medium">
                Search
            </button>
        </form>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-users text-green-600 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Members List</h3>
            </div>
            <span class="text-sm text-gray-600">
                <i class="fas fa-list mr-1"></i> {{ $members->total() }} members
            </span>
        </div>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($members as $member)
                    <tr class="hover:bg-green-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($member->profile_photo || $member->photo)
                                    <img src="{{ asset('storage/' . ($member->profile_photo ?? $member->photo)) }}" 
                                         alt="{{ $member->full_name }}" 
                                         class="w-10 h-10 rounded-full object-cover mr-3 border-2 border-green-500"
                                         onerror="this.onerror=null; this.src='{{ asset('storage/' . ($member->profile_photo ?? $member->photo)) }}'; this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($member->full_name) }}&size=40&background=22c55e&color=fff'">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                        {{ strtoupper(substr($member->first_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $member->full_name }}</div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-id-badge mr-1"></i>{{ $member->member_id ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-phone text-green-600 mr-1"></i> {{ $member->phone }}
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-envelope text-blue-600 mr-1"></i> {{ $member->email ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="text-gray-600">None</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-circle text-gray-400 mr-1" style="font-size: 6px;"></i>
                                Member
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $member->membership_status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>
                                {{ ucfirst($member->membership_status ?? 'active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('members.show', $member) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-lg transition-all" title="View Profile">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-lg transition-all" title="Edit Member">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this member? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg transition-all" title="Delete Member">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No members found</p>
                            <a href="{{ route('members.create') }}" class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                                Add Your First Member
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($members->hasPages())
    <div class="bg-white rounded-xl shadow-lg p-4">
        {{ $members->links() }}
    </div>
    @endif
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}
</style>
@endsection
