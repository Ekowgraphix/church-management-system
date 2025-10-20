<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Attendance;
use App\Models\Donation;
use App\Models\Event;
use App\Models\SmallGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\SyncsStorage;

class MemberPortalController extends Controller
{
    use SyncsStorage;
    public function index()
    {
        // Get authenticated user's member profile
        $user = auth()->user();
        $member = Member::where('email', $user->email)->first();
        
        if (!$member) {
            return redirect()->route('login')
                ->with('error', 'No member profile found. Please contact the administrator.');
        }

        $stats = [
            'total_attendance' => Attendance::where('member_id', $member->id)->count(),
            'attendance_this_month' => Attendance::where('member_id', $member->id)
                ->whereMonth('attendance_date', now()->month)->count(),
            'total_giving' => Donation::where('member_id', $member->id)->sum('amount'),
            'giving_this_year' => Donation::where('member_id', $member->id)
                ->whereYear('donation_date', now()->year)->sum('amount'),
        ];

        $recentAttendance = Attendance::where('member_id', $member->id)
            ->latest('attendance_date')
            ->limit(5)
            ->get();

        $upcomingEvents = Event::where('status', 'upcoming')
            ->where('start_date', '>=', now())
            ->latest('start_date')
            ->limit(3)
            ->get();

        $myGroups = SmallGroup::whereHas('activeMembers', function($q) use ($member) {
            $q->where('small_group_members.member_id', $member->id);
        })->get();

        return view('portal.index', compact('member', 'stats', 'recentAttendance', 'upcomingEvents', 'myGroups'));
    }

    public function profile()
    {
        $user = auth()->user();
        $member = Member::where('email', $user->email)->first();
        
        if (!$member) {
            return redirect()->route('login')
                ->with('error', 'No member profile found. Please contact the administrator.');
        }

        return view('portal.profile', compact('member'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $member = Member::where('email', $user->email)->first();
        
        if (!$member) {
            return redirect()->route('login')
                ->with('error', 'No member profile found. Please contact the administrator.');
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($member->photo) {
                \Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        $member->update($validated);
        
        // Sync storage to public (for systems without symlink support)
        $this->syncStorageToPublic();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function giving()
    {
        $user = auth()->user();
        $member = Member::where('email', $user->email)->first();
        
        if (!$member) {
            return redirect()->route('login')
                ->with('error', 'No member profile found. Please contact the administrator.');
        }

        $donations = Donation::where('member_id', $member->id)
            ->latest('donation_date')
            ->paginate(20);

        $stats = [
            'total' => Donation::where('member_id', $member->id)->sum('amount'),
            'this_year' => Donation::where('member_id', $member->id)
                ->whereYear('donation_date', now()->year)->sum('amount'),
            'this_month' => Donation::where('member_id', $member->id)
                ->whereMonth('donation_date', now()->month)->sum('amount'),
            'count' => Donation::where('member_id', $member->id)->count(),
        ];

        return view('portal.giving', compact('member', 'donations', 'stats'));
    }

    public function attendance()
    {
        $user = auth()->user();
        $member = Member::where('email', $user->email)->first();
        
        if (!$member) {
            return redirect()->route('login')
                ->with('error', 'No member profile found. Please contact the administrator.');
        }

        $attendance = Attendance::where('member_id', $member->id)
            ->latest('attendance_date')
            ->paginate(20);

        $stats = [
            'total' => Attendance::where('member_id', $member->id)->count(),
            'this_year' => Attendance::where('member_id', $member->id)
                ->whereYear('attendance_date', now()->year)->count(),
            'this_month' => Attendance::where('member_id', $member->id)
                ->whereMonth('attendance_date', now()->month)->count(),
            'streak' => $this->calculateStreak($member->id),
        ];

        return view('portal.attendance', compact('member', 'attendance', 'stats'));
    }

    private function calculateStreak($memberId)
    {
        $dates = Attendance::where('member_id', $memberId)
            ->orderBy('attendance_date', 'desc')
            ->pluck('attendance_date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->unique()
            ->values();

        if ($dates->isEmpty()) return 0;

        $streak = 1;
        for ($i = 0; $i < $dates->count() - 1; $i++) {
            $current = \Carbon\Carbon::parse($dates[$i]);
            $next = \Carbon\Carbon::parse($dates[$i + 1]);
            
            if ($current->diffInDays($next) <= 7) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
}
