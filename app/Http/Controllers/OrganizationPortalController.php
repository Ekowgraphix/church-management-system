<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Event;
use App\Models\Donation;
use App\Models\Branch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrganizationPortalController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_branches' => 12, // Mock data - replace with Branch::count()
            'total_members' => Member::count(),
            'total_volunteers' => 150, // Mock - create volunteers query
            'current_events' => Event::where('start_date', '>=', Carbon::now())->count(),
            'monthly_giving' => Donation::whereMonth('donation_date', Carbon::now()->month)->sum('amount'),
        ];

        return view('organization.dashboard', compact('stats'));
    }

    public function branches()
    {
        // Mock branch data - replace with actual Branch model
        $branches = collect([
            (object)[
                'name' => 'Faith Temple',
                'location' => 'Accra',
                'pastor' => 'Ps. Owusu',
                'members' => 850,
                'status' => 'Active'
            ],
            (object)[
                'name' => 'Grace Centre',
                'location' => 'Kumasi',
                'pastor' => 'Ps. K. Appiah',
                'members' => 600,
                'status' => 'Active'
            ],
            (object)[
                'name' => 'Hope Sanctuary',
                'location' => 'Takoradi',
                'pastor' => 'Ps. Mensah',
                'members' => 420,
                'status' => 'Pending'
            ]
        ]);

        return view('organization.branches', compact('branches'));
    }

    public function staff()
    {
        return view('organization.staff');
    }

    public function finance()
    {
        $monthlyIncome = Donation::whereMonth('donation_date', Carbon::now()->month)->sum('amount');
        $yearlyIncome = Donation::whereYear('donation_date', Carbon::now()->year)->sum('amount');

        return view('organization.finance', compact('monthlyIncome', 'yearlyIncome'));
    }

    public function reports()
    {
        return view('organization.reports');
    }

    public function events()
    {
        $events = Event::latest()->paginate(20);
        return view('organization.events', compact('events'));
    }

    public function aiInsights()
    {
        return view('organization.ai-insights');
    }

    public function communication()
    {
        return view('organization.communication');
    }

    public function settings()
    {
        $organization = auth()->user();
        return view('organization.settings', compact('organization'));
    }
}
