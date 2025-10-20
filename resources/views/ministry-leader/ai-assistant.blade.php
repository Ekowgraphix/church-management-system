@extends('layouts.ministry-leader')

@section('title', 'AI Assistant')
@section('header', 'AI Ministry Assistant')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chat Interface -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-md flex flex-col" style="height: calc(100vh - 200px);">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-robot text-blue-600 mr-2"></i>
                Ministry AI Assistant
            </h3>
            <p class="text-sm text-gray-600 mt-1">Get insights, suggestions, and guidance for ministry leadership</p>
        </div>
        
        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            <!-- AI Welcome Message -->
            <div class="flex items-start">
                <div class="bg-blue-100 rounded-full p-2 mr-3">
                    <i class="fas fa-robot text-blue-600"></i>
                </div>
                <div class="bg-gray-100 rounded-lg p-4 max-w-lg">
                    <p class="text-gray-800">Hello! I'm your AI Ministry Assistant. I can help you with:</p>
                    <ul class="mt-2 space-y-1 text-sm text-gray-700">
                        <li>• Ministry planning and strategy</li>
                        <li>• Member engagement ideas</li>
                        <li>• Event suggestions</li>
                        <li>• Prayer request insights</li>
                        <li>• Leadership guidance</li>
                    </ul>
                    <p class="mt-2 text-gray-800">How can I assist you today?</p>
                </div>
            </div>
        </div>
        
        <!-- Chat Input -->
        <div class="p-6 border-t border-gray-200">
            <form class="flex space-x-3">
                <input type="text" 
                       placeholder="Ask me anything about ministry leadership..." 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Actions & Suggestions -->
    <div class="space-y-6">
        <!-- Suggested Questions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="font-bold text-gray-800 mb-4">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                Suggested Questions
            </h4>
            <div class="space-y-2">
                <button class="w-full text-left p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm">
                    How can I improve small group engagement?
                </button>
                <button class="w-full text-left p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition text-sm">
                    Suggest event ideas for youth ministry
                </button>
                <button class="w-full text-left p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition text-sm">
                    Tips for effective member follow-up
                </button>
                <button class="w-full text-left p-3 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition text-sm">
                    How to handle prayer requests sensitively
                </button>
            </div>
        </div>

        <!-- AI Features -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="font-bold text-gray-800 mb-4">
                <i class="fas fa-magic text-purple-500 mr-2"></i>
                AI Features
            </h4>
            <div class="space-y-3">
                <div class="p-3 bg-gray-50 rounded-lg">
                    <h5 class="font-semibold text-gray-800 text-sm mb-1">Smart Insights</h5>
                    <p class="text-xs text-gray-600">Get data-driven ministry insights</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <h5 class="font-semibold text-gray-800 text-sm mb-1">Content Generator</h5>
                    <p class="text-xs text-gray-600">Create announcements & messages</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <h5 class="font-semibold text-gray-800 text-sm mb-1">Prayer Analysis</h5>
                    <p class="text-xs text-gray-600">Identify common prayer themes</p>
                </div>
            </div>
        </div>

        <!-- Chat History -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-800">
                    <i class="fas fa-history text-gray-500 mr-2"></i>
                    Recent Chats
                </h4>
                <button class="text-blue-600 hover:text-blue-800 text-sm">Clear</button>
            </div>
            <p class="text-gray-500 text-sm text-center py-4">No recent conversations</p>
        </div>
    </div>
</div>
@endsection
