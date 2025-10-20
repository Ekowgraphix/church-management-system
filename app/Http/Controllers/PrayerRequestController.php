<?php

namespace App\Http\Controllers;

use App\Models\PrayerRequest;
use App\Models\Member;
use Illuminate\Http\Request;

class PrayerRequestController extends Controller
{
    public function index()
    {
        $prayerRequests = PrayerRequest::with(['member', 'assignedTo'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => PrayerRequest::count(),
            'pending' => PrayerRequest::where('status', 'pending')->count(),
            'answered' => PrayerRequest::where('status', 'answered')->count(),
            'public' => PrayerRequest::where('is_public', true)->count(),
        ];

        // Show member-specific view for Church Members
        if (auth()->user()->hasRole('Church Member')) {
            return view('prayer-requests.member-index', compact('prayerRequests', 'stats'));
        }

        return view('prayer-requests.index', compact('prayerRequests', 'stats'));
    }

    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        
        // Show member-specific view for Church Members
        if (auth()->user()->hasRole('Church Member')) {
            return view('prayer-requests.member-create');
        }
        
        return view('prayer-requests.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:health,family,financial,spiritual,other',
            'is_public' => 'boolean',
            'is_urgent' => 'boolean',
        ]);

        $validated['status'] = 'pending';
        $validated['requested_by'] = auth()->id();

        PrayerRequest::create($validated);

        return redirect()->route('prayer-requests.index')
            ->with('success', 'Prayer request submitted successfully!');
    }

    public function show(PrayerRequest $prayerRequest)
    {
        $prayerRequest->load(['member', 'assignedTo', 'responses']);
        
        // Show member-specific view for Church Members
        if (auth()->user()->hasRole('Church Member')) {
            return view('prayer-requests.member-show', compact('prayerRequest'));
        }
        
        return view('prayer-requests.show', compact('prayerRequest'));
    }

    public function update(Request $request, PrayerRequest $prayerRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,praying,answered',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $prayerRequest->update($validated);

        return back()->with('success', 'Prayer request updated!');
    }

    public function addResponse(Request $request, PrayerRequest $prayerRequest)
    {
        $validated = $request->validate([
            'response' => 'required|string',
        ]);

        $prayerRequest->responses()->create([
            'user_id' => auth()->id(),
            'response' => $validated['response'],
        ]);

        return back()->with('success', 'Response added!');
    }

    public function destroy(PrayerRequest $prayerRequest)
    {
        $prayerRequest->delete();
        return redirect()->route('prayer-requests.index')
            ->with('success', 'Prayer request deleted!');
    }
}
