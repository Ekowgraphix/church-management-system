<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Member;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        try {
            $groups = Cluster::with('leader')->withCount('members')->paginate(20);
        } catch (\Exception $e) {
            // If pivot table doesn't exist, just get groups without count
            $groups = Cluster::with('leader')->paginate(20);
        }
        
        $totalMembers = Member::count();
        
        // Try to count members in groups using the pivot table
        try {
            $membersInGroups = \DB::table('cluster_members')
                ->distinct('member_id')
                ->count('member_id');
        } catch (\Exception $e) {
            $membersInGroups = 0;
        }
        
        $stats = [
            'total_groups' => Cluster::count(),
            'total_members' => $totalMembers,
            'members_in_groups' => $membersInGroups,
            'members_without_group' => $totalMembers - $membersInGroups,
        ];
        
        return view('groups.index', compact('groups', 'stats'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:members,id',
            'meeting_day' => 'nullable|string',
            'meeting_time' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        Cluster::create($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Group created successfully!');
    }

    public function show(Cluster $group)
    {
        $group->load(['members', 'leader']);
        return view('groups.show', compact('group'));
    }

    public function edit(Cluster $group)
    {
        $members = Member::active()->get();
        return view('groups.edit', compact('group', 'members'));
    }

    public function update(Request $request, Cluster $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:members,id',
            'meeting_day' => 'nullable|string',
            'meeting_time' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $group->update($validated);

        return redirect()->route('groups.index')
            ->with('success', 'Group updated successfully!');
    }

    public function destroy(Cluster $group)
    {
        $group->delete();

        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully!');
    }
}
