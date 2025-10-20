<?php

namespace App\Http\Controllers;

use App\Models\VolunteerRole;
use App\Models\VolunteerAssignment;
use App\Models\Member;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index()
    {
        $roles = VolunteerRole::with(['assignments' => function($q) {
            $q->where('assignment_date', '>=', now())->latest('assignment_date');
        }])->where('is_active', true)->get();

        return view('volunteers.index', compact('roles'));
    }

    public function create()
    {
        return view('volunteers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department' => 'required|in:worship,children,hospitality,media,security,ushering,other',
            'required_volunteers' => 'required|integer|min:1',
        ]);

        VolunteerRole::create($validated);

        return redirect()->route('volunteers.index')
            ->with('success', 'Volunteer role created successfully.');
    }

    public function schedule(Request $request)
    {
        $validated = $request->validate([
            'volunteer_role_id' => 'required|exists:volunteer_roles,id',
            'member_id' => 'required|exists:members,id',
            'assignment_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        VolunteerAssignment::create($validated);

        return back()->with('success', 'Volunteer scheduled successfully.');
    }

    public function assignments()
    {
        $assignments = VolunteerAssignment::with(['role', 'member'])
            ->where('assignment_date', '>=', now())
            ->orderBy('assignment_date')
            ->paginate(20);

        $members = Member::where('membership_status', 'active')->get();
        $roles = VolunteerRole::where('is_active', true)->get();

        return view('volunteers.assignments', compact('assignments', 'members', 'roles'));
    }

    public function updateStatus(Request $request, VolunteerAssignment $assignment)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
        ]);

        $assignment->update($validated);

        return back()->with('success', 'Status updated successfully.');
    }
}
