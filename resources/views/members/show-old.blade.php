@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">Member Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('members.edit', $member) }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('members.index') }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-600">Full Name</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->full_name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Member ID</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->member_id ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Phone</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->phone }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Email</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->email ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Status</label>
                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($member->membership_status ?? 'active') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->date_of_birth ? $member->date_of_birth->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection