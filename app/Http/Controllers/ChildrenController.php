<?php

namespace App\Http\Controllers;

use App\Models\ChildrenMinistry;
use Illuminate\Http\Request;

class ChildrenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $children = ChildrenMinistry::latest()->paginate(20);
        
        // Calculate statistics
        $stats = [
            'total' => ChildrenMinistry::count(),
            'present_today' => rand(40, 60), // TODO: Implement actual attendance tracking
            'attendance_rate' => rand(75, 95),
            'classes' => 5,
            'teachers' => 8,
            'birthdays' => ChildrenMinistry::whereMonth('dob', now()->month)->count(),
            'milestones' => 12, // TODO: Implement milestone tracking
        ];
        
        return view('children-ministry.index', compact('children', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('children.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'dob' => 'required|date',
            'guardian_name' => 'required|string|max:100',
            'guardian_contact' => 'nullable|string|max:50',
            'guardian_email' => 'nullable|email|max:100',
            'class_group' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'medical_info' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('children', 'public');
        }

        ChildrenMinistry::create($validated);

        return redirect()->route('children.index')->with('success', 'Child registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $child = ChildrenMinistry::findOrFail($id);
        return view('children.show', compact('child'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $child = ChildrenMinistry::findOrFail($id);
        return view('children.edit', compact('child'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $child = ChildrenMinistry::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'dob' => 'required|date',
            'guardian_name' => 'required|string|max:100',
            'guardian_contact' => 'nullable|string|max:50',
            'guardian_email' => 'nullable|email|max:100',
            'class_group' => 'nullable|string|max:50',
            'medical_info' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $child->update($validated);

        return redirect()->route('children.index')->with('success', 'Child updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $child = ChildrenMinistry::findOrFail($id);
        $child->delete();

        return redirect()->route('children.index')->with('success', 'Child deleted successfully!');
    }

    public function attendance()
    {
        $children = ChildrenMinistry::all();
        return view('children.attendance', compact('children'));
    }

    public function markAttendance(Request $request)
    {
        // TODO: Implement attendance tracking
        return back()->with('success', 'Attendance marked successfully!');
    }
}
