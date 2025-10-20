<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Show the login page
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login with role selection
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|string',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        // Check if user has the requested role
        $role = Role::where('name', $request->role)->first();
        
        if (!$role) {
            return back()->withErrors(['role' => 'Invalid role selected'])->withInput();
        }

        $hasRole = $user->hasRole($role->name);

        if (!$hasRole) {
            // If role is Church Member and user doesn't have it, redirect to signup
            if ($request->role === 'Church Member') {
                return redirect()->route('signup')
                    ->with('info', 'Please sign up as a Church Member')
                    ->withInput(['email' => $request->email]);
            }
            
            return back()->withErrors(['role' => 'You are not assigned to this role'])->withInput();
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password'])->withInput();
        }

        // Check if email is verified (only for Church Member signups, not admin-created users)
        // Admin, Pastor, Ministry Leader, Organization, Volunteer don't need email verification
        if ($request->role === 'Church Member' && !$user->email_verified_at) {
            return back()->withErrors(['email' => 'Please verify your email address first. Check your inbox for verification link.'])->withInput();
        }

        // Check if account is active
        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Your account has been deactivated'])->withInput();
        }

        // Login successful
        Auth::login($user, $request->filled('remember'));
        
        // Store active role in session
        $request->session()->put('active_role', $role->name);
        
        // Update last login
        $user->update(['last_login_at' => now()]);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'description' => "User logged in as {$role->name}",
            'ip_address' => $request->ip(),
        ]);

        $request->session()->regenerate();

        // Redirect based on role
        return $this->redirectByRole($role->name);
    }

    /**
     * Show the signup page for Church Members
     */
    public function showSignup()
    {
        return view('auth.signup');
    }

    /**
     * Handle Church Member signup
     */
    public function signupStore(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,male,female,Other,other',
        ]);

        DB::beginTransaction();
        
        try {
            // Create user account
            $user = User::create([
                'name' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => true,
                'email_verified_at' => null, // Will be set after verification
            ]);

            // Assign Church Member role
            $memberRole = Role::firstOrCreate(['name' => 'Church Member']);
            $user->assignRole($memberRole);

            // Create member profile
            $nameParts = explode(' ', $request->fullname);
            $firstName = $nameParts[0] ?? '';
            $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';

            // Generate unique member ID
            $memberIdPrefix = 'MEM';
            $year = date('Y');
            $lastMember = Member::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastMember && preg_match('/MEM' . $year . '(\d+)/', $lastMember->member_id, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }
            
            $memberId = $memberIdPrefix . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            Member::create([
                'member_id' => $memberId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->dob,
                'gender' => $request->gender ? strtolower($request->gender) : null,
                'membership_status' => 'inactive', // Will be activated after verification
                'membership_date' => now(),
            ]);

            // Generate verification token
            $token = Str::random(60);
            DB::table('email_verifications')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'token' => $token,
                    'created_at' => now(),
                ]
            );

            // Send verification email
            $emailSent = $this->sendVerificationEmail($user, $token);

            // Auto-verify in development mode (when using log driver)
            if (env('MAIL_MAILER') === 'log') {
                $user->update(['email_verified_at' => now()]);
                $member = Member::where('email', $user->email)->first();
                if ($member) {
                    $member->update(['membership_status' => 'active']);
                }
                DB::table('email_verifications')->where('user_id', $user->id)->delete();
                
                DB::commit();
                
                \Log::info("Auto-verified user in development mode: {$user->email}");
                
                return redirect()->route('login')
                    ->with('success', 'Account created and verified successfully! You can now log in immediately.');
            }

            DB::commit();

            if ($emailSent) {
                return redirect()->route('login')
                    ->with('success', 'Account created successfully! Please check your email to verify your account.');
            } else {
                // Email failed but account created
                \Log::warning("Verification email failed for user: {$user->email}");
                return redirect()->route('login')
                    ->with('warning', 'Account created! However, we could not send the verification email. Please contact the administrator or check your spam folder.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Signup failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Verify email address
     */
    public function verifyEmail($token)
    {
        $verification = DB::table('email_verifications')
            ->where('token', $token)
            ->first();

        if (!$verification) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Invalid verification token']);
        }

        // Check if token is older than 24 hours
        if (now()->diffInHours($verification->created_at) > 24) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Verification token has expired']);
        }

        $user = User::find($verification->user_id);
        
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['error' => 'User not found']);
        }

        // Mark email as verified
        $user->update(['email_verified_at' => now()]);

        // Update member status to active
        $member = Member::where('email', $user->email)->first();
        if ($member) {
            $member->update(['membership_status' => 'active']);
        }

        // Delete verification token
        DB::table('email_verifications')->where('token', $token)->delete();

        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now log in.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'description' => 'User logged out',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully');
    }

    /**
     * Send verification email
     */
    private function sendVerificationEmail($user, $token)
    {
        $verificationUrl = route('verify.email', ['token' => $token]);
        
        try {
            Mail::send('emails.verify', ['url' => $verificationUrl, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Verify Your Email Address - Church Management System');
            });
            return true;
        } catch (\Exception $e) {
            // Log error but don't fail the registration
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            \Log::error('Verification URL for ' . $user->email . ': ' . $verificationUrl);
            return false;
        }
    }

    /**
     * Redirect user based on their role to their specific portal
     */
    private function redirectByRole($roleName)
    {
        switch ($roleName) {
            case 'Admin':
                return redirect()->route('dashboard'); // Admin Dashboard
            case 'Pastor':
                return redirect()->route('pastor.dashboard'); // Pastor Portal
            case 'Ministry Leader':
                return redirect()->route('ministry-leader.dashboard'); // Ministry Leader Portal
            case 'Organization':
                return redirect()->route('organization.dashboard'); // Organization Portal
            case 'Volunteer':
                return redirect()->route('volunteer.dashboard'); // Volunteer Portal
            case 'Church Member':
                return redirect()->route('portal.index'); // Member Portal
            default:
                return redirect()->route('dashboard');
        }
    }
}
