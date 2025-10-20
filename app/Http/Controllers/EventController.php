<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\SyncsStorage;

class EventController extends Controller
{
    use SyncsStorage;
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest('start_date')->paginate(12);

        // Show member-specific view for Church Members
        if (auth()->user()->hasRole('Church Member')) {
            return view('events.member-index', compact('events'));
        }

        // Show staff management view for staff
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:service,meeting,conference,social,outreach,training,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'requires_registration' => 'boolean',
            'registration_fee' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // Set created_by to first user if not authenticated
        $validated['created_by'] = auth()->id() ?? \App\Models\User::first()->id ?? null;
        $validated['status'] = 'upcoming';
        $validated['requires_registration'] = $request->has('requires_registration');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($validated);
        
        // Sync storage to public (for systems without symlink support)
        $this->syncStorageToPublic();

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['registrations.member', 'creator']);
        
        // Show member-specific view for Church Members
        if (auth()->user()->hasRole('Church Member')) {
            return view('events.member-show', compact('event'));
        }
        
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:service,meeting,conference,social,outreach,training,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'requires_registration' => 'boolean',
            'registration_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['requires_registration'] = $request->has('requires_registration');

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);
        
        // Sync storage to public (for systems without symlink support)
        $this->syncStorageToPublic();

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function register(Request $request, Event $event)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        if ($event->isFullyBooked()) {
            return back()->with('error', 'Event is fully booked.');
        }

        if ($event->isMemberRegistered($request->member_id)) {
            return back()->with('error', 'Member is already registered.');
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'member_id' => $request->member_id,
            'status' => 'registered',
            'registered_at' => now(),
        ]);

        return back()->with('success', 'Registration successful.');
    }

    public function attendees(Event $event)
    {
        $registrations = $event->registrations()
            ->with('member')
            ->latest()
            ->get();

        return view('events.attendees', compact('event', 'registrations'));
    }
}
