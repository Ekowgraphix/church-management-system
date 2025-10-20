@extends('layouts.app')

@section('content')
<div>
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Reports</h1>
        <p class="mt-2">Generate and view church reports</p>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <a href="{{ route('reports.membership') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <i class="fas fa-users text-4xl text-blue-600 mb-4"></i>
            <h3 class="text-xl font-bold">Membership Report</h3>
        </a>
        <a href="{{ route('reports.financial') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <i class="fas fa-chart-line text-4xl text-green-600 mb-4"></i>
            <h3 class="text-xl font-bold">Financial Report</h3>
        </a>
        <a href="{{ route('reports.attendance') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <i class="fas fa-calendar-check text-4xl text-purple-600 mb-4"></i>
            <h3 class="text-xl font-bold">Attendance Report</h3>
        </a>
    </div>
</div>
@endsection