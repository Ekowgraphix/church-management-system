@extends('layouts.app')

@section('page-title', 'Edit Member')
@section('page-subtitle', 'Update member information')

@section('content')
<div class="max-w-4xl">
    <div class="glass-card p-8 rounded-2xl mb-6">
        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-300 px-6 py-4 rounded-xl mb-6">
                <p class="font-bold mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('members.update', $member) }}" enctype="multipart/form-data" id="memberEditForm">
            @csrf
            @method('PUT')
            
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/50 text-green-300 px-6 py-4 rounded-xl mb-6">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Middle Name -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Middle Name</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $member->middle_name) }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Phone *</label>
                    <input type="tel" name="phone" value="{{ old('phone', $member->phone) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Date of Birth -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500 transition-all">
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Gender</label>
                    <select name="gender" class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500 transition-all">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $member->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Membership Status -->
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Membership Status *</label>
                    <select name="membership_status" required class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500 transition-all">
                        <option value="active" {{ old('membership_status', $member->membership_status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('membership_status', $member->membership_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="transferred" {{ old('membership_status', $member->membership_status) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                        <option value="deceased" {{ old('membership_status', $member->membership_status) == 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                </div>
                
                <!-- Profile Photo Upload -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">
                        <i class="fas fa-camera mr-2"></i> Profile Photo
                    </label>
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            @if($member->profile_photo)
                                <img id="photo-preview" src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->full_name }}" class="w-32 h-32 rounded-full object-cover border-4 border-green-500 shadow-lg">
                            @else
                                <img id="photo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($member->full_name) }}&size=150&background=22c55e&color=fff&bold=true" alt="{{ $member->full_name }}" class="w-32 h-32 rounded-full object-cover border-4 border-green-500 shadow-lg">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" 
                                   class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-500 file:text-white hover:file:bg-green-600 focus:outline-none focus:border-green-500 transition-all" 
                                   onchange="previewPhoto(event)">
                            <p class="text-sm text-green-200 mt-2">
                                <i class="fas fa-info-circle"></i> Upload JPG, PNG, or GIF. Max size: 2MB
                            </p>
                            @if($member->profile_photo)
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-check-circle text-green-500"></i> Current photo will be replaced if you upload a new one
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">{{ old('address', $member->address) }}</textarea>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">{{ old('notes', $member->notes) }}</textarea>
                </div>
            </div>
            
            <script>
                function previewPhoto(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('photo-preview').src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                }
            </script>

            <!-- Action Buttons -->
            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Member
                </button>
                <a href="{{ route('members.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection