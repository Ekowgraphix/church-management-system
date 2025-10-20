<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use App\Models\Event;
use App\Models\SmallGroup;
use App\Models\PrayerRequest;
use App\Models\Donation;
use App\Models\Message;

class MinistryLeaderPortalController extends Controller
{
    /**
     * Display Ministry Leader Dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get ministry-specific data
        $totalMembers = Member::count();
        $activeGroups = SmallGroup::where('is_active', true)->count();
        $upcomingEvents = Event::where('event_date', '>', now())
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();
        $recentPrayers = PrayerRequest::latest()->take(5)->get();
        $monthlyDonations = Donation::whereMonth('created_at', now()->month)->sum('amount');
        
        return view('ministry-leader.dashboard', compact(
            'totalMembers',
            'activeGroups',
            'upcomingEvents',
            'recentPrayers',
            'monthlyDonations'
        ));
    }

    /**
     * Display Ministry Members
     */
    public function members()
    {
        $members = Member::latest()->paginate(20);
        return view('ministry-leader.members', compact('members'));
    }

    /**
     * Display Small Groups Management
     */
    public function groups()
    {
        $groups = SmallGroup::with('leader')->latest()->paginate(15);
        return view('ministry-leader.groups', compact('groups'));
    }

    /**
     * Display Events Management
     */
    public function events()
    {
        $events = Event::latest()->paginate(15);
        return view('ministry-leader.events', compact('events'));
    }

    /**
     * Display Prayer Requests
     */
    public function prayerRequests()
    {
        $prayers = PrayerRequest::latest()->paginate(20);
        return view('ministry-leader.prayer-requests', compact('prayers'));
    }

    /**
     * Display Ministry Reports
     */
    public function reports()
    {
        // Get report data
        $monthlyGrowth = Member::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();
        
        $eventAttendance = Event::where('event_date', '>=', now()->subMonths(3))
            ->with('attendees')
            ->get();
        
        return view('ministry-leader.reports', compact('monthlyGrowth', 'eventAttendance'));
    }

    /**
     * Display Communication Center
     */
    public function communication()
    {
        $recentMessages = Message::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->latest()
            ->take(20)
            ->get();
        
        return view('ministry-leader.communication', compact('recentMessages'));
    }

    /**
     * Display AI Ministry Assistant
     */
    public function aiAssistant()
    {
        return view('ministry-leader.ai-assistant');
    }

    /**
     * Display Settings
     */
    public function settings()
    {
        $user = auth()->user();
        return view('ministry-leader.settings', compact('user'));
    }

    /**
     * Update Profile Settings
     */
    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $uploadPath = public_path('uploads/profiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete old photo if exists
            if ($user->profile_photo && file_exists(public_path('uploads/profiles/' . $user->profile_photo))) {
                @unlink(public_path('uploads/profiles/' . $user->profile_photo));
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $user->profile_photo = $filename;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
