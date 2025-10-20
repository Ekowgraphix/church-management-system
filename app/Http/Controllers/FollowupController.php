<?php

namespace App\Http\Controllers;

use App\Models\Followup;
use App\Models\FollowupType;
use App\Models\Member;
use App\Models\Cluster;
use App\Models\FollowupActivity;
use Illuminate\Http\Request;

class FollowupController extends Controller
{
    public function index(Request $request)
    {
        $query = Followup::with(['member', 'type', 'assignedTo', 'cluster']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $followups = $query->latest('due_date')->paginate(20);

        $stats = [
            'pending' => Followup::pending()->count(),
            'overdue' => Followup::overdue()->count(),
            'completed_this_month' => Followup::where('status', 'completed')
                ->whereMonth('completed_date', now()->month)->count(),
        ];

        return view('followups.index', compact('followups', 'stats'));
    }

    public function create()
    {
        $members = Member::active()->get();
        $types = FollowupType::where('is_active', true)->get();
        $clusters = Cluster::active()->get();

        return view('followups.create', compact('members', 'types', 'clusters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'cluster_id' => 'nullable|exists:clusters,id',
            'followup_type_id' => 'required|exists:followup_types,id',
            'assigned_to' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'pending';

        $followup = Followup::create($validated);

        return redirect()->route('followups.show', $followup)
            ->with('success', 'Follow-up created successfully.');
    }

    public function show(Followup $followup)
    {
        $followup->load(['member', 'type', 'assignedTo', 'createdBy', 'activities']);
        return view('followups.show', compact('followup'));
    }

    public function complete(Request $request, Followup $followup)
    {
        $validated = $request->validate([
            'outcome' => 'required|string',
        ]);

        $followup->update([
            'status' => 'completed',
            'completed_date' => now(),
            'outcome' => $validated['outcome'],
        ]);

        return redirect()->route('followups.show', $followup)
            ->with('success', 'Follow-up marked as completed.');
    }

    public function addActivity(Request $request, Followup $followup)
    {
        $validated = $request->validate([
            'activity_date' => 'required|date',
            'activity_type' => 'required|in:call,visit,email,sms,meeting,other',
            'notes' => 'required|string',
        ]);

        $validated['followup_id'] = $followup->id;
        $validated['user_id'] = auth()->id();

        FollowupActivity::create($validated);

        return redirect()->route('followups.show', $followup)
            ->with('success', 'Activity added successfully.');
    }
}
