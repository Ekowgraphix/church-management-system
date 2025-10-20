@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add Follow-up</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route('followups.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium">
                    Save Follow-up
                </button>
            </div>
        </form>
    </div>
</div>
@endsection