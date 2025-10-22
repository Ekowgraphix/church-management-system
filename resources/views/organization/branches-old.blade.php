@extends('layouts.organization')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">🌍 Branch Management</h1>
                <p class="text-gray-300">Oversee and manage all church branches across locations</p>
            </div>
            <button onclick="addNewBranch()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Add New Branch</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-code-branch text-3xl opacity-80"></i>
            </div>
            <p class="text-sm opacity-90">Total Branches</p>
            <p class="text-4xl font-bold">12</p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-check-circle text-3xl opacity-80"></i>
            </div>
            <p class="text-sm opacity-90">Active Branches</p>
            <p class="text-4xl font-bold">10</p>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-users text-3xl opacity-80"></i>
            </div>
            <p class="text-sm opacity-90">Total Members</p>
            <p class="text-4xl font-bold">1,870</p>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-chart-line text-3xl opacity-80"></i>
            </div>
            <p class="text-sm opacity-90">Growth Rate</p>
            <p class="text-4xl font-bold">+23%</p>
        </div>
    </div>


    <!-- Branches List -->
    <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-white/20">
        
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 p-6 border-b border-white/10">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">📋 All Branches</h2>
                    <p class="text-gray-300 text-sm">{{ count($branches) }} branches registered</p>
                </div>
                <div class="flex gap-3">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search branches..." 
                               class="bg-white/10 border border-white/20 text-white placeholder-gray-400 rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button onclick="exportBranches()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-all flex items-center gap-2 shadow-lg">
                        <i class="fas fa-download"></i>
                        <span>Export</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Branches Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($branches as $branch)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-l-4 {{ $branch->status == 'Active' ? 'border-green-500' : 'border-yellow-500' }}">
                        
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-5 border-b">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $branch->name }}</h3>
                                    <p class="text-sm text-gray-600 flex items-center gap-1">
                                        <i class="fas fa-map-marker-alt text-red-500"></i>
                                        {{ $branch->location }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $branch->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $branch->status }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-5">
                            <div class="space-y-4">
                                <!-- Pastor Info -->
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Pastor</p>
                                        <p class="text-gray-800 font-bold">{{ $branch->pastor }}</p>
                                    </div>
                                </div>
                                
                                <!-- Members Count -->
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-users text-purple-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Members</p>
                                        <p class="text-gray-800 font-bold text-xl">{{ number_format($branch->members) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="bg-gray-50 px-5 py-4 border-t flex gap-3">
                            <button onclick="viewBranch('{{ $branch->name }}')" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-all shadow-md flex items-center justify-center gap-2">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </button>
                            <button onclick="editBranch('{{ $branch->name }}')" 
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-all shadow-md flex items-center justify-center gap-2">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
