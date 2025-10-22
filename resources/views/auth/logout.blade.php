<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-image: url('{{ asset('images/church-background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(3px);
            z-index: 0;
        }
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full content-wrapper">
        <!-- Card -->
        <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-pink-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-4xl text-pink-400"></i>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-white text-center mb-2">
                Logout
            </h1>
            <p class="text-gray-300 text-center mb-8">
                Are you sure you want to logout?
            </p>

            <!-- User Info -->
            <div class="bg-white/5 rounded-lg p-4 mb-6 border border-white/10">
                <p class="text-gray-400 text-sm mb-1">Logged in as:</p>
                <p class="text-white font-semibold">{{ Auth::user()->name }}</p>
                <p class="text-gray-400 text-sm">{{ Auth::user()->email }}</p>
            </div>

            <!-- Logout Form -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-fuchsia-600 text-white font-bold py-3 px-4 rounded-xl hover:shadow-lg hover:shadow-pink-500/50 transform hover:scale-105 transition-all duration-200 mb-3">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Yes, Logout
                </button>
            </form>

            <!-- Cancel Button -->
            <a href="{{ url()->previous() }}" class="block w-full bg-white/10 text-white font-bold py-3 px-4 rounded-xl hover:bg-white/20 transition-all duration-200 text-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Cancel
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 text-center space-y-2">
            <a href="{{ route('dashboard') }}" class="text-pink-300 hover:text-pink-200 transition-colors">
                <i class="fas fa-home mr-1"></i>
                Go to Dashboard
            </a>
        </div>
    </div>

    <!-- Auto-submit script (optional) -->
    <script>
        // Uncomment to auto-submit after 3 seconds
        // setTimeout(function(){
        //     document.querySelector('form').submit();
        // }, 3000);
    </script>
</body>
</html>
