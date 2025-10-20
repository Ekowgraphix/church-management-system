@extends('layouts.app')

@section('content')
<div>
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-users mr-3"></i>
                    Membership Report
                </h1>
                <p class="mt-2">Comprehensive membership statistics and analytics</p>
            </div>
            <a href="{{ route('reports.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Members</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $data['total_members'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Members</p>
                    <p class="text-3xl font-bold text-green-600">{{ $data['active_members'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-user-check text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Inactive Members</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $data['inactive_members'] }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-user-times text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">New Members</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $data['new_members'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-user-plus text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <!-- Gender Distribution -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Gender Distribution</h3>
            <div class="space-y-3">
                @foreach($data['gender_distribution'] as $gender)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst($gender->gender ?? 'Not Specified') }}</span>
                            <span class="text-sm font-medium text-gray-700">{{ $gender->count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $data['total_members'] > 0 ? ($gender->count / $data['total_members']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Age Distribution</h3>
            <div class="space-y-3">
                @foreach($data['age_distribution'] as $age)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $age->age_group }}</span>
                            <span class="text-sm font-medium text-gray-700">{{ $age->count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $data['total_members'] > 0 ? ($age->count / $data['total_members']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Marital Status -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Marital Status</h3>
            <div class="space-y-3">
                @foreach($data['marital_status'] as $status)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst($status->marital_status ?? 'Not Specified') }}</span>
                            <span class="text-sm font-medium text-gray-700">{{ $status->count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $data['total_members'] > 0 ? ($status->count / $data['total_members']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Total Members</span>
                    <span class="text-sm font-bold text-blue-600">{{ $data['total_members'] }}</span>
                </div>
                <div class="flex justify-between p-3 bg-green-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Active Rate</span>
                    <span class="text-sm font-bold text-green-600">{{ $data['total_members'] > 0 ? round(($data['active_members'] / $data['total_members']) * 100) : 0 }}%</span>
                </div>
                <div class="flex justify-between p-3 bg-purple-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">New This Period</span>
                    <span class="text-sm font-bold text-purple-600">{{ $data['new_members'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection