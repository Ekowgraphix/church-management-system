@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-orange-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-600 via-red-600 to-pink-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">🔧 Maintenance Schedule</h1>
                    <p class="text-orange-100 text-lg">Track and manage equipment maintenance</p>
                </div>
                <a href="{{ route('equipment.index') }}" class="bg-white text-orange-600 px-6 py-3 rounded-xl font-semibold hover:bg-orange-50 transition-all shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if($maintenanceOverdue->count() > 0)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg mb-6 shadow-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-2xl mr-4"></i>
                    <div>
                        <h3 class="font-bold text-lg">{{ $maintenanceOverdue->count() }} Equipment with Overdue Maintenance!</h3>
                        <p>Immediate attention required to prevent equipment failure.</p>
                    </div>
                </div>
            </div>
        @endif

        @if($maintenanceDue->count() > 0)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded-lg mb-6 shadow-lg">
                <div class="flex items-center">
                    <i class="fas fa-clock text-2xl mr-4"></i>
                    <div>
                        <h3 class="font-bold text-lg">{{ $maintenanceDue->count() }} Equipment Due for Maintenance Soon</h3>
                        <p>Schedule maintenance within the next 7 days.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Overdue Maintenance -->
        @if($maintenanceOverdue->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="bg-red-600 text-white px-6 py-4">
                    <h2 class="text-2xl font-bold">⚠️ Overdue Maintenance ({{ $maintenanceOverdue->count() }})</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($maintenanceOverdue as $equipment)
                            <div class="border-2 border-red-300 rounded-lg p-4 bg-red-50 hover:shadow-lg transition-all">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $equipment->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $equipment->equipment_code }}</p>
                                    </div>
                                    <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold">OVERDUE</span>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-tag w-5"></i>
                                        <span>{{ $equipment->category->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-map-marker-alt w-5"></i>
                                        <span>{{ $equipment->location ?? 'Not Set' }}</span>
                                    </div>
                                    <div class="flex items-center text-red-700 font-semibold">
                                        <i class="fas fa-calendar-times w-5"></i>
                                        <span>Due: {{ $equipment->next_maintenance_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-red-700 font-semibold">
                                        <i class="fas fa-clock w-5"></i>
                                        <span>{{ $equipment->next_maintenance_date->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('equipment.show', $equipment) }}" class="flex-1 bg-red-600 text-white text-center py-2 rounded-lg hover:bg-red-700 transition-all text-sm font-semibold">
                                        <i class="fas fa-wrench mr-1"></i>Schedule Now
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Due Soon -->
        @if($maintenanceDue->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="bg-yellow-600 text-white px-6 py-4">
                    <h2 class="text-2xl font-bold">📅 Maintenance Due Soon ({{ $maintenanceDue->count() }})</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($maintenanceDue as $equipment)
                            <div class="border-2 border-yellow-300 rounded-lg p-4 bg-yellow-50 hover:shadow-lg transition-all">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $equipment->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $equipment->equipment_code }}</p>
                                    </div>
                                    <span class="bg-yellow-600 text-white px-2 py-1 rounded text-xs font-semibold">DUE SOON</span>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-tag w-5"></i>
                                        <span>{{ $equipment->category->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-map-marker-alt w-5"></i>
                                        <span>{{ $equipment->location ?? 'Not Set' }}</span>
                                    </div>
                                    <div class="flex items-center text-yellow-700 font-semibold">
                                        <i class="fas fa-calendar w-5"></i>
                                        <span>Due: {{ $equipment->next_maintenance_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-yellow-700 font-semibold">
                                        <i class="fas fa-clock w-5"></i>
                                        <span>{{ $equipment->next_maintenance_date->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('equipment.show', $equipment) }}" class="flex-1 bg-yellow-600 text-white text-center py-2 rounded-lg hover:bg-yellow-700 transition-all text-sm font-semibold">
                                        <i class="fas fa-tools mr-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- All Maintenance Records -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4">
                <h2 class="text-2xl font-bold">📋 Maintenance History</h2>
            </div>
            <div class="overflow-x-auto">
                @if($allMaintenance->count() > 0)
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Equipment</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Description</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Cost</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Performed By</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Next Service</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($allMaintenance as $maintenance)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-gray-800 font-medium">{{ $maintenance->maintenance_date->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $maintenance->equipment->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $maintenance->equipment->category->name ?? 'N/A' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $typeColors = [
                                                'routine' => 'bg-blue-100 text-blue-800',
                                                'repair' => 'bg-red-100 text-red-800',
                                                'inspection' => 'bg-green-100 text-green-800',
                                                'upgrade' => 'bg-purple-100 text-purple-800',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 {{ $typeColors[$maintenance->maintenance_type] ?? 'bg-gray-100 text-gray-800' }} rounded-full text-sm font-medium capitalize">
                                            {{ $maintenance->maintenance_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-gray-700 line-clamp-2">{{ $maintenance->description }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($maintenance->cost)
                                            <span class="text-gray-800 font-semibold">GH₵{{ number_format($maintenance->cost, 2) }}</span>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700">{{ $maintenance->performed_by ?? $maintenance->vendor ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($maintenance->next_maintenance_date)
                                            <span class="text-gray-800 font-medium">{{ $maintenance->next_maintenance_date->format('M d, Y') }}</span>
                                        @else
                                            <span class="text-gray-400">Not Set</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $allMaintenance->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-tools text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No maintenance records found</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
