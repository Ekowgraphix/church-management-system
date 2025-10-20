@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add New Member</h1>
        <p class="mt-2">Register a new church member</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Please fix the following errors:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data" id="memberCreateForm">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                
                <!-- Profile Photo Upload -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-camera mr-2"></i> Profile Photo
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img id="photo-preview" src="https://via.placeholder.com/150" alt="Photo Preview" class="w-32 h-32 rounded-full object-cover border-4 border-green-500 shadow-lg">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" onchange="previewPhoto(event)">
                            <p class="text-sm text-gray-500 mt-2">
                                <i class="fas fa-info-circle"></i> Upload JPG, PNG, or GIF. Max size: 2MB
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Street address">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="City">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                    <select name="marital_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Status</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="divorced">Divorced</option>
                        <option value="widowed">Widowed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                    <input type="text" name="occupation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Occupation">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Baptism Date</label>
                    <input type="date" name="baptism_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Date</label>
                    <input type="date" name="membership_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Additional notes..."></textarea>
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
            
            <div class="mt-6 flex space-x-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-save mr-2"></i> Save Member
                </button>
                <a href="{{ route('members.index') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-all">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection