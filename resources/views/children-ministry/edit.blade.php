@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass-card p-8 rounded-2xl">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-white mb-2">
                <i class="fas fa-edit mr-3 text-neon-green"></i>
                Edit Child Record
            </h1>
            <p class="text-gray-300">Update {{ $childrenMinistry->child_name }}'s information</p>
        </div>

        <form method="POST" action="{{ route('children-ministry.update', $childrenMinistry) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Child Name *</label>
                    <input type="text" name="child_name" required class="input-field" value="{{ old('child_name', $childrenMinistry->child_name) }}">
                    @error('child_name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Date of Birth *</label>
                    <input type="date" name="dob" required class="input-field" value="{{ old('dob', $childrenMinistry->dob->format('Y-m-d')) }}">
                    @error('dob')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Gender *</label>
                    <select name="gender" required class="input-field">
                        <option value="">Select gender...</option>
                        <option value="Male" {{ old('gender', $childrenMinistry->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $childrenMinistry->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Parent/Guardian Name *</label>
                    <input type="text" name="parent_name" required class="input-field" value="{{ old('parent_name', $childrenMinistry->parent_name) }}">
                    @error('parent_name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Contact Number *</label>
                    <input type="text" name="contact" required class="input-field" value="{{ old('contact', $childrenMinistry->contact) }}">
                    @error('contact')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="input-field">{{ old('address', $childrenMinistry->address) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Class/Group</label>
                    <select name="class_group" class="input-field">
                        <option value="">Select class...</option>
                        <option value="Nursery" {{ old('class_group', $childrenMinistry->class_group) == 'Nursery' ? 'selected' : '' }}>Nursery</option>
                        <option value="Pre-teens" {{ old('class_group', $childrenMinistry->class_group) == 'Pre-teens' ? 'selected' : '' }}>Pre-teens</option>
                        <option value="Teens" {{ old('class_group', $childrenMinistry->class_group) == 'Teens' ? 'selected' : '' }}>Teens</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Registration Date *</label>
                    <input type="date" name="registered_on" required class="input-field" value="{{ old('registered_on', $childrenMinistry->registered_on->format('Y-m-d')) }}">
                    @error('registered_on')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Allergies/Health Info</label>
                    <textarea name="allergies" rows="2" class="input-field" placeholder="Any allergies or health conditions...">{{ old('allergies', $childrenMinistry->allergies) }}</textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="input-field" placeholder="Additional notes...">{{ old('notes', $childrenMinistry->notes) }}</textarea>
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="fas fa-save mr-2"></i> Update Child
                </button>
                <a href="{{ route('children-ministry.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
