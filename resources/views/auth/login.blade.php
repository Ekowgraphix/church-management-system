<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-image: url('{{ asset('images/church-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #0a2a3a;
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
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(34, 197, 94, 0.5);
            transform: translateY(-2px);
        }
        .role-card.selected {
            background: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.8);
        }
        .role-card input[type="radio"] {
            display: none;
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
    </script>
</body>
</html>
