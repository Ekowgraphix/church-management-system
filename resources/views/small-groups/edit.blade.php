@extends('layouts.app')

@section('page-title', 'Edit Small Group')
@section('page-subtitle', 'Update group information')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('small-groups.update', $smallGroup) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Group Name *</label>
                    <input type="text" name="name" value="{{ old('name', $smallGroup->name) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">{{ old('description', $smallGroup->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Category *</label>
                    <select name="category" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Category</option>
                        <option value="bible_study" {{ $smallGroup->category == 'bible_study' ? 'selected' : '' }}>Bible Study</option>
                        <option value="prayer" {{ $smallGroup->category == 'prayer' ? 'selected' : '' }}>Prayer</option>
                        <option value="youth" {{ $smallGroup->category == 'youth' ? 'selected' : '' }}>Youth</option>
                        <option value="men" {{ $smallGroup->category == 'men' ? 'selected' : '' }}>Men</option>
                        <option value="women" {{ $smallGroup->category == 'women' ? 'selected' : '' }}>Women</option>
                        <option value="couples" {{ $smallGroup->category == 'couples' ? 'selected' : '' }}>Couples</option>
                        <option value="children" {{ $smallGroup->category == 'children' ? 'selected' : '' }}>Children</option>
                        <option value="other" {{ $smallGroup->category == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Leader</label>
                    <select name="leader_id" 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Leader</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $smallGroup->leader_id == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">Meeting Day</label>
                        <select name="meeting_day" 
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                            <option value="">Select Day</option>
                            <option value="monday" {{ $smallGroup->meeting_day == 'monday' ? 'selected' : '' }}>Monday</option>
                            <option value="tuesday" {{ $smallGroup->meeting_day == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="wednesday" {{ $smallGroup->meeting_day == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="thursday" {{ $smallGroup->meeting_day == 'thursday' ? 'selected' : '' }}>Thursday</option>
                            <option value="friday" {{ $smallGroup->meeting_day == 'friday' ? 'selected' : '' }}>Friday</option>
                            <option value="saturday" {{ $smallGroup->meeting_day == 'saturday' ? 'selected' : '' }}>Saturday</option>
                            <option value="sunday" {{ $smallGroup->meeting_day == 'sunday' ? 'selected' : '' }}>Sunday</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">Meeting Time</label>
                        <input type="time" name="meeting_time" value="{{ old('meeting_time', $smallGroup->meeting_time ? $smallGroup->meeting_time->format('H:i') : '') }}" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location', $smallGroup->location) }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Maximum Members</label>
                    <input type="number" name="max_members" value="{{ old('max_members', $smallGroup->max_members) }}" min="1" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="flex items-center space-x-3 text-white cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $smallGroup->is_active ? 'checked' : '' }}
                               class="w-4 h-4 text-green-500 bg-white/10 border-white/20 rounded focus:ring-green-500">
                        <span>Group is Active</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Group
                </button>
                <a href="{{ route('small-groups.show', $smallGroup) }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
