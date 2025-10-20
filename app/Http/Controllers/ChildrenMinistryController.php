<?php

namespace App\Http\Controllers;

use App\Models\ChildrenMinistry;
use Illuminate\Http\Request;

class ChildrenMinistryController extends Controller
{
    public function index()
    {
        $children = ChildrenMinistry::latest()->paginate(20);
        return view('children-ministry.index', compact('children'));
    }

    public function create()
    {
        return view('children-ministry.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'child_name' => 'required|string|max:200',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'parent_name' => 'required|string|max:200',
            'contact' => 'required|string|max:50',
            'address' => 'nullable|string',
            'class_group' => 'nullable|string|max:100',
            'allergies' => 'nullable|string',
            'notes' => 'nullable|string',
            'registered_on' => 'required|date',
        ]);

        ChildrenMinistry::create($validated);

        return redirect()->route('children-ministry.index')
            ->with('success', 'Child registered successfully!');
    }

    public function show(ChildrenMinistry $childrenMinistry)
    {
        return view('children-ministry.show', compact('childrenMinistry'));
    }

    public function edit(ChildrenMinistry $childrenMinistry)
    {
        return view('children-ministry.edit', compact('childrenMinistry'));
    }

    public function update(Request $request, ChildrenMinistry $childrenMinistry)
    {
        $validated = $request->validate([
            'child_name' => 'required|string|max:200',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'parent_name' => 'required|string|max:200',
            'contact' => 'required|string|max:50',
            'address' => 'nullable|string',
            'class_group' => 'nullable|string|max:100',
            'allergies' => 'nullable|string',
            'notes' => 'nullable|string',
            'registered_on' => 'required|date',
        ]);

        $childrenMinistry->update($validated);

        return redirect()->route('children-ministry.index')
            ->with('success', 'Child updated successfully!');
    }

    public function destroy(ChildrenMinistry $childrenMinistry)
    {
        $childrenMinistry->delete();
        
        return redirect()->route('children-ministry.index')
            ->with('success', 'Child record deleted successfully!');
    }
}
