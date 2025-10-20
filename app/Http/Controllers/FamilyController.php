<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Member;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index()
    {
        $families = Family::with(['headOfFamily', 'members'])->latest()->paginate(20);

        return view('families.index', compact('families'));
    }

    public function create()
    {
        $members = Member::where('membership_status', 'active')->get();
        return view('families.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'head_of_family_id' => 'required|exists:members,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $family = Family::create($validated);

        // Add head of family as member
        $family->members()->attach($validated['head_of_family_id'], [
            'relationship' => 'head',
        ]);

        return redirect()->route('families.show', $family)
            ->with('success', 'Family created successfully.');
    }

    public function show(Family $family)
    {
        $family->load(['headOfFamily', 'members']);
        
        $availableMembers = Member::where('membership_status', 'active')
            ->whereNotIn('id', $family->members->pluck('id'))
            ->get();

        return view('families.show', compact('family', 'availableMembers'));
    }

    public function addMember(Request $request, Family $family)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'relationship' => 'required|in:head,spouse,child,parent,sibling,other',
        ]);

        if ($family->members()->where('member_id', $validated['member_id'])->exists()) {
            return back()->with('error', 'Member already in this family.');
        }

        $family->members()->attach($validated['member_id'], [
            'relationship' => $validated['relationship'],
        ]);

        return back()->with('success', 'Member added to family.');
    }

    public function edit(Family $family)
    {
        $members = Member::where('membership_status', 'active')->get();
        return view('families.edit', compact('family', 'members'));
    }

    public function update(Request $request, Family $family)
    {
        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'head_of_family_id' => 'required|exists:members,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $family->update($validated);

        return redirect()->route('families.show', $family)
            ->with('success', 'Family updated successfully.');
    }

    public function destroy(Family $family)
    {
        $family->delete();

        return redirect()->route('families.index')
            ->with('success', 'Family deleted successfully.');
    }

    public function removeMember(Request $request, Family $family)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $family->members()->detach($request->member_id);

        return back()->with('success', 'Member removed from family.');
    }
}
