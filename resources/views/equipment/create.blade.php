@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">➕ Add New Equipment</h1>
                    <p class="text-blue-100 text-lg">Add equipment to your church inventory</p>
                </div>
                <a href="{{ route('equipment.index') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <form method="POST" action="{{ route('equipment.store') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Image Upload Section -->
                <div class="mb-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-camera text-blue-600 mr-3"></i>
                        Equipment Photo
                    </h3>
                    <div class="flex flex-col items-center">
                        <!-- Image Preview -->
                        <div id="imagePreview" class="hidden mb-4">
                            <img id="previewImg" src="" alt="Preview" class="w-64 h-64 object-cover rounded-xl shadow-lg border-4 border-white">
                        </div>
                        
                        <!-- Upload Button -->
                        <div class="w-full">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-48 border-2 border-blue-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-blue-50 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-blue-500 text-5xl mb-3"></i>
                                    <p class="mb-2 text-sm text-gray-700 font-semibold">
                                        <span>Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 2MB)</p>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" onchange="previewImage(event)">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b pb-3">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                        Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Equipment Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="e.g., Main Sanctuary Speaker System">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Location
                            </label>
                            <input type="text" name="location" value="{{ old('location') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., Main Sanctuary">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Describe the equipment...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Equipment Details -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b pb-3">
                        <i class="fas fa-tag text-green-600 mr-3"></i>
                        Equipment Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                            <input type="text" name="brand" value="{{ old('brand') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., Yamaha">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                            <input type="text" name="model" value="{{ old('model') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., P-125">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Serial Number</label>
                            <input type="text" name="serial_number" value="{{ old('serial_number') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., SN123456789">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Condition <span class="text-red-500">*</span>
                            </label>
                            <select name="condition" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="excellent" {{ old('condition') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                                <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b pb-3">
                        <i class="fas fa-dollar-sign text-green-600 mr-3"></i>
                        Financial Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Date</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Price (GH₵)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500">GH₵</span>
                                <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}" 
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                            <input type="text" name="vendor" value="{{ old('vendor') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Where purchased">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Warranty Expiry</label>
                            <input type="date" name="warranty_expiry" value="{{ old('warranty_expiry') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Maintenance Schedule -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b pb-3">
                        <i class="fas fa-tools text-orange-600 mr-3"></i>
                        Maintenance Schedule
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Interval (Days)</label>
                            <input type="number" name="maintenance_interval_days" value="{{ old('maintenance_interval_days') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="e.g., 90">
                            <p class="mt-1 text-xs text-gray-500">How often should this equipment be serviced?</p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b pb-3">
                        <i class="fas fa-sticky-note text-yellow-600 mr-3"></i>
                        Additional Notes
                    </h3>
                    <textarea name="notes" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Any additional information about this equipment...">{{ old('notes') }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition-all transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>Save Equipment
                    </button>
                    <a href="{{ route('equipment.index') }}" class="bg-gray-200 text-gray-700 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-300 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- JavaScript for Image Preview -->
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

// Drag and drop functionality
const dropZone = document.querySelector('label[for="image"]');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-blue-500', 'bg-blue-100');
}

function unhighlight(e) {
    dropZone.classList.remove('border-blue-500', 'bg-blue-100');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('image').files = files;
    previewImage({ target: { files: files } });
}
</script>
@endsection