<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Church Management System</title>
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
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
        .progress-bar {
            transition: width 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full glass-container p-12 rounded-3xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-user-plus text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">
                Join Our Church
            </h1>
            <p class="text-sm text-gray-300">
                Become a member of our community
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-xs text-gray-300 font-medium step-label" data-step="1">Personal Info</span>
                <span class="text-xs text-gray-300 font-medium step-label" data-step="2">Contact Details</span>
                <span class="text-xs text-gray-300 font-medium step-label" data-step="3">Create Account</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2">
                <div class="progress-bar bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" style="width: 33%"></div>
            </div>
        </div>
        
        <form class="space-y-6" method="POST" action="{{ route('signup.store') }}" id="signupForm">
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

            @if (session('info'))
                <div class="bg-blue-500/20 border border-blue-400/30 text-white text-sm p-4 rounded-xl backdrop-blur-sm">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        <p>{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            <!-- Step 1: Personal Information -->
            <div class="step active" data-step="1">
                <div class="space-y-5">
                    <div>
                        <label for="fullname" class="block text-sm font-medium text-gray-200 mb-2">
                            Full Name <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="fullname" name="fullname" type="text" required 
                                   class="input-glass w-full pl-11 pr-4 py-3.5 text-white placeholder-gray-400 rounded-xl focus:outline-none text-sm" 
                                   placeholder="Enter your full name" 
                                   value="{{ old('fullname') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="dob" class="block text-sm font-medium text-gray-200 mb-2">
                                Date of Birth
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                </div>
                                <input id="dob" name="dob" type="date" 
                                       class="input-glass w-full pl-11 pr-4 py-3.5 text-white placeholder-gray-400 rounded-xl focus:outline-none text-sm" 
                                       value="{{ old('dob') }}">
                            </div>
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-200 mb-2">
                                Gender
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-venus-mars text-gray-400"></i>
                                </div>
                                <select id="gender" name="gender" 
                                        class="input-glass w-full pl-11 pr-4 py-3.5 text-white rounded-xl focus:outline-none text-sm">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Contact Details -->
            <div class="step" data-step="2">
                <div class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-200 mb-2">
                            Email Address <span class="text-red-400">*</span>
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
                        <label for="phone" class="block text-sm font-medium text-gray-200 mb-2">
                            Phone Number <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input id="phone" name="phone" type="tel" required 
                                   class="input-glass w-full pl-11 pr-4 py-3.5 text-white placeholder-gray-400 rounded-xl focus:outline-none text-sm" 
                                   placeholder="Enter your phone number" 
                                   value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-200 mb-2">
                            Address
                        </label>
                        <div class="relative">
                            <div class="absolute top-3.5 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <textarea id="address" name="address" rows="3"
                                      class="input-glass w-full pl-11 pr-4 py-3.5 text-white placeholder-gray-400 rounded-xl focus:outline-none text-sm" 
                                      placeholder="Enter your address">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Create Account -->
            <div class="step" data-step="3">
                <div class="space-y-5">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-200 mb-2">
                            Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required 
                                   class="input-glass w-full pl-11 pr-4 py-3.5 text-white placeholder-gray-400 rounded-xl focus:outline-none text-sm" 
                                   placeholder="Create a password (min 8 characters)">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-200 mb-2">
                            Confirm Password <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                   class="input-glass w-full pl-11 pr-4 py-3.5 text-white placeholder-gray-400 rounded-xl focus:outline-none text-sm" 
                                   placeholder="Confirm your password">
                        </div>
                    </div>

                    <div class="bg-blue-500/20 border border-blue-400/30 text-white text-sm p-4 rounded-xl">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                            <div>
                                <p class="font-semibold mb-1">Email Verification Required</p>
                                <p class="text-xs text-gray-300">After signing up, you'll receive a verification email. Please check your inbox and click the verification link to activate your account.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between pt-4">
                <button type="button" id="prevBtn" onclick="changeStep(-1)" 
                        class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-sm font-semibold transition-all"
                        style="display: none;">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Previous
                </button>
                <button type="button" id="nextBtn" onclick="changeStep(1)" 
                        class="ml-auto px-6 py-3 btn-primary text-white rounded-xl text-sm font-semibold">
                    Next
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
                <button type="submit" id="submitBtn" 
                        class="ml-auto px-6 py-3 btn-primary text-white rounded-xl text-sm font-semibold"
                        style="display: none;">
                    <i class="fas fa-check mr-2"></i>
                    Create Account
                </button>
            </div>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-300">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-green-400 hover:text-green-300 font-semibold transition-colors">
                    Sign in here
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
        let currentStep = 1;
        const totalSteps = 3;

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            
            // Show current step
            document.querySelector(`.step[data-step="${step}"]`).classList.add('active');
            
            // Update progress bar
            const progress = (step / totalSteps) * 100;
            document.querySelector('.progress-bar').style.width = progress + '%';
            
            // Update step labels
            document.querySelectorAll('.step-label').forEach(label => {
                const labelStep = parseInt(label.dataset.step);
                if (labelStep < step) {
                    label.classList.add('text-green-400');
                } else if (labelStep === step) {
                    label.classList.add('text-white', 'font-bold');
                    label.classList.remove('text-green-400');
                } else {
                    label.classList.remove('text-green-400', 'text-white', 'font-bold');
                }
            });
            
            // Show/hide buttons
            document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'inline-flex';
            document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'inline-flex';
            document.getElementById('submitBtn').style.display = step === totalSteps ? 'inline-flex' : 'none';
        }

        function changeStep(direction) {
            const newStep = currentStep + direction;
            
            if (newStep < 1 || newStep > totalSteps) return;
            
            // Validate current step before moving forward
            if (direction === 1 && !validateStep(currentStep)) {
                return;
            }
            
            currentStep = newStep;
            showStep(currentStep);
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector(`.step[data-step="${step}"]`);
            const requiredInputs = currentStepElement.querySelectorAll('[required]');
            
            let valid = true;
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    valid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            if (!valid) {
                alert('Please fill in all required fields');
            }
            
            return valid;
        }

        // Initialize
        showStep(1);
    </script>
</body>
</html>
