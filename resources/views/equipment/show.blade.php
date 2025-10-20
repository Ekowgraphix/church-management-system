@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-start space-x-6">
                    @if($equipment->image)
                        <img src="{{ asset('storage/' . $equipment->image) }}" alt="{{ $equipment->name }}" class="w-24 h-24 object-cover rounded-xl shadow-lg">
                    @else
                        <div class="w-24 h-24 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-box text-white text-3xl"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-4xl font-bold mb-2">{{ $equipment->name }}</h1>
                        <p class="text-blue-100 text-lg">{{ $equipment->equipment_code }}</p>
                        <div class="flex items-center space-x-3 mt-3">
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-sm font-medium">
                                {{ $equipment->category->name ?? 'N/A' }}
                            </span>
                            @php
                                $statusColors = [
                                    'available' => 'bg-green-500/90',
                                    'in_use' => 'bg-yellow-500/90',
                                    'maintenance' => 'bg-orange-500/90',
                                    'retired' => 'bg-gray-500/90',
                                ];
                                $statusLabels = [
                                    'available' => 'Available',
                                    'in_use' => 'In Use',
                                    'maintenance' => 'Maintenance',
                                    'retired' => 'Retired',
                                ];
                            @endphp
                            <span class="px-4 py-2 {{ $statusColors[$equipment->status] ?? 'bg-gray-500/90' }} backdrop-blur-sm rounded-lg text-sm font-medium">
                                {{ $statusLabels[$equipment->status] ?? $equipment->status }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('equipment.index') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                    <a href="{{ route('equipment.edit', $equipment) }}" class="bg-green-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-600 transition-all shadow-lg text-center">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                        Basic Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Equipment Code</p>
                            <p class="font-semibold text-gray-800">{{ $equipment->equipment_code }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Category</p>
                            <p class="font-semibold text-gray-800">{{ $equipment->category->name ?? 'N/A' }}</p>
                        </div>
                        @if($equipment->brand)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-1">Brand</p>
                                <p class="font-semibold text-gray-800">{{ $equipment->brand }}</p>
                            </div>
                        @endif
                        @if($equipment->model)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-1">Model</p>
                                <p class="font-semibold text-gray-800">{{ $equipment->model }}</p>
                            </div>
                        @endif
                        @if($equipment->serial_number)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-1">Serial Number</p>
                                <p class="font-semibold text-gray-800">{{ $equipment->serial_number }}</p>
                            </div>
                        @endif
                        @if($equipment->location)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-1">Location</p>
                                <p class="font-semibold text-gray-800">{{ $equipment->location }}</p>
                            </div>
                        @endif
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Condition</p>
                            @php
                                $conditionColors = [
                                    'excellent' => 'bg-green-100 text-green-800',
                                    'good' => 'bg-blue-100 text-blue-800',
                                    'fair' => 'bg-yellow-100 text-yellow-800',
                                    'poor' => 'bg-orange-100 text-orange-800',
                                    'damaged' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 {{ $conditionColors[$equipment->condition] ?? 'bg-gray-100 text-gray-800' }} rounded-full text-sm font-medium capitalize">
                                {{ $equipment->condition }}
                            </span>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span class="px-3 py-1 {{ $statusColors[$equipment->status] ?? 'bg-gray-100 text-gray-800' }} text-white rounded-full text-sm font-medium">
                                {{ $statusLabels[$equipment->status] ?? $equipment->status }}
                            </span>
                        </div>
                    </div>

                    @if($equipment->description)
                        <div class="mt-6 bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-2 font-medium">Description</p>
                            <p class="text-gray-800">{{ $equipment->description }}</p>
                        </div>
                    @endif

                    @if($equipment->notes)
                        <div class="mt-4 bg-yellow-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-2 font-medium">Notes</p>
                            <p class="text-gray-800">{{ $equipment->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Financial Information & Depreciation -->
                @if($equipment->purchase_date || $equipment->purchase_price)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-dollar-sign text-green-600 mr-3"></i>
                            Financial Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @if($equipment->purchase_date)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 mb-1">Purchase Date</p>
                                    <p class="font-semibold text-gray-800">{{ $equipment->purchase_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                            @if($equipment->purchase_price)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 mb-1">Purchase Price</p>
                                    <p class="font-semibold text-gray-800 text-xl">GH₵{{ number_format($equipment->purchase_price, 2) }}</p>
                                </div>
                            @endif
                            @if($equipment->vendor)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 mb-1">Vendor</p>
                                    <p class="font-semibold text-gray-800">{{ $equipment->vendor }}</p>
                                </div>
                            @endif
                            @if($equipment->warranty_expiry)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 mb-1">Warranty Expiry</p>
                                    <p class="font-semibold text-gray-800">{{ $equipment->warranty_expiry->format('M d, Y') }}</p>
                                    @if($equipment->warranty_expiry->isFuture())
                                        <p class="text-xs text-green-600 mt-1">{{ $equipment->warranty_expiry->diffForHumans() }}</p>
                                    @else
                                        <p class="text-xs text-red-600 mt-1">Expired</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Depreciation Calculator -->
                        @php $depreciation = $equipment->calculateDepreciation(); @endphp
                        @if($depreciation)
                            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg p-6 border-2 border-green-200">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                                    Depreciation Analysis
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 mb-1">Original Value</p>
                                        <p class="text-lg font-bold text-gray-800">GH₵{{ number_format($depreciation['original_value'], 0) }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 mb-1">Current Value</p>
                                        <p class="text-lg font-bold text-green-600">GH₵{{ number_format($depreciation['current_value'], 0) }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 mb-1">Depreciation</p>
                                        <p class="text-lg font-bold text-red-600">GH₵{{ number_format($depreciation['total_depreciation'], 0) }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 mb-1">Rate</p>
                                        <p class="text-lg font-bold text-orange-600">{{ number_format($depreciation['depreciation_rate'], 1) }}%</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-4 text-center">
                                    Equipment is {{ $depreciation['years_old'] }} years old • Annual depreciation: GH₵{{ number_format($depreciation['annual_depreciation'], 2) }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Maintenance History -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-tools text-orange-600 mr-3"></i>
                            Maintenance History
                        </span>
                        <button onclick="document.getElementById('maintenanceModal').classList.remove('hidden')" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-all text-sm">
                            <i class="fas fa-plus mr-2"></i>Add Maintenance
                        </button>
                    </h2>

                    @php $maintenanceStatus = $equipment->maintenanceStatus; @endphp
                    <div class="mb-6">
                        @if($maintenanceStatus['status'] === 'overdue')
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                                <p class="font-bold">⚠️ Maintenance Overdue!</p>
                                <p class="text-sm">Due: {{ $equipment->next_maintenance_date->format('M d, Y') }} ({{ $equipment->next_maintenance_date->diffForHumans() }})</p>
                            </div>
                        @elseif($maintenanceStatus['status'] === 'due_soon')
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg">
                                <p class="font-bold">📅 Maintenance Due Soon</p>
                                <p class="text-sm">Scheduled: {{ $equipment->next_maintenance_date->format('M d, Y') }} ({{ $equipment->next_maintenance_date->diffForHumans() }})</p>
                            </div>
                        @elseif($maintenanceStatus['status'] === 'scheduled')
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                                <p class="font-bold">✅ Maintenance Scheduled</p>
                                <p class="text-sm">Next service: {{ $equipment->next_maintenance_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($equipment->maintenanceRecords->count() > 0)
                        <div class="space-y-4">
                            @foreach($equipment->maintenanceRecords->take(5) as $maintenance)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                @php
                                                    $typeColors = [
                                                        'routine' => 'bg-blue-100 text-blue-800',
                                                        'repair' => 'bg-red-100 text-red-800',
                                                        'inspection' => 'bg-green-100 text-green-800',
                                                        'upgrade' => 'bg-purple-100 text-purple-800',
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 {{ $typeColors[$maintenance->maintenance_type] ?? 'bg-gray-100 text-gray-800' }} rounded-full text-xs font-semibold capitalize">
                                                    {{ $maintenance->maintenance_type }}
                                                </span>
                                                <span class="text-sm text-gray-600">{{ $maintenance->maintenance_date->format('M d, Y') }}</span>
                                            </div>
                                            <p class="text-gray-800 font-medium mb-1">{{ $maintenance->description }}</p>
                                            @if($maintenance->performed_by)
                                                <p class="text-sm text-gray-600">By: {{ $maintenance->performed_by }}</p>
                                            @endif
                                        </div>
                                        @if($maintenance->cost)
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-gray-800">GH₵{{ number_format($maintenance->cost, 2) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-wrench text-4xl mb-3 text-gray-300"></i>
                            <p>No maintenance records yet</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Column - Quick Actions & QR -->
            <div class="space-y-6">
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('equipment.qr', $equipment) }}" class="w-full flex items-center justify-center bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-3 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-qrcode mr-2"></i>
                            Generate QR Code
                        </a>
                        <a href="{{ route('equipment.edit', $equipment) }}" class="w-full flex items-center justify-center bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-3 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Equipment
                        </a>
                        <button onclick="window.print()" class="w-full flex items-center justify-center bg-gradient-to-r from-gray-600 to-gray-700 text-white px-4 py-3 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-print mr-2"></i>
                            Print Details
                        </button>
                        <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this equipment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-lg hover:shadow-lg transition-all">
                                <i class="fas fa-trash mr-2"></i>
                                Delete Equipment
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Assignment Status -->
                @if($equipment->assignments->where('status', 'active')->count() > 0)
                    <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">📋 Current Assignment</h3>
                        @foreach($equipment->assignments->where('status', 'active') as $assignment)
                            <div class="bg-white rounded-lg p-4 mb-3">
                                <p class="text-sm text-gray-600 mb-1">Assigned To</p>
                                <p class="font-semibold text-gray-800">{{ $assignment->assignedTo->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600 mt-2">Since: {{ $assignment->assigned_date->format('M d, Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

<!-- Add Maintenance Modal -->
<div id="maintenanceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Add Maintenance Record</h2>
            <button onclick="document.getElementById('maintenanceModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form action="{{ route('equipment.maintenance.add', $equipment) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Date*</label>
                        <input type="date" name="maintenance_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type*</label>
                        <select name="maintenance_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="routine">Routine</option>
                            <option value="repair">Repair</option>
                            <option value="inspection">Inspection</option>
                            <option value="upgrade">Upgrade</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description*</label>
                    <textarea name="description" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cost</label>
                        <input type="number" step="0.01" name="cost" placeholder="0.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Next Maintenance Date</label>
                        <input type="date" name="next_maintenance_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Performed By</label>
                        <input type="text" name="performed_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                        <input type="text" name="vendor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>
            <div class="mt-6 flex space-x-3">
                <button type="submit" class="flex-1 bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-all font-semibold">
                    <i class="fas fa-save mr-2"></i>Save Maintenance Record
                </button>
                <button type="button" onclick="document.getElementById('maintenanceModal').classList.add('hidden')" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
