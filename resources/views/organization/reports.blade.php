@extends('layouts.organization')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📊 Reports & Analytics</h1>
                <p class="text-gray-600">Generate and view organization-wide reports</p>
            </div>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Generate Custom Report
            </button>
        </div>
    </div>

    <!-- Report Types -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <button class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-left">
            <i class="fas fa-users text-blue-600 text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800">Attendance Report</h3>
            <p class="text-sm text-gray-600">View attendance trends</p>
        </button>
        <button class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-left">
            <i class="fas fa-hand-holding-usd text-green-600 text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800">Financial Report</h3>
            <p class="text-sm text-gray-600">Income & expenses</p>
        </button>
        <button class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-left">
            <i class="fas fa-church text-purple-600 text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800">Ministry Performance</h3>
            <p class="text-sm text-gray-600">Ministry analytics</p>
        </button>
        <button class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-left">
            <i class="fas fa-hands-helping text-orange-600 text-3xl mb-3"></i>
            <h3 class="font-bold text-gray-800">Volunteer Report</h3>
            <p class="text-sm text-gray-600">Participation data</p>
        </button>
    </div>

    <!-- Custom Report Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">📋 Custom Report Filters</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Date Range</label>
                <select class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last quarter</option>
                    <option>Last year</option>
                    <option>Custom</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Branch</label>
                <select class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option>All Branches</option>
                    <option>Faith Temple</option>
                    <option>Grace Centre</option>
                    <option>Hope Sanctuary</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Department</label>
                <select class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option>All Departments</option>
                    <option>Youth</option>
                    <option>Women</option>
                    <option>Children</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Export Format</label>
                <select class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option>PDF</option>
                    <option>Excel</option>
                    <option>CSV</option>
                </select>
            </div>
        </div>
        <button class="mt-4 bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700">
            <i class="fas fa-file-export mr-2"></i>Generate Report
        </button>
    </div>

    <!-- AI Summary -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center space-x-3 mb-4">
            <i class="fas fa-brain text-3xl"></i>
            <h3 class="text-2xl font-bold">AI Summary</h3>
        </div>
        <p class="text-lg">"In Q3, the Youth Ministry saw a 17% increase in engagement across all branches. Faith Temple leads with 23% member growth. Consider replicating their strategies in other locations."</p>
    </div>
</div>
@endsection
