@extends('layouts.ministry-leader')

@section('title', 'Communication')
@section('header', 'Communication Center')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Message Compose -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-envelope text-blue-600 mr-2"></i>
            Compose Message
        </h3>
        
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Recipients</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Members</option>
                    <option>Small Group Leaders</option>
                    <option>Specific Group</option>
                    <option>Individual Member</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Message Type</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="type" value="sms" class="mr-2">
                        <span class="text-sm">SMS</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="type" value="email" class="mr-2" checked>
                        <span class="text-sm">Email</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="type" value="both" class="mr-2">
                        <span class="text-sm">Both</span>
                    </label>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter subject...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                <textarea rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type your message..."></textarea>
            </div>
            
            <div class="flex justify-between items-center">
                <button type="button" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-paperclip mr-2"></i>Attach File
                </button>
                <div class="flex space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-save mr-2"></i>Save Draft
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Send Message
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Recent Messages -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-history text-purple-600 mr-2"></i>
            Recent Messages
        </h3>
        
        <div class="space-y-3">
            @forelse($recentMessages as $message)
                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                    <div class="flex items-center mb-2">
                        <img src="https://ui-avatars.com/api/?name=Message&background=9333ea&color=fff" 
                             alt="Message" 
                             class="w-8 h-8 rounded-full mr-2">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800">{{ Str::limit($message->message, 30) }}</p>
                            <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">No recent messages</p>
            @endforelse
        </div>
        
        <button class="w-full mt-4 px-4 py-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition text-sm font-semibold">
            View All Messages
        </button>
    </div>
</div>

<!-- Quick Templates -->
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-file-alt text-green-600 mr-2"></i>
        Message Templates
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button class="p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition text-left">
            <h4 class="font-semibold text-gray-800 mb-1">Event Reminder</h4>
            <p class="text-sm text-gray-600">Quick reminder for upcoming events</p>
        </button>
        <button class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition text-left">
            <h4 class="font-semibold text-gray-800 mb-1">Prayer Update</h4>
            <p class="text-sm text-gray-600">Share prayer request updates</p>
        </button>
        <button class="p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition text-left">
            <h4 class="font-semibold text-gray-800 mb-1">Welcome Message</h4>
            <p class="text-sm text-gray-600">Welcome new members to ministry</p>
        </button>
    </div>
</div>
@endsection
