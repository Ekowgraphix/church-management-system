<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Media Team Portal') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN (for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-right: 1px solid rgba(34, 197, 94, 0.1);
            z-index: 1000;
            overflow-y: auto;
            padding: 1.5rem;
        }
        
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(34, 197, 94, 0.3); border-radius: 3px; }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .sidebar-item:hover {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            transform: translateX(4px);
        }
        
        .sidebar-item.active {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
        }
        
        .sidebar-item i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }
        
        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(34, 197, 94, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }
        
        /* Mobile Responsive */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .mobile-menu-btn {
                display: inline-flex !important;
            }
        }
        
        /* Cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.4);
        }
        
        /* Gradients */
        .gradient-green { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
        .gradient-blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .gradient-purple { background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); }
        .gradient-orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
        .gradient-red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .gradient-cyan { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    </style>
    
    @stack('styles')
</head>
<body class="bg-slate-900">
    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Logo -->
        <div class="mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl gradient-green flex items-center justify-center">
                    <i class="fas fa-film text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg">Media Portal</h2>
                    <p class="text-gray-400 text-xs">Content Management</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="space-y-1">
            <a href="{{ route('media.dashboard') }}" class="sidebar-item {{ request()->routeIs('media.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('media.library') }}" class="sidebar-item {{ request()->routeIs('media.library') ? 'active' : '' }}">
                <i class="fas fa-photo-video"></i>
                <span>Media Library</span>
            </a>
            
            <a href="{{ route('media.gallery') }}" class="sidebar-item {{ request()->routeIs('media.gallery') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                <span>Gallery Management</span>
            </a>
            
            <a href="{{ route('media.livestream') }}" class="sidebar-item {{ request()->routeIs('media.livestream') ? 'active' : '' }}">
                <i class="fas fa-broadcast-tower"></i>
                <span>Livestream Control</span>
            </a>
            
            <a href="{{ route('media.schedule') }}" class="sidebar-item {{ request()->routeIs('media.schedule') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Event Scheduling</span>
            </a>
            
            <a href="{{ route('media.youtube.search') }}" class="sidebar-item {{ request()->routeIs('media.youtube.*') ? 'active' : '' }}">
                <i class="fab fa-youtube"></i>
                <span>YouTube Search</span>
            </a>
            
            <a href="{{ route('media.ai-tools') }}" class="sidebar-item {{ request()->routeIs('media.ai-tools') ? 'active' : '' }}">
                <i class="fas fa-brain"></i>
                <span>AI Tools</span>
            </a>
            
            <a href="{{ route('media.announcements') }}" class="sidebar-item {{ request()->routeIs('media.announcements') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
            
            <a href="{{ route('media.team') }}" class="sidebar-item {{ request()->routeIs('media.team') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Team Management</span>
            </a>
            
            <a href="{{ route('media.analytics') }}" class="sidebar-item {{ request()->routeIs('media.analytics') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            
            <a href="{{ route('media.settings') }}" class="sidebar-item {{ request()->routeIs('media.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </nav>
        
        <!-- User Profile at Bottom -->
        <div class="mt-auto pt-6 border-t border-white/10">
            <div class="flex items-center space-x-3 p-3 rounded-xl bg-white/5">
                <div class="w-10 h-10 rounded-full gradient-green flex items-center justify-center">
                    <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 2) }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-white text-sm font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-gray-400 text-xs">Media Team</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="sidebar-item w-full text-red-400 hover:bg-red-500/10">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content relative z-10">
        <!-- Top Bar -->
        <header class="top-bar sticky top-0 z-30">
            <div class="flex items-center justify-between px-10 py-4">
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="flex-1 flex items-center gap-2">
                    <h1 class="text-xl font-black text-green-300">@yield('title', 'Media Portal')</h1>
                    <span class="text-xs text-green-200">@yield('subtitle', 'Content Management System')</span>
                </div>
                
                <div class="flex items-center gap-3">
                    <button onclick="toggleSearch()" class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center hover:bg-white/10 transition-all text-green-300 hover:text-green-200">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                    
                    <button onclick="toggleNotifications()" class="relative w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center hover:bg-white/10 transition-all text-green-300 hover:text-green-200">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-red-500 to-pink-600 rounded-full text-white text-xs flex items-center justify-center font-bold shadow-lg">3</span>
                    </button>
                    
                    <div class="text-right">
                        <p class="text-sm font-semibold text-green-300 whitespace-nowrap">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-green-200">Media Team</p>
                    </div>
                    
                    <div class="w-12 h-12 gradient-green rounded-2xl flex items-center justify-center text-white font-black text-lg shadow-xl cursor-pointer hover:scale-105 transition-transform">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-12 h-12 bg-red-500/20 hover:bg-red-500 rounded-xl flex items-center justify-center text-red-400 hover:text-white transition-all border border-red-500/30 hover:border-red-500"
                                onclick="return confirm('Are you sure you want to logout?')" title="Logout">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="animate-fade-in">
            <div class="max-w-[1920px] mx-auto px-8 py-8 space-y-6">
                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        function toggleMobileMenu() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
        
        function toggleSearch() {
            alert('Search feature coming soon!');
        }
        
        function toggleNotifications() {
            alert('Notifications feature coming soon!');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
