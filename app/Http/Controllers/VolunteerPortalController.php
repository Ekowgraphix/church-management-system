<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PrayerRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VolunteerPortalController extends Controller
{
    public function dashboard()
    {
        $volunteer = auth()->user();
        
        $stats = [
            'upcoming_events' => 2, // Mock - replace with actual query
            'tasks_due' => 3, // Mock
            'hours_served' => 15, // Mock
            'tasks_completed' => 80, // Mock percentage
        ];

        $upcomingEvents = Event::where('start_date', '>=', Carbon::now())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return view('volunteer.dashboard', compact('stats', 'upcomingEvents', 'volunteer'));
    }

    public function events()
    {
        $events = Event::where('start_date', '>=', Carbon::now())
            ->orderBy('start_date')
            ->paginate(20);
            
        return view('volunteer.events', compact('events'));
    }

    public function tasks()
    {
        // Mock task data - replace with actual Task model
        $tasks = collect([
            (object)[
                'title' => 'Setup Chairs for Sunday Service',
                'deadline' => Carbon::now()->addDays(2),
                'status' => 'pending',
                'instructions' => 'Arrange 200 chairs in the main hall'
            ],
            (object)[
                'title' => 'Welcome Desk Duty',
                'deadline' => Carbon::now()->addDays(5),
                'status' => 'pending',
                'instructions' => 'Greet visitors and direct them to seating'
            ],
            (object)[
                'title' => 'Sound System Check',
                'deadline' => Carbon::now()->addDays(1),
                'status' => 'in_progress',
                'instructions' => 'Test all microphones and speakers'
            ]
        ]);

        return view('volunteer.tasks', compact('tasks'));
    }

    public function team()
    {
        return view('volunteer.team');
    }

    public function prayer()
    {
        $prayers = PrayerRequest::latest()->take(10)->get();
        return view('volunteer.prayer', compact('prayers'));
    }

    public function aiHelper()
    {
        return view('volunteer.ai-helper');
    }

    public function communication()
    {
        return view('volunteer.communication');
    }

    public function settings()
    {
        $volunteer = auth()->user();
        return view('volunteer.settings', compact('volunteer'));
    }
}
