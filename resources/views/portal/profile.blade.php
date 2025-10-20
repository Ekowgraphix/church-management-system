@extends('layouts.member-portal')

@section('page-title', 'My Profile')
@section('page-subtitle', 'Update your personal information')

@section('content')
<div class="max-w-2xl">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-xl text-green-300">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-xl text-red-300">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-xl text-red-300">
            <i class="fas fa-exclamation-circle"></i> 
            <ul class="list-disc ml-6 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('portal.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div class="text-center mb-6">
                    <div class="inline-block relative">
                        <img id="photoPreview" 
                             src="{{ $member->photo ? asset('storage/' . $member->photo) : '' }}" 
                             alt="{{ $member->full_name }}" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-green-500 {{ $member->photo ? '' : 'hidden' }}">
                        
                        <div id="photoPlaceholder" class="w-32 h-32 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center border-4 border-green-500 {{ $member->photo ? 'hidden' : '' }}">
                            <span class="text-white text-4xl font-bold">{{ strtoupper(substr($member->first_name, 0, 1)) }}</span>
                        </div>
                        
                        <label for="photo" class="absolute bottom-0 right-0 bg-green-500 rounded-full p-2 cursor-pointer hover:bg-green-600 transition">
                            <i class="fas fa-camera text-white"></i>
                            <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
                        </label>
                    </div>
                    <p class="text-gray-400 text-sm mt-2">Click camera icon to change photo</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Full Name</label>
                    <input type="text" value="{{ $member->full_name }}" disabled 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-gray-400">
                    <p class="text-gray-500 text-xs mt-1">Contact admin to change your name</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Phone *</label>
                    <input type="tel" name="phone" value="{{ old('phone', $member->phone) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Address</label>
                    <textarea name="address" rows="2" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">{{ old('address', $member->address) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">City</label>
                        <input type="text" name="city" value="{{ old('city', $member->city) }}" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-green-300 mb-2">State</label>
                        <input type="text" name="state" value="{{ old('state', $member->state) }}" 
                               class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Profile
                </button>
                <a href="{{ route('portal.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Photo preview
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');
        const photoPlaceholder = document.getElementById('photoPlaceholder');
        
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    return;
                }
                
                // Validate file size (max 2MB)
                if (file.size > 2048 * 1024) {
                    alert('Image size must be less than 2MB');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreview.classList.remove('hidden');
                    photoPlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
@endsection
