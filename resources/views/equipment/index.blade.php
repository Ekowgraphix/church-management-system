@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">⚙️ Equipment Management</h1>
                    <p class="text-blue-100 text-lg">Track all church assets, maintenance, and assignments</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('equipment.create') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Add Equipment
                    </a>
                    <a href="{{ route('equipment.analytics') }}" class="bg-blue-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-400 transition-all shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i>Analytics
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Equipment -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Total Equipment</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $equipment->total() }}</h3>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-boxes text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Available -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Available</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $equipment->where('status', 'available')->count() }}</h3>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- In Use -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">In Use</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $equipment->where('status', 'in_use')->count() }}</h3>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-4">
                        <i class="fas fa-user-clock text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Maintenance Due -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Maintenance Due</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $equipment->filter(fn($e) => $e->isMaintenanceDue())->count() }}</h3>
                    </div>
                    <div class="bg-red-100 rounded-full p-4">
                        <i class="fas fa-tools text-red-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Bar -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6 flex flex-wrap gap-3">
            <a href="{{ route('equipment.maintenance') }}" class="flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all shadow hover:shadow-lg">
                <i class="fas fa-wrench mr-2"></i>Maintenance Schedule
            </a>
            <a href="{{ route('equipment.qr.bulk') }}" class="flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-all shadow hover:shadow-lg">
                <i class="fas fa-qrcode mr-2"></i>Generate QR Codes
            </a>
            <a href="{{ route('equipment.export') }}" class="flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all shadow hover:shadow-lg">
                <i class="fas fa-file-export mr-2"></i>Export Inventory
            </a>
            <button onclick="window.print()" class="flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-all shadow hover:shadow-lg">
                <i class="fas fa-print mr-2"></i>Print Report
            </button>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('equipment.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, code, serial..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all shadow-lg">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('equipment.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-all">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Equipment Cards/Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            @if($equipment->count() > 0)
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Image</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Equipment</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Category</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Location</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Condition</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Maintenance</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($equipment as $item)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <!-- Image -->
                                    <td class="px-6 py-4">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded-lg shadow">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Equipment Info -->
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->equipment_code }}</p>
                                            @if($item->brand)
                                                <p class="text-xs text-gray-400">{{ $item->brand }} {{ $item->model }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Category -->
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                            {{ $item->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    
                                    <!-- Location -->
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $item->location ?? 'Not Set' }}</span>
                                    </td>
                                    
                                    <!-- Condition -->
                                    <td class="px-6 py-4">
                                        @php
                                            $conditionColors = [
                                                'excellent' => 'bg-green-100 text-green-800',
                                                'good' => 'bg-blue-100 text-blue-800',
                                                'fair' => 'bg-yellow-100 text-yellow-800',
                                                'poor' => 'bg-orange-100 text-orange-800',
                                                'damaged' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 {{ $conditionColors[$item->condition] ?? 'bg-gray-100 text-gray-800' }} rounded-full text-sm font-medium capitalize">
                                            {{ $item->condition }}
                                        </span>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'available' => 'bg-green-100 text-green-800',
                                                'in_use' => 'bg-yellow-100 text-yellow-800',
                                                'maintenance' => 'bg-orange-100 text-orange-800',
                                                'retired' => 'bg-gray-100 text-gray-800',
                                            ];
                                            $statusLabels = [
                                                'available' => 'Available',
                                                'in_use' => 'In Use',
                                                'maintenance' => 'Maintenance',
                                                'retired' => 'Retired',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }} rounded-full text-sm font-medium">
                                            {{ $statusLabels[$item->status] ?? $item->status }}
                                        </span>
                                    </td>
                                    
                                    <!-- Maintenance Status -->
                                    <td class="px-6 py-4">
                                        @php $maintenanceStatus = $item->maintenanceStatus; @endphp
                                        @if($maintenanceStatus['status'] === 'overdue')
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                                <i class="fas fa-exclamation-circle mr-1"></i>Overdue
                                            </span>
                                        @elseif($maintenanceStatus['status'] === 'due_soon')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                                <i class="fas fa-clock mr-1"></i>Due Soon
                                            </span>
                                        @elseif($maintenanceStatus['status'] === 'scheduled')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                <i class="fas fa-check mr-1"></i>Scheduled
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                                                Not Set
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('equipment.show', $item) }}" class="text-blue-600 hover:text-blue-800" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('equipment.edit', $item) }}" class="text-green-600 hover:text-green-800" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('equipment.qr', $item) }}" class="text-purple-600 hover:text-purple-800" title="QR Code">
                                                <i class="fas fa-qrcode"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden p-4 space-y-4">
                    @foreach($equipment as $item)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow hover:shadow-lg transition-all">
                            <div class="flex items-start space-x-4">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">{{ $item->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item->equipment_code }}</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $item->category->name ?? 'N/A' }}</span>
                                        @php
                                            $statusColors = [
                                                'available' => 'bg-green-100 text-green-800',
                                                'in_use' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }} rounded text-xs capitalize">
                                            {{ str_replace('_', ' ', $item->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-3 flex space-x-3">
                                        <a href="{{ route('equipment.show', $item) }}" class="text-blue-600 text-sm">View</a>
                                        <a href="{{ route('equipment.edit', $item) }}" class="text-green-600 text-sm">Edit</a>
                                        <a href="{{ route('equipment.qr', $item) }}" class="text-purple-600 text-sm">QR</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $equipment->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-boxes text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Equipment Found</h3>
                    <p class="text-gray-500 mb-6">Start tracking your church equipment and assets</p>
                    <a href="{{ route('equipment.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Add Your First Equipment
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background: white !important;
        }
    }
</style>
@endsection