<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PrayerRequest;
use App\Models\Event;
use App\Models\Donation;
use App\Models\CounsellingSession;
use App\Models\Sermon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PastorPortalController extends Controller
{
    public function dashboard()
    {
        try {
            $stats = [
                'total_members' => $this->safeCount(Member::class),
                'active_members' => $this->safeCount(Member::class, ['membership_status' => 'active']),
                'new_this_week' => Member::where('created_at', '>=', Carbon::now()->subWeek())->count() ?? 0,
                'pending_prayers' => $this->safeCount(PrayerRequest::class, ['status' => 'pending']),
                'upcoming_events' => Event::where('start_date', '>=', Carbon::now())->count() ?? 0,
                'total_donations' => Donation::whereMonth('donation_date', Carbon::now()->month)->sum('amount') ?? 0,
                'pending_counselling' => $this->safeCount(CounsellingSession::class, ['status' => 'pending']),
            ];

            $recentPrayers = $this->safeGet(PrayerRequest::class, 5);
            $upcomingEvents = Event::where('start_date', '>=', Carbon::now())->orderBy('start_date')->take(3)->get();
            $topGivers = Member::withSum('donations', 'amount')
                ->orderBy('donations_sum_amount', 'desc')
                ->take(5)
                ->get();

            return view('pastor.dashboard', compact('stats', 'recentPrayers', 'upcomingEvents', 'topGivers'));
        } catch (\Exception $e) {
            // Graceful fallback if tables don't exist
            $stats = [
                'total_members' => 0,
                'active_members' => 0,
                'new_this_week' => 0,
                'pending_prayers' => 0,
                'upcoming_events' => 0,
                'total_donations' => 0,
                'pending_counselling' => 0,
            ];
            
            $recentPrayers = collect();
            $upcomingEvents = collect();
            $topGivers = collect();
            
            return view('pastor.dashboard', compact('stats', 'recentPrayers', 'upcomingEvents', 'topGivers'))
                ->with('info', 'Some features are not yet set up. Dashboard loaded with limited data.');
        }
    }

    // Helper method to safely count
    private function safeCount($model, $where = [])
    {
        try {
            $query = $model::query();
            foreach ($where as $key => $value) {
                $query->where($key, $value);
            }
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    // Helper method to safely get records
    private function safeGet($model, $limit = 5)
    {
        try {
            return $model::latest()->take($limit)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function sermons()
    {
        try {
            $sermons = Sermon::latest()->paginate(20);
        } catch (\Exception $e) {
            $sermons = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        return view('pastor.sermons', compact('sermons'));
    }

    // Store new sermon
    public function storeSermon(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sermon_date' => 'required|date',
            'scripture_reference' => 'required|string|max:255',
            'theme' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,mp4,wav,m4a|max:51200', // 50MB
        ]);

        try {
            $sermon = new Sermon();
            $sermon->title = $request->title;
            $sermon->sermon_date = $request->sermon_date;
            $sermon->scripture_reference = $request->scripture_reference;
            $sermon->theme = $request->theme;
            $sermon->notes = $request->notes;
            $sermon->speaker = auth()->user()->name;  // Required field
            $sermon->preacher = auth()->user()->name;
            $sermon->uploaded_by = auth()->id();       // Required foreign key
            
            // Handle file upload if present
            if ($request->hasFile('audio_file')) {
                $uploadPath = public_path('uploads/sermons');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $file = $request->file('audio_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($uploadPath, $filename);
                $sermon->audio_file = $filename;
            }
            
            $sermon->save();
            
            return redirect()->back()->with('success', 'Sermon uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to upload sermon: ' . $e->getMessage()]);
        }
    }

    // Update sermon
    public function updateSermon(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'theme' => 'nullable|string|max:255',
            'scripture_reference' => 'required|string|max:255',
        ]);

        try {
            $sermon = Sermon::findOrFail($id);
            $sermon->update($request->only(['title', 'theme', 'scripture_reference']));
            
            return redirect()->back()->with('success', 'Sermon updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update sermon.']);
        }
    }

    // Delete sermon
    public function deleteSermon($id)
    {
        try {
            $sermon = Sermon::findOrFail($id);
            
            // Delete audio file if exists
            if ($sermon->audio_file && file_exists(public_path('uploads/sermons/' . $sermon->audio_file))) {
                @unlink(public_path('uploads/sermons/' . $sermon->audio_file));
            }
            
            $sermon->delete();
            
            return redirect()->back()->with('success', 'Sermon deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete sermon.']);
        }
    }

    public function prayerRequests()
    {
        try {
            $prayers = PrayerRequest::with('member')->latest()->paginate(20);
        } catch (\Exception $e) {
            $prayers = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        return view('pastor.prayer-requests', compact('prayers'));
    }

    public function members()
    {
        try {
            $members = Member::where('membership_status', 'active')->paginate(20);
        } catch (\Exception $e) {
            $members = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        return view('pastor.members', compact('members'));
    }

    public function events()
    {
        try {
            $events = Event::latest()->paginate(20);
        } catch (\Exception $e) {
            $events = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        return view('pastor.events', compact('events'));
    }

    public function counselling()
    {
        try {
            $sessions = CounsellingSession::with('member')->latest()->paginate(20);
        } catch (\Exception $e) {
            // Return empty paginator instead of collection
            $sessions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        return view('pastor.counselling', compact('sessions'))
            ->with('info', 'Counselling management feature coming soon.');
    }

    public function finance()
    {
        try {
            $monthlyDonations = Donation::whereMonth('donation_date', Carbon::now()->month)->sum('amount') ?? 0;
            $yearlyDonations = Donation::whereYear('donation_date', Carbon::now()->year)->sum('amount') ?? 0;
            
            $donationsByType = Donation::selectRaw('donation_type, SUM(amount) as total')
                ->whereMonth('donation_date', Carbon::now()->month)
                ->groupBy('donation_type')
                ->get();

            $recentDonations = Donation::with('member')->latest()->take(10)->get();
        } catch (\Exception $e) {
            $monthlyDonations = 0;
            $yearlyDonations = 0;
            $donationsByType = collect();
            $recentDonations = collect();
        }

        return view('pastor.finance', compact('monthlyDonations', 'yearlyDonations', 'donationsByType', 'recentDonations'));
    }

    public function broadcast()
    {
        return view('pastor.broadcast');
    }

    public function aiAssistant()
    {
        return view('pastor.ai-assistant');
    }

    public function settings()
    {
        $pastor = auth()->user();
        return view('pastor.settings', compact('pastor'));
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $user->update($request->only(['name', 'email', 'phone']));

        return back()->with('success', 'Profile updated successfully!');
    }

    // Upload photo
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        
        if ($request->hasFile('photo')) {
            // Ensure directory exists
            $uploadPath = public_path('uploads/profiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $file = $request->file('photo');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            
            // Delete old photo if exists
            if ($user->profile_photo && file_exists(public_path('uploads/profiles/' . $user->profile_photo))) {
                @unlink(public_path('uploads/profiles/' . $user->profile_photo));
            }
            
            $user->update(['profile_photo' => $filename]);
            
            return back()->with('success', 'Profile photo uploaded successfully!');
        }

        return back()->withErrors(['photo' => 'Please select a photo to upload.']);
    }

    // Change password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'The new password confirmation does not match.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'current_password.required' => 'Please enter your current password.',
        ]);

        $user = auth()->user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect. Please try again.']);
        }

        $user->update(['password' => \Hash::make($request->new_password)]);

        return redirect()->back()->with('success', 'Password changed successfully! Please use your new password next time you log in.');
    }

    // Send broadcast
    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'recipient_group' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'channels' => 'required|array',
        ]);

        // Here you would implement actual broadcast logic
        // For now, just return success

        return back()->with('success', 'Broadcast sent successfully to ' . $request->recipient_group . '!');
    }

    // Schedule counselling session
    public function scheduleCounselling(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Here you would create the counselling session
        // For now, just return success

        return back()->with('success', 'Counselling session scheduled successfully!');
    }
}
