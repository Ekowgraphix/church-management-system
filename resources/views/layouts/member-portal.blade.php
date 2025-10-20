<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Member Portal - {{ config('app.name') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            position: relative;
        }
        
        /* Animated gradient background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
            z-index: 0;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .sidebar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(40px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 4px 0 40px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(180deg, 
                transparent 0%, 
                rgba(34, 197, 94, 0.5) 50%, 
                transparent 100%);
        }
        
        .sidebar-item {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 0%;
            width: 4px;
            background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
            transition: height 0.3s ease;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.1) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-item:hover::before {
            height: 70%;
        }
        
        .sidebar-item:hover::after {
            opacity: 1;
        }
        
        .sidebar-item:hover {
            transform: translateX(8px);
            background: rgba(34, 197, 94, 0.05);
        }
        
        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.2) 0%, transparent 100%);
        }
        
        .sidebar-item.active::before {
            height: 70%;
        }
        
        .icon-box {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .icon-box::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, currentColor, transparent);
            opacity: 0;
            border-radius: inherit;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-item:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }
        
        .sidebar-item:hover .icon-box::before {
            opacity: 0.2;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
                        inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border-color: rgba(34, 197, 94, 0.3);
        }
        
        .top-bar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(40px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
        }
        
        .top-bar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(34, 197, 94, 0.5) 50%, 
                transparent 100%);
        }
        
        .gradient-green {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.4);
        }
        
        .gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
        }
        
        .gradient-purple {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
            box-shadow: 0 4px 20px rgba(168, 85, 247, 0.4);
        }
        
        .gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        }
        
        .gradient-pink {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.4);
        }
        
        .gradient-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(34, 197, 94, 0.4);
            }
            50% {
                box-shadow: 0 0 40px rgba(34, 197, 94, 0.6);
            }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .notification-badge {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(34, 197, 94, 0.5);
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
        }
        
        /* Sidebar Navigation Scrollbar */
        .sidebar-nav {
            scrollbar-width: thin;
            scrollbar-color: #22c55e rgba(15, 23, 42, 0.5);
            overflow-y: auto !important;
            overflow-x: hidden;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 8px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 4px;
            margin: 8px 0;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
            border-radius: 4px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }
        
        /* Ensure sidebar has proper height constraints */
        .sidebar {
            max-height: 100vh;
        }
        
        .logo-glow {
            position: relative;
        }
        
        .logo-glow::after {
            content: '';
            position: absolute;
            inset: -4px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: inherit;
            opacity: 0.5;
            filter: blur(12px);
            z-index: -1;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #22c55e 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.1) 50%, 
                transparent 100%);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            to {
                left: 100%;
            }
        }
        
        /* Perfect Button Styles */
        .btn {
            position: relative;
            overflow: hidden;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            outline: none;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.4);
        }
        
        .btn-primary:hover {
            box-shadow: 0 8px 30px rgba(34, 197, 94, 0.6);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary:hover {
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.6);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4);
        }
        
        .btn-danger:hover {
            box-shadow: 0 8px 30px rgba(239, 68, 68, 0.6);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        }
        
        .btn-warning:hover {
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.6);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        }
        
        .btn-purple {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(168, 85, 247, 0.4);
        }
        
        .btn-pink {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.4);
        }
        
        .btn-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid rgba(34, 197, 94, 0.5);
            color: #22c55e;
            box-shadow: none;
        }
        
        .btn-outline:hover {
            background: rgba(34, 197, 94, 0.1);
            border-color: #22c55e;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
        }
        
        .btn-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.125rem;
        }
        
        .btn-icon {
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
        }
        
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen relative">
        <!-- Sidebar -->
        <aside class="sidebar w-80 h-screen fixed left-0 top-0 z-40 flex flex-col overflow-hidden">
            <!-- Logo Section - Fixed at top -->
            <div class="p-8 border-b border-white border-opacity-10 flex-shrink-0">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 gradient-green rounded-2xl flex items-center justify-center shadow-2xl logo-glow pulse-glow">
                        <i class="fas fa-church text-white text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-green-300 font-black text-2xl tracking-tight">Member Portal</h2>
                        <p class="text-green-200 text-xs font-medium">Your Church Dashboard</p>
                    </div>
                </div>
            </div>
            
            <!-- Scrollable Navigation -->
            <nav class="p-6 space-y-2 flex-1 sidebar-nav" style="overflow-y: scroll !important; overflow-x: hidden !important; max-height: calc(100vh - 250px); min-height: 400px;">
                <a href="{{ route('portal.index') }}" class="sidebar-item {{ request()->routeIs('portal.index') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('portal.index') ? 'gradient-green' : 'bg-white/5' }} group-hover:gradient-green transition-all relative z-10">
                        <i class="fas fa-home text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Dashboard</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('portal.profile') }}" class="sidebar-item {{ request()->routeIs('portal.profile') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('portal.profile') ? 'gradient-blue' : 'bg-white/5' }} group-hover:gradient-blue transition-all relative z-10">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">My Profile</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('events.index') }}" class="sidebar-item {{ request()->routeIs('events.*') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('events.*') ? 'gradient-purple' : 'bg-white/5' }} group-hover:gradient-purple transition-all relative z-10">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Events</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('portal.giving') }}" class="sidebar-item {{ request()->routeIs('portal.giving') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('portal.giving') ? 'gradient-orange' : 'bg-white/5' }} group-hover:gradient-orange transition-all relative z-10">
                        <i class="fas fa-hand-holding-usd text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">My Giving</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('chat.index') }}" class="sidebar-item {{ request()->routeIs('chat.*') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('chat.*') ? 'gradient-cyan' : 'bg-white/5' }} group-hover:gradient-cyan transition-all relative z-10">
                        <i class="fas fa-comments text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Chat</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('devotional.index') }}" class="sidebar-item {{ request()->routeIs('devotional.*') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('devotional.*') ? 'gradient-purple' : 'bg-white/5' }} group-hover:gradient-purple transition-all relative z-10">
                        <i class="fas fa-book-open text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Devotionals</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('prayer-requests.index') }}" class="sidebar-item {{ request()->routeIs('prayer-requests.*') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('prayer-requests.*') ? 'gradient-pink' : 'bg-white/5' }} group-hover:gradient-pink transition-all relative z-10">
                        <i class="fas fa-praying-hands text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Prayer Requests</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('ai.chat') }}" class="sidebar-item {{ request()->routeIs('ai.*') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('ai.*') ? 'gradient-cyan' : 'bg-white/5' }} group-hover:gradient-cyan transition-all relative z-10">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">AI Chat</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('notifications.index') }}" class="sidebar-item {{ request()->routeIs('notifications.*') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('notifications.*') ? 'gradient-orange' : 'bg-white/5' }} group-hover:gradient-orange transition-all relative z-10">
                        <i class="fas fa-bell text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Notifications</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
            </nav>
            
            <!-- Premium Badge - Fixed at bottom -->
            <div class="p-6 border-t border-white border-opacity-10 flex-shrink-0">
                <div class="glass-card p-4 rounded-2xl shimmer">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-green rounded-xl flex items-center justify-center">
                            <i class="fas fa-crown text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white text-xs font-bold">Premium Plan</p>
                            <p class="text-emerald-300 text-xs">Unlimited Access</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-80 relative z-10">
            <!-- Top Bar -->
            <header class="top-bar sticky top-0 z-30">
                <div class="flex items-center justify-between px-10 py-6">
                    <div>
                        <h1 class="text-3xl font-black text-green-300">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-green-200 mt-1 font-medium">@yield('page-subtitle', 'Welcome back to your workspace')</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <button onclick="toggleSearch()" class="relative text-green-300 hover:text-green-200 transition-colors group">
                            <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center group-hover:bg-white/10 transition-all">
                                <i class="fas fa-search text-lg text-green-300"></i>
                            </div>
                        </button>
                        <button onclick="toggleNotifications()" class="relative text-green-300 hover:text-green-200 transition-colors group">
                            <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center group-hover:bg-white/10 transition-all">
                                <i class="fas fa-bell text-lg text-green-300"></i>
                                <span class="notification-badge absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-red-500 to-pink-600 rounded-full text-white text-xs flex items-center justify-center font-bold shadow-lg">3</span>
                            </div>
                        </button>
                        <div class="h-12 w-px bg-white/10"></div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-300">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-green-200">Church Member</p>
                            </div>
                            @php
                                $currentMember = \App\Models\Member::where('email', auth()->user()->email)->first();
                            @endphp
                            @if($currentMember && $currentMember->photo)
                                <img src="{{ asset('storage/' . $currentMember->photo) }}" 
                                     alt="{{ auth()->user()->name }}"
                                     class="w-14 h-14 rounded-2xl object-cover border-4 border-green-500 shadow-2xl logo-glow cursor-pointer hover:scale-110 transition-transform">
                            @else
                                <div class="w-14 h-14 gradient-green rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-2xl logo-glow cursor-pointer hover:scale-110 transition-transform">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-12 h-12 bg-white/5 hover:bg-red-500/20 rounded-xl flex items-center justify-center text-green-300 hover:text-red-400 transition-all">
                                    <i class="fas fa-sign-out-alt text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-10 animate-fade-in">
                @if (session('success'))
                    <div class="mb-8 glass-card border-l-4 border-green-500 px-6 py-5 rounded-2xl flex items-center space-x-4 shimmer">
                        <div class="w-12 h-12 gradient-green rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-green-300 text-lg">Success!</p>
                            <p class="text-sm text-green-200">{{ session('success') }}</p>
                        </div>
                        <button class="ml-auto text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-8 glass-card border-l-4 border-red-500 px-6 py-5 rounded-2xl flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-red-300 text-lg">Error!</p>
                            <p class="text-sm text-red-200">{{ session('error') }}</p>
                        </div>
                        <button class="ml-auto text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Search Modal -->
    <div id="searchModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center pt-20">
        <div class="glass-card max-w-2xl w-full mx-4 p-6 rounded-2xl animate-fade-in">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-green-300">Search</h3>
                <button onclick="toggleSearch()" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search members, visitors, transactions..." 
                       class="w-full px-4 py-3 pl-12 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <div class="mt-4 space-y-2">
                <p class="text-sm text-gray-400">Quick links:</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('members.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-users"></i> Members
                    </a>
                    <a href="{{ route('attendance.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-calendar-check"></i> Attendance
                    </a>
                    <a href="{{ route('donations.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-dollar-sign"></i> Donations
                    </a>
                    <a href="{{ route('visitors.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-user-plus"></i> Visitors
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notifications Dropdown -->
    <div id="notificationsDropdown" class="hidden fixed top-20 right-8 w-96 glass-card rounded-2xl p-6 z-50 animate-fade-in">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-green-300">Notifications</h3>
            <button onclick="toggleNotifications()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="space-y-3">
            <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition-all cursor-pointer border-l-4 border-green-500">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 gradient-green rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-plus text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-semibold text-sm">New Member Added</p>
                        <p class="text-gray-400 text-xs mt-1">John Doe joined the church</p>
                        <p class="text-green-300 text-xs mt-1">2 hours ago</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition-all cursor-pointer border-l-4 border-blue-500">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 gradient-blue rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-semibold text-sm">Attendance Marked</p>
                        <p class="text-gray-400 text-xs mt-1">150 members present today</p>
                        <p class="text-green-300 text-xs mt-1">5 hours ago</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition-all cursor-pointer border-l-4 border-orange-500">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 gradient-orange rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-semibold text-sm">New Donation</p>
                        <p class="text-gray-400 text-xs mt-1">₵500 received from Sarah</p>
                        <p class="text-green-300 text-xs mt-1">1 day ago</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/10">
            <a href="#" class="text-green-300 hover:text-green-200 text-sm font-semibold flex items-center justify-center">
                View All Notifications
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    
    <script>
        function toggleSearch() {
            const modal = document.getElementById('searchModal');
            const input = document.getElementById('searchInput');
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) {
                setTimeout(() => input.focus(), 100);
            }
        }
        
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            dropdown.classList.toggle('hidden');
        }
        
        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const searchModal = document.getElementById('searchModal');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            
            if (event.target === searchModal) {
                searchModal.classList.add('hidden');
            }
        });
        
        // Close search modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.getElementById('searchModal').classList.add('hidden');
                document.getElementById('notificationsDropdown').classList.add('hidden');
            }
        });
        
        // Search functionality
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            // Add your search logic here
            console.log('Searching for:', query);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
