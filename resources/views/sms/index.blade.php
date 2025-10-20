@extends('layouts.app')

@section('content')
<div>
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">SMS Broadcast</h1>
                <p class="mt-2">Send messages to members</p>
            </div>
            <a href="{{ route('sms.create') }}" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                <i class="fas fa-paper-plane mr-2"></i> Compose SMS
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-blue-50 rounded-xl p-6">
            <p class="text-sm text-gray-600">Total SMS</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">0</p>
        </div>
        <div class="bg-green-50 rounded-xl p-6">
            <p class="text-sm text-gray-600">Sent</p>
            <p class="text-3xl font-bold text-green-600 mt-2">0</p>
        </div>
        <div class="bg-yellow-50 rounded-xl p-6">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">0</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">SMS Campaigns</h2>
        <p class="text-gray-600">No campaigns found</p>
    </div>
</div>
@endsection