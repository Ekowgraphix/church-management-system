@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Create New Group</h1>
        <p class="mt-2">Set up a new church group or cell</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route('groups.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="e.g., Youth Group, Men's Fellowship">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Brief description of the group...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Day</label>
                    <select name="meeting_day" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select day...</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Time</label>
                    <input type="time" name="meeting_time" value="{{ old('meeting_time') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Meeting location">
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i> Create Group
                    </button>
                    <a href="{{ route('groups.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
