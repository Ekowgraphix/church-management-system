<?php

namespace App\Http\Controllers;

use App\Models\SmallGroup;
use App\Models\Member;
use App\Models\SmallGroupAttendance;
use Illuminate\Http\Request;

class SmallGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = SmallGroup::with(['leader', 'activeMembers']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $groups = $query->latest()->paginate(12);

        return view('small-groups.index', compact('groups'));
    }

    public function create()
    {
        $members = Member::where('membership_status', 'active')->get();
        return view('small-groups.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:bible_study,prayer,youth,men,women,couples,children,other',
            'leader_id' => 'nullable|exists:members,id',
            'meeting_day' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'meeting_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'max_members' => 'nullable|integer|min:1',
        ]);

        $validated['is_active'] = true;

        $group = SmallGroup::create($validated);

        // Add leader as member
        if ($validated['leader_id']) {
            $group->members()->attach($validated['leader_id'], [
                'joined_date' => now(),
                'role' => 'leader',
                'is_active' => true,
            ]);
        }

        return redirect()->route('small-groups.show', $group)
            ->with('success', 'Small group created successfully.');
    }

    public function show(SmallGroup $smallGroup)
    {
        $smallGroup->load(['leader', 'activeMembers', 'attendance' => function($q) {
            $q->latest('meeting_date')->limit(10);
        }]);

        $availableMembers = Member::where('membership_status', 'active')
            ->whereNotIn('id', $smallGroup->activeMembers->pluck('id'))
            ->get();

        return view('small-groups.show', compact('smallGroup', 'availableMembers'));
    }

    public function edit(SmallGroup $smallGroup)
    {
        $members = Member::where('membership_status', 'active')->get();
        return view('small-groups.edit', compact('smallGroup', 'members'));
    }

    public function update(Request $request, SmallGroup $smallGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:bible_study,prayer,youth,men,women,couples,children,other',
            'leader_id' => 'nullable|exists:members,id',
            'meeting_day' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'meeting_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'max_members' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $smallGroup->update($validated);

        return redirect()->route('small-groups.show', $smallGroup)
            ->with('success', 'Small group updated successfully.');
    }

    public function destroy(SmallGroup $smallGroup)
    {
        $smallGroup->delete();

        return redirect()->route('small-groups.index')
            ->with('success', 'Small group deleted successfully.');
    }

    public function join(Request $request, SmallGroup $smallGroup)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        if ($smallGroup->isFull()) {
            return back()->with('error', 'Group is full.');
        }

        if ($smallGroup->hasMember($request->member_id)) {
            return back()->with('error', 'Member is already in this group.');
        }

        $smallGroup->members()->attach($request->member_id, [
            'joined_date' => now(),
            'role' => 'member',
            'is_active' => true,
        ]);

        return back()->with('success', 'Member added to group successfully.');
    }

    public function leave(Request $request, SmallGroup $smallGroup)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $smallGroup->members()->updateExistingPivot($request->member_id, [
            'is_active' => false,
        ]);

        return back()->with('success', 'Member removed from group.');
    }

    public function attendance(SmallGroup $smallGroup)
    {
        $attendance = SmallGroupAttendance::where('small_group_id', $smallGroup->id)
            ->with('member')
            ->latest('meeting_date')
            ->paginate(20);

        return view('small-groups.attendance', compact('smallGroup', 'attendance'));
    }
}
