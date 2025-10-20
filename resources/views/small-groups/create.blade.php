@extends('layouts.app')

@section('page-title', 'Create Small Group')
@section('page-subtitle', 'Add a new small group')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('small-groups.store') }}">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Group Name *</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Category *</label>
                    <select name="category" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Category</option>
                        <option value="bible_study">Bible Study</option>
                        <option value="prayer">Prayer</option>
                        <option value="youth">Youth</option>
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                        <option value="couples">Couples</option>
                        <option value="children">Children</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Leader</label>
                    <select name="leader_id" 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Leader</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">Meeting Day</label>
                        <select name="meeting_day" 
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                            <option value="">Select Day</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">Meeting Time</label>
                        <input type="time" name="meeting_time" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Location</label>
                    <input type="text" name="location" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Maximum Members</label>
                    <input type="number" name="max_members" min="1" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Create Group
                </button>
                <a href="{{ route('small-groups.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
