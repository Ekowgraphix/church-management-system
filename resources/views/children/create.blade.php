@extends('layouts.app')

@section('page-title', 'Register Child')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-4xl font-black text-white">👶 Register Child</h1>
        <a href="{{ route('children.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <div class="glass-card rounded-3xl p-8">
        <form action="{{ route('children.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Child's Name *</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-pink-400 transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Date of Birth *</label>
                    <input type="date" name="dob" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-pink-400 transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Guardian Name *</label>
                    <input type="text" name="guardian_name" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-pink-400 transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Guardian Contact</label>
                    <input type="text" name="guardian_contact"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-pink-400 transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Guardian Email</label>
                    <input type="email" name="guardian_email"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-pink-400 transition">
                </div>

                <div>
                    <label class="block text-white font-semibold mb-2">Class/Group</label>
                    <input type="text" name="class_group" placeholder="e.g., Beginners, Juniors"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-pink-400 transition">
                </div>
            </div>

            <div>
                <label class="block text-white font-semibold mb-2">Photo</label>
                <input type="file" name="photo" accept="image/*"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-pink-400 transition">
            </div>

            <div>
                <label class="block text-white font-semibold mb-2">Medical Information</label>
                <textarea name="medical_info" rows="3" placeholder="Allergies, conditions, medications..."
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-pink-400 transition"></textarea>
            </div>

            <div>
                <label class="block text-white font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-pink-400 transition"></textarea>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="glass-card px-8 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-pink-500/20 to-purple-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-save mr-2"></i>Register Child
                </button>
                <a href="{{ route('children.index') }}" class="glass-card px-8 py-4 rounded-xl font-semibold text-white hover:bg-white/10 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
