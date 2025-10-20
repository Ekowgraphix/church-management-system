<?php

namespace App\Http\Controllers;

use App\Models\MediaFile;
use App\Models\MediaTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $media = MediaFile::latest()->paginate(20);
        return view('media.index', compact('media'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'file_path' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200', // 50MB max
            'file_type' => 'required|in:image,video',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'event_name' => 'nullable|string|max:150',
        ]);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('media', 'public');
            $validated['file_path'] = $path;
            $validated['file_size'] = $file->getSize();
        }

        $validated['uploaded_by'] = auth()->user()->name;

        MediaFile::create($validated);

        return redirect()->route('media.index')->with('success', 'Media file uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $media = MediaFile::findOrFail($id);
        return view('media.show', compact('media'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $media = MediaFile::findOrFail($id);
        return view('media.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $media = MediaFile::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'event_name' => 'nullable|string|max:150',
        ]);

        $media->update($validated);

        return redirect()->route('media.index')->with('success', 'Media file updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $media = MediaFile::findOrFail($id);

        // Delete file from storage
        if ($media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return redirect()->route('media.index')->with('success', 'Media file deleted successfully!');
    }

    /**
     * Display media team management
     */
    public function team()
    {
        $team = MediaTeam::latest()->paginate(20);
        return view('media.team', compact('team'));
    }

    /**
     * Store media team member
     */
    public function storeTeam(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'contact' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'assigned_event' => 'nullable|string|max:150',
            'status' => 'required|in:active,inactive',
        ]);

        MediaTeam::create($validated);

        return back()->with('success', 'Team member added successfully!');
    }

    /**
     * Delete media team member
     */
    public function destroyTeam(string $id)
    {
        $team = MediaTeam::findOrFail($id);
        $team->delete();

        return back()->with('success', 'Team member removed successfully!');
    }
}
