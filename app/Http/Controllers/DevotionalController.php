<?php

namespace App\Http\Controllers;

use App\Models\Devotional;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DevotionalController extends Controller
{
    /**
     * Display today's devotional
     */
    public function index()
    {
        $today = Devotional::today()->published()->first();
        
        // Get recent devotionals
        $recent = Devotional::published()
            ->where('devotional_date', '<=', today())
            ->orderBy('devotional_date', 'desc')
            ->limit(7)
            ->get();

        // Show member-specific view for Church Members
        if (auth()->user()->hasRole('Church Member')) {
            return view('devotional.member-index', compact('today', 'recent'));
        }

        return view('devotional.index', compact('today', 'recent'));
    }

    /**
     * Show a specific devotional by date
     */
    public function show($date)
    {
        $devotional = Devotional::published()
            ->where('devotional_date', $date)
            ->firstOrFail();

        // Get previous and next devotionals
        $previous = Devotional::published()
            ->where('devotional_date', '<', $date)
            ->orderBy('devotional_date', 'desc')
            ->first();

        $next = Devotional::published()
            ->where('devotional_date', '>', $date)
            ->orderBy('devotional_date', 'asc')
            ->first();

        // Show member-specific view for Church Members
        if (auth()->user()->hasRole('Church Member')) {
            return view('devotional.member-show', compact('devotional', 'previous', 'next'));
        }

        return view('devotional.show', compact('devotional', 'previous', 'next'));
    }

    /**
     * Display devotional archive
     */
    public function archive(Request $request)
    {
        $query = Devotional::published()->orderBy('devotional_date', 'desc');

        // Filter by month if provided
        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('devotional_date', $request->month)
                  ->whereYear('devotional_date', $request->year);
        }

        $devotionals = $query->paginate(20);

        // Show member-specific view for Church Members
        if (auth()->user()->hasRole('Church Member')) {
            return view('devotional.member-archive', compact('devotionals'));
        }

        return view('devotional.archive', compact('devotionals'));
    }

    /**
     * Admin: Create devotional form
     */
    public function create()
    {
        return view('devotional.create');
    }

    /**
     * Admin: Store new devotional
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'devotional_date' => 'required|date',
            'scripture_reference' => 'required|string|max:255',
            'scripture_text' => 'required|string',
            'message' => 'required|string',
            'prayer' => 'nullable|string',
            'reflection_questions' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $devotional = Devotional::create($validated);

        return redirect()->route('devotional.index')
            ->with('success', 'Devotional created successfully!');
    }
}
