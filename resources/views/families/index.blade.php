@extends('layouts.app')

@section('page-title', 'Family Management')
@section('page-subtitle', 'Manage family units and relationships')

@section('content')
<div>
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search families..." 
                       class="pl-10 pr-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
        <a href="{{ route('families.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Family
        </a>
    </div>

    <!-- Families Grid -->
    @if($families->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($families as $family)
                <div class="glass-card p-6 rounded-2xl hover:scale-105 transition-transform">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $family->family_name }}</h3>
                            @if($family->headOfFamily)
                                <p class="text-green-300 text-sm">
                                    <i class="fas fa-user-tie mr-1"></i>
                                    {{ $family->headOfFamily->full_name }}
                                </p>
                            @endif
                        </div>
                        <div class="w-12 h-12 gradient-pink rounded-xl flex items-center justify-center">
                            <i class="fas fa-home text-white text-xl"></i>
                        </div>
                    </div>

                    @if($family->address)
                        <div class="mb-4">
                            <p class="text-gray-400 text-sm">
                                <i class="fas fa-map-marker-alt text-green-400 mr-2"></i>
                                {{ $family->address }}
                                @if($family->city), {{ $family->city }}@endif
                            </p>
                        </div>
                    @endif

                    @if($family->phone)
                        <div class="mb-4">
                            <p class="text-gray-400 text-sm">
                                <i class="fas fa-phone text-green-400 mr-2"></i>
                                {{ $family->phone }}
                            </p>
                        </div>
                    @endif

                    <div class="bg-white/5 p-3 rounded-xl mb-4">
                        <p class="text-gray-400 text-xs mb-1">Family Members</p>
                        <p class="text-white font-bold text-2xl">{{ $family->member_count }}</p>
                    </div>

                    <a href="{{ route('families.show', $family) }}" class="btn btn-secondary btn-sm w-full">
                        <i class="fas fa-eye"></i> View Family
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $families->links() }}
        </div>
    @else
        <div class="glass-card p-16 rounded-2xl text-center">
            <i class="fas fa-home text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-lg mb-2">No families found</p>
            <p class="text-gray-500 text-sm mb-6">Create your first family unit</p>
            <a href="{{ route('families.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create First Family
            </a>
        </div>
    @endif
</div>
@endsection
