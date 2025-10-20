@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🧠 AI Helper</h1>
        <p class="text-gray-600">Your personal assistant for volunteering</p>
    </div>

    <!-- AI Chat Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Quick Actions Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">Quick Help</h3>
                <div class="space-y-2">
                    <button class="w-full text-left px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg text-sm font-semibold text-orange-700">
                        <i class="fas fa-calendar-check mr-2"></i>Next Assignment
                    </button>
                    <button class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg text-sm font-semibold text-blue-700">
                        <i class="fas fa-lightbulb mr-2"></i>Task Tips
                    </button>
                    <button class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg text-sm font-semibold text-purple-700">
                        <i class="fas fa-bible mr-2"></i>Daily Verse
                    </button>
                    <button class="w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg text-sm font-semibold text-green-700">
                        <i class="fas fa-question-circle mr-2"></i>Event Prep
                    </button>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg flex flex-col" style="height: 600px;">
                <!-- Chat Messages -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <!-- AI Welcome Message -->
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-lg">
                            <p class="text-gray-800">Hello! I'm your AI volunteer assistant. How can I help you today?</p>
                            <ul class="mt-2 space-y-1 text-sm text-gray-700">
                                <li>• Check your next assignment</li>
                                <li>• Get task completion tips</li>
                                <li>• Find motivational verses</li>
                                <li>• Prepare for upcoming events</li>
                            </ul>
                        </div>
                    </div>

                    <!-- User Message Example -->
                    <div class="flex items-start space-x-3 justify-end">
                        <div class="bg-orange-600 text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-lg">
                            <p>What's my next assignment?</p>
                        </div>
                        <div class="w-10 h-10 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-orange-700"></i>
                        </div>
                    </div>

                    <!-- AI Response Example -->
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-lg">
                            <p class="text-gray-800 font-semibold mb-2">Your Next Assignment:</p>
                            <div class="text-sm text-gray-700 space-y-2">
                                <p><strong>Event:</strong> Sunday Worship Service</p>
                                <p><strong>Date:</strong> This Sunday, 9:00 AM</p>
                                <p><strong>Role:</strong> Ushering Team</p>
                                <p><strong>Tasks:</strong></p>
                                <ul class="list-disc ml-4">
                                    <li>Arrive by 8:30 AM</li>
                                    <li>Welcome guests at main entrance</li>
                                    <li>Assist with seating</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t p-4">
                    <div class="flex space-x-3">
                        <input type="text" placeholder="Ask me anything..." class="flex-1 border border-gray-300 rounded-lg px-4 py-3">
                        <button class="bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                            <i class="fas fa-microphone"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
