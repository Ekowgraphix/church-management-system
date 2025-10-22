<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PrayerRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VolunteerPortalController extends Controller
{
    public function dashboard()
    {
        $volunteer = auth()->user();
        
        $stats = [
            'upcoming_events' => 2, // Mock - replace with actual query
            'tasks_due' => 3, // Mock
            'hours_served' => 15, // Mock
            'tasks_completed' => 80, // Mock percentage
        ];

        $upcomingEvents = Event::where('start_date', '>=', Carbon::now())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return view('volunteer.dashboard', compact('stats', 'upcomingEvents', 'volunteer'));
    }

    public function events()
    {
        $events = Event::where('start_date', '>=', Carbon::now())
            ->orderBy('start_date')
            ->paginate(20);
            
        return view('volunteer.events', compact('events'));
    }

    public function tasks()
    {
        // Mock task data - replace with actual Task model
        $tasks = collect([
            (object)[
                'id' => 1,
                'title' => 'Setup Chairs for Sunday Service',
                'deadline' => Carbon::now()->addDays(2),
                'status' => 'pending',
                'instructions' => 'Arrange 200 chairs in the main hall'
            ],
            (object)[
                'id' => 2,
                'title' => 'Welcome Desk Duty',
                'deadline' => Carbon::now()->addDays(5),
                'status' => 'pending',
                'instructions' => 'Greet visitors and direct them to seating'
            ],
            (object)[
                'id' => 3,
                'title' => 'Sound System Check',
                'deadline' => Carbon::now()->addDays(1),
                'status' => 'in_progress',
                'instructions' => 'Test all microphones and speakers'
            ]
        ]);

        return view('volunteer.tasks', compact('tasks'));
    }

    public function team()
    {
        return view('volunteer.team');
    }

    public function prayer()
    {
        $prayers = PrayerRequest::latest()->take(10)->get();
        return view('volunteer.prayer', compact('prayers'));
    }

    public function aiHelper()
    {
        return view('volunteer.ai-helper');
    }

    public function communication()
    {
        return view('volunteer.communication');
    }

    public function settings()
    {
        $volunteer = auth()->user();
        return view('volunteer.settings', compact('volunteer'));
    }

    /**
     * Mark task as complete
     */
    public function completeTask($id)
    {
        try {
            // TODO: Update task status in database
            // $task = Task::findOrFail($id);
            // $task->update(['status' => 'completed', 'completed_at' => now()]);
            
            // For now, return success response
            return response()->json([
                'success' => true,
                'message' => 'Task marked as complete! +10 points earned.',
                'task_id' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload proof for task
     */
    public function uploadProof(Request $request, $id)
    {
        try {
            // Validate file
            $request->validate([
                'proof' => 'required|file|max:10240' // 10MB max
            ]);

            // TODO: Store file and update task
            // $file = $request->file('proof');
            // $path = $file->store('task-proofs', 'public');
            // $task = Task::findOrFail($id);
            // $task->update(['proof_file' => $path, 'status' => 'under_review']);

            return response()->json([
                'success' => true,
                'message' => 'Proof uploaded successfully!',
                'task_id' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit prayer request
     */
    public function submitPrayer(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'request' => 'required|string|min:10',
                'category' => 'nullable|string',
                'confidential' => 'nullable|boolean'
            ]);

            // TODO: Save to database
            // $prayer = PrayerRequest::create([
            //     'member_id' => auth()->id(),
            //     'request' => $validated['request'],
            //     'category' => $validated['category'] ?? 'General',
            //     'is_confidential' => $validated['confidential'] ?? false,
            //     'status' => 'pending',
            //     'prayer_count' => 0
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Prayer request submitted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit prayer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark prayer as prayed for
     */
    public function prayedFor($id)
    {
        try {
            // TODO: Increment prayer count in database
            // $prayer = PrayerRequest::findOrFail($id);
            // $prayer->increment('prayer_count');
            
            // Log who prayed
            // PrayerLog::create([
            //     'prayer_request_id' => $id,
            //     'user_id' => auth()->id(),
            //     'prayed_at' => now()
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for praying!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
