@extends('layouts.organization')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🌍 Branch Management</h1>
                <p class="text-gray-600">Manage all church branches and locations</p>
            </div>
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>Add New Branch
            </button>
        </div>
    </div>

    <!-- Map View -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">📍 Branch Locations Map</h3>
        <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
            <p class="text-gray-500">Interactive map view (Google Maps / Leaflet integration)</p>
        </div>
    </div>

    <!-- Branches Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">All Branches</h3>
                <div class="flex space-x-3">
                    <input type="text" placeholder="Search branches..." class="border border-gray-300 rounded-lg px-4 py-2 w-64">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pastor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Members</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($branches as $branch)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $branch->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $branch->location }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $branch->pastor }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800">{{ $branch->members }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $branch->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $branch->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="text-green-600 hover:text-green-800">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
