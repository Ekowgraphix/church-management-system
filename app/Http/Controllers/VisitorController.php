<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\VisitorFollowup;
use App\Models\Member;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'visitors_this_week' => Visitor::whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'converted_members' => Visitor::where('conversion_status', 'converted')->count(),
            'pending_followup' => Visitor::where('followup_status', 'Pending')->count(),
            'returning_visitors' => Visitor::where('visit_count', '>', 1)->count(),
            'visitor_change' => 15,
            'conversion_rate' => 35,
        ];
        
        $visitors = Visitor::with(['followups'])
            ->latest('visit_date')
            ->limit(20)
            ->get();
        
        return view('visitors.dashboard', compact('stats', 'visitors'));
    }
    
    public function index(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('followup_status')) {
            $query->where('followup_status', $request->followup_status);
        }

        $visitors = $query->latest('visit_date')->paginate(20);

        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        return view('visitors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'visit_date' => 'required|date',
            'service_attended' => 'nullable|string|max:255',
            'visit_type' => 'required|in:first_time,returning,guest',
            'invited_by' => 'nullable|string|max:255',
            'interests' => 'nullable|string',
            'prayer_requests' => 'nullable|string',
            'wants_followup' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $visitor = Visitor::create($validated);

        return redirect()->route('visitors.show', $visitor)
            ->with('success', 'Visitor recorded successfully.');
    }

    public function show(Visitor $visitor)
    {
        $visitor->load(['followups', 'attendanceRecords']);

        return view('visitors.show', compact('visitor'));
    }

    public function edit(Visitor $visitor)
    {
        return view('visitors.edit', compact('visitor'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'visit_date' => 'required|date',
            'service_attended' => 'nullable|string|max:255',
            'visit_type' => 'required|in:first_time,returning,guest',
            'invited_by' => 'nullable|string|max:255',
            'interests' => 'nullable|string',
            'prayer_requests' => 'nullable|string',
            'wants_followup' => 'boolean',
            'followup_status' => 'required|in:pending,contacted,completed,no_response',
            'followup_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $visitor->update($validated);

        return redirect()->route('visitors.show', $visitor)
            ->with('success', 'Visitor updated successfully.');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();

        return redirect()->route('visitors.index')
            ->with('success', 'Visitor deleted successfully.');
    }

    public function convertToMember(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'membership_date' => 'required|date',
        ]);

        $member = Member::create([
            'member_id' => 'MEM-' . strtoupper(\Str::random(8)),
            'first_name' => $visitor->first_name,
            'last_name' => $visitor->last_name,
            'email' => $visitor->email,
            'phone' => $visitor->phone,
            'address' => $visitor->address,
            'city' => $visitor->city,
            'state' => $visitor->state,
            'membership_date' => $validated['membership_date'],
            'membership_status' => 'active',
        ]);

        $visitor->update([
            'converted_to_member' => true,
            'member_id' => $member->id,
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', 'Visitor converted to member successfully.');
    }

    public function addFollowup(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'followup_date' => 'required|date',
            'method' => 'required|in:phone,email,visit,sms,other',
            'notes' => 'required|string',
            'outcome' => 'nullable|in:positive,neutral,negative,no_response',
        ]);

        $validated['visitor_id'] = $visitor->id;
        $validated['followed_up_by'] = auth()->id();

        VisitorFollowup::create($validated);

        $visitor->update([
            'followup_status' => 'contacted',
            'followup_date' => $validated['followup_date'],
        ]);

        return redirect()->route('visitors.show', $visitor)
            ->with('success', 'Follow-up recorded successfully.');
    }
}
