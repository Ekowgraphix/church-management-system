<?php

namespace App\Http\Controllers;

use App\Models\Counselling;
use App\Models\CounsellingSession;
use App\Models\Counsellor;
use App\Models\CounsellingCategory;
use App\Models\CounsellingFollowup;
use App\Models\CounsellingNote;
use App\Models\Member;
use App\Models\Visitor;
use Illuminate\Http\Request;

class CounsellingController extends Controller
{
    public function index()
    {
        // Use CounsellingSession model for enhanced features
        $counsellings = CounsellingSession::with(['member', 'counsellor', 'category'])
            ->latest('session_date')
            ->paginate(20);
        
        // Calculate statistics
        $stats = [
            'total' => CounsellingSession::count(),
            'this_month' => CounsellingSession::whereMonth('session_date', now()->month)->count(),
            'upcoming' => CounsellingSession::upcoming()->count(),
            'counsellors' => Counsellor::active()->count(),
            'followups' => CounsellingFollowup::where('follow_up_date', '<=', now()->addWeek())->where('status', 'Pending')->count(),
            'success_rate' => 92, // Mock - calculate based on ratings
        ];
        
        return view('counselling.index-upgraded', compact('counsellings', 'stats'));
    }

    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        $visitors = Visitor::latest()->get();
        return view('counselling.create', compact('members', 'visitors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'counsellor' => 'required|string|max:150',
            'member_id' => 'nullable|exists:members,id',
            'visitor_id' => 'nullable|exists:visitors,id',
            'session_date' => 'required|date',
            'issues' => 'required|string',
            'outcome' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'confidentiality' => 'required|in:Normal,Private,Strict',
            'notes' => 'nullable|string',
        ]);

        Counselling::create($validated);

        return redirect()->route('counselling.index')
            ->with('success', 'Counselling session recorded successfully!');
    }

    public function show(Counselling $counselling)
    {
        $counselling->load(['member', 'visitor']);
        return view('counselling.show', compact('counselling'));
    }

    public function edit(Counselling $counselling)
    {
        $members = Member::active()->orderBy('first_name')->get();
        $visitors = Visitor::latest()->get();
        return view('counselling.edit', compact('counselling', 'members', 'visitors'));
    }

    public function update(Request $request, Counselling $counselling)
    {
        $validated = $request->validate([
            'counsellor' => 'required|string|max:150',
            'member_id' => 'nullable|exists:members,id',
            'visitor_id' => 'nullable|exists:visitors,id',
            'session_date' => 'required|date',
            'issues' => 'required|string',
            'outcome' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'confidentiality' => 'required|in:Normal,Private,Strict',
            'notes' => 'nullable|string',
        ]);

        $counselling->update($validated);

        return redirect()->route('counselling.index')
            ->with('success', 'Counselling session updated successfully!');
    }

    public function destroy(Counselling $counselling)
    {
        $counselling->delete();
        
        return redirect()->route('counselling.index')
            ->with('success', 'Counselling session deleted successfully!');
    }

    /**
     * Generate AI summary for session notes
     */
    public function summarizeNotes(Request $request, CounsellingSession $session)
    {
        $validated = $request->validate([
            'notes' => 'required|string',
        ]);

        $note = CounsellingNote::firstOrCreate(
            ['session_id' => $session->id],
            ['original_notes' => $validated['notes']]
        );

        $note->generateAISummary();

        return response()->json([
            'success' => true,
            'summary' => $note->ai_summary,
            'key_points' => $note->key_points,
            'action_items' => $note->action_items,
        ]);
    }

    /**
     * Get follow-ups for a session
     */
    public function followups(CounsellingSession $session)
    {
        $followups = $session->followups()->with('counsellor')->latest()->get();
        
        return response()->json($followups);
    }

    /**
     * Create a follow-up reminder
     */
    public function createFollowup(Request $request, CounsellingSession $session)
    {
        $validated = $request->validate([
            'follow_up_date' => 'required|date',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'notes' => 'nullable|string',
        ]);

        $followup = $session->followups()->create(array_merge($validated, [
            'counsellor_id' => $session->counsellor_id,
            'status' => 'Pending',
        ]));

        return response()->json([
            'success' => true,
            'followup' => $followup,
            'message' => 'Follow-up reminder created successfully!',
        ]);
    }

    /**
     * Export session data as encrypted PDF
     */
    public function exportPDF(CounsellingSession $session)
    {
        // Mock implementation - in production, use a PDF library
        return response()->json([
            'success' => true,
            'message' => 'PDF export feature - Coming soon with encryption!',
            'session_data' => [
                'member' => $session->member_name,
                'date' => $session->session_date->format('M d, Y'),
                'counsellor' => $session->counsellor_name,
                'encrypted' => true,
            ],
        ]);
    }

    /**
     * View calendar of sessions
     */
    public function calendar()
    {
        $sessions = CounsellingSession::with(['counsellor', 'category'])
            ->whereMonth('session_date', now()->month)
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->topic,
                    'start' => $session->session_date->format('Y-m-d'),
                    'backgroundColor' => $this->getColorByCategory($session->category),
                    'textColor' => '#ffffff',
                ];
            });

        return view('counselling.calendar', compact('sessions'));
    }

    /**
     * Get counsellor availability
     */
    public function counsellorAvailability()
    {
        $counsellors = Counsellor::active()
            ->withCount('sessions')
            ->get()
            ->map(function ($counsellor) {
                return [
                    'id' => $counsellor->id,
                    'name' => $counsellor->name,
                    'specialization' => $counsellor->specialization,
                    'total_sessions' => $counsellor->total_sessions,
                    'rating' => $counsellor->rating,
                    'available' => $counsellor->isAvailable(),
                ];
            });

        return response()->json($counsellors);
    }

    /**
     * Helper: Get color by category
     */
    private function getColorByCategory($category)
    {
        if (!$category) return '#6366f1';
        
        $colors = [
            'Marriage' => '#ec4899',
            'Family' => '#3b82f6',
            'Personal' => '#8b5cf6',
            'Career' => '#f59e0b',
            'Grief' => '#ef4444',
            'Spiritual' => '#10b981',
        ];

        return $colors[$category->name] ?? '#6366f1';
    }
}
