<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Login - Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* FORCE MOBILE BASE STYLES */
        html {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
            margin: 0;
            padding: 0;
        }
        
        body {
            min-width: 320px;
        }
        
        *, *::before, *::after {
            box-sizing: border-box;
            max-width: 100%;
        }
        * {
            box-sizing: border-box;
        }
        
        body {
            background-image: url('{{ asset('images/church-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #0a2a3a;
            margin: 0;
            padding: 0;
        }
        .glass-container {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        .input-glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .input-glass:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(34, 197, 94, 0.5);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
        }
        .role-card {
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .role-card:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(34, 197, 94, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .role-card.selected {
            background: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.8);
        }
        .role-card input[type="radio"] {
            display: none;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .glass-container {
                margin: 1rem;
                padding: 1.5rem !important;
            }
            
            h1 {
                font-size: 1.5rem !important;
            }
            
            h2 {
                font-size: 1.25rem !important;
            }
            
            .role-card {
                padding: 1rem !important;
            }
            
            .role-card h3 {
                font-size: 1rem !important;
            }
            
            button, input {
                font-size: 1rem !important;
                padding: 0.75rem 1rem !important;
            }
        }
        
        @media (max-width: 640px) {
            .glass-container {
                margin: 0.5rem;
                padding: 1rem !important;
            }
            
            h1 {
                font-size: 1.25rem !important;
            }
            
            .grid-cols-2 {
                grid-template-columns: 1fr !important;
            }
        }

        /* Aggressive Mobile Fit CSS */
        * {
            box-sizing: border-box !important;
        }
        
        html {
            overflow-x: hidden;
            width: 100%;
        }
        
        body {
            overflow-x: hidden !important;
            width: 100% !important;
            max-width: 100vw !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        @media (max-width: 1024px) {
            body, html {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            .sidebar {
                position: fixed !important;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 50 !important;
            }
            
            main, .main-content, .content-area {
                width: 100% !important;
                max-width: 100vw !important;
                margin-left: 0 !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                overflow-x: hidden !important;
            }
            
            .container, .max-w-7xl, .w-full {
                max-width: 100% !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            
            table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            img {
                max-width: 100% !important;
                height: auto !important;
            }
        }
        
        @media (max-width: 768px) {
            .top-bar, .header {
                padding: 1rem !important;
                flex-wrap: wrap;
            }
            
            h1 {
                font-size: 1.5rem !important;
                word-wrap: break-word;
            }
            
            h2 {
                font-size: 1.25rem !important;
            }
            
            h3 {
                font-size: 1.1rem !important;
            }
            
            .card, .glass-card, .bg-white {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding: 1rem !important;
            }
            
            .grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            button, .btn, a.btn {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            input, textarea, select {
                width: 100% !important;
                font-size: 16px !important;
            }
        }
        
        @media (max-width: 640px) {
            body {
                font-size: 14px !important;
            }
            
            .p-10, .p-8, .p-6 {
                padding: 1rem !important;
            }
            
            .px-10, .px-8, .px-6 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            
            .text-3xl {
                font-size: 1.25rem !important;
            }
            
            .text-2xl {
                font-size: 1.125rem !important;
            }
            
            .space-x-6 > * + *, .space-x-4 > * + * {
                margin-left: 0.5rem !important;
            }
            
            .gap-6 {
                gap: 0.75rem !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full glass-container p-12 rounded-3xl">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-church text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">
                Welcome Back
            </h1>
            <p class="text-sm text-gray-300">
                Select your role and sign in to Church Management System
            </p>
        </div>
        
        <form class="space-y-6" method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-400/30 text-white text-sm p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-500/20 border border-green-400/30 text-white text-sm p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-500/20 border border-blue-400/30 text-white text-sm p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        <p>{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="bg-yellow-500/20 border border-yellow-400/30 text-white text-sm p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <p>{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-3">
                    Select Your Role
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @php
                        $roles = [
                            ['name' => 'Admin', 'icon' => 'fa-user-shield'],
                            ['name' => 'Pastor', 'icon' => 'fa-cross'],
                            ['name' => 'Ministry Leader', 'icon' => 'fa-users'],
                            ['name' => 'Volunteer', 'icon' => 'fa-hands-helping'],
                            ['name' => 'Organization', 'icon' => 'fa-building'],
                            ['name' => 'Church Member', 'icon' => 'fa-user'],
                            ['name' => 'Media Team', 'icon' => 'fa-film'],
                        ];
                    @endphp
                    
                    @foreach($roles as $role)
                    <label class="role-card p-4 rounded-xl text-center">
                        <input type="radio" name="role" value="{{ $role['name'] }}" required>
                        <div class="text-green-400 text-2xl mb-2">
                            <i class="fas {{ $role['icon'] }}"></i>
                        </div>
                        <div class="text-white text-xs font-medium">{{ $role['name'] }}</div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-200 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                               class="input-glass w-full pl-11 pr-4 py-3.5 text-white placeholder-gray-400 rounded-xl focus:outline-none text-sm" 
                               placeholder="Enter your email" 
                               value="{{ old('email') }}">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-200 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                               class="input-glass w-full pl-11 pr-4 py-3.5 text-white placeholder-gray-400 rounded-xl focus:outline-none text-sm" 
                               placeholder="Enter your password">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                           class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-400 rounded bg-white/10">
                    <label for="remember" class="ml-2 block text-sm text-gray-300">
                        Remember me
                    </label>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="btn-primary w-full flex items-center justify-center py-3.5 px-4 text-sm font-semibold rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>
            </div>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-300">
                New member? 
                <a href="{{ route('signup') }}" class="text-green-400 hover:text-green-300 font-semibold transition-colors">
                    Sign up here
                </a>
            </p>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">
                © 2025 Church Management System. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        // Add click handling for role cards
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Check the radio button
                this.querySelector('input[type="radio"]').checked = true;
            });
        });
        
        // Handle "Page Expired" error - auto refresh if page is idle too long
        let lastActivity = Date.now();
        const REFRESH_AFTER = 30 * 60 * 1000; // 30 minutes
        
        // Update last activity on any interaction
        document.addEventListener('click', () => lastActivity = Date.now());
        document.addEventListener('keypress', () => lastActivity = Date.now());
        document.addEventListener('touchstart', () => lastActivity = Date.now());
        
        // Before form submit, check if page is stale
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const timeSinceActivity = Date.now() - lastActivity;
                
                // If page has been idle for more than 30 minutes, refresh first
                if (timeSinceActivity > REFRESH_AFTER) {
                    e.preventDefault();
                    alert('Page has been idle for a while. Refreshing for security...');
                    window.location.reload();
                    return false;
                }
            });
        }
        
        // Auto-refresh page if idle for too long (prevents stale CSRF token)
        setInterval(function() {
            const timeSinceActivity = Date.now() - lastActivity;
            if (timeSinceActivity > REFRESH_AFTER) {
                console.log('Auto-refreshing due to inactivity...');
                window.location.reload();
            }
        }, 60000); // Check every minute
    </script>
</body>
</html>
