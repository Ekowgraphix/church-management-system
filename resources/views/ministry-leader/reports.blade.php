@extends('layouts.ministry-leader')

@section('title', 'Reports')
@section('header', 'Ministry Reports & Analytics')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Monthly Growth Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-green-600 mr-2"></i>
            Monthly Member Growth
        </h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
                <i class="fas fa-chart-bar text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Growth chart visualization</p>
                <p class="text-sm text-gray-400">Chart component to be integrated</p>
            </div>
        </div>
    </div>

    <!-- Event Attendance -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-users text-purple-600 mr-2"></i>
            Event Attendance (Last 3 Months)
        </h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
                <i class="fas fa-chart-pie text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Attendance chart visualization</p>
                <p class="text-sm text-gray-400">Chart component to be integrated</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Members</p>
                <h3 class="text-3xl font-bold mt-2">{{ $monthlyGrowth->sum('count') }}</h3>
                <p class="text-blue-100 text-xs mt-2">This year</p>
            </div>
            <i class="fas fa-users text-4xl opacity-50"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Events Held</p>
                <h3 class="text-3xl font-bold mt-2">{{ $eventAttendance->count() }}</h3>
                <p class="text-green-100 text-xs mt-2">Last 3 months</p>
            </div>
            <i class="fas fa-calendar-check text-4xl opacity-50"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Avg. Attendance</p>
                <h3 class="text-3xl font-bold mt-2">{{ $eventAttendance->count() > 0 ? number_format($eventAttendance->avg(fn($e) => $e->attendees->count()), 0) : 0 }}</h3>
                <p class="text-purple-100 text-xs mt-2">Per event</p>
            </div>
            <i class="fas fa-user-check text-4xl opacity-50"></i>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-download text-blue-600 mr-2"></i>
        Export Reports
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button class="px-4 py-3 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
            <i class="fas fa-file-excel mr-2"></i>Export to Excel
        </button>
        <button class="px-4 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
            <i class="fas fa-file-pdf mr-2"></i>Export to PDF
        </button>
        <button class="px-4 py-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
            <i class="fas fa-print mr-2"></i>Print Report
        </button>
    </div>
</div>
@endsection
