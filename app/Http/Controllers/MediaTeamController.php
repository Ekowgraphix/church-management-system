<?php

namespace App\Http\Controllers;

use App\Models\MediaTeam;
use App\Models\Member;
use Illuminate\Http\Request;

class MediaTeamController extends Controller
{
    public function index()
    {
        $mediaTeams = MediaTeam::with('member')->latest()->paginate(20);
        return view('media-teams.index', compact('mediaTeams'));
    }

    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        return view('media-teams.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'role' => 'required|string|max:100',
            'availability' => 'nullable|string|max:50',
            'assigned_program' => 'nullable|string|max:200',
            'notes' => 'nullable|string',
        ]);

        MediaTeam::create($validated);

        return redirect()->route('media-teams.index')
            ->with('success', 'Media team member added successfully!');
    }

    public function show(MediaTeam $mediaTeam)
    {
        $mediaTeam->load('member');
        return view('media-teams.show', compact('mediaTeam'));
    }

    public function edit(MediaTeam $mediaTeam)
    {
        $members = Member::active()->orderBy('first_name')->get();
        return view('media-teams.edit', compact('mediaTeam', 'members'));
    }

    public function update(Request $request, MediaTeam $mediaTeam)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'role' => 'required|string|max:100',
            'availability' => 'nullable|string|max:50',
            'assigned_program' => 'nullable|string|max:200',
            'notes' => 'nullable|string',
        ]);

        $mediaTeam->update($validated);

        return redirect()->route('media-teams.index')
            ->with('success', 'Media team member updated successfully!');
    }

    public function destroy(MediaTeam $mediaTeam)
    {
        $mediaTeam->delete();
        
        return redirect()->route('media-teams.index')
            ->with('success', 'Media team member removed successfully!');
    }
}
