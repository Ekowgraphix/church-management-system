<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Attendance;
use App\Models\Donation;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::where('membership_status', 'active')->count(),
            'new_members_this_month' => Member::whereMonth('created_at', now()->month)->count(),
            'total_donations' => Donation::sum('amount'),
            'donations_this_month' => Donation::whereMonth('created_at', now()->month)->sum('amount'),
            'avg_attendance' => Attendance::whereMonth('attendance_date', now()->month)->count() / max(now()->day, 1),
            'total_visitors' => Visitor::count(),
            'visitors_this_month' => Visitor::whereMonth('visit_date', now()->month)->count(),
        ];

        return view('analytics.index', compact('stats'));
    }

    public function getData(Request $request)
    {
        $type = $request->get('type', 'attendance');
        $period = $request->get('period', '30'); // days

        switch ($type) {
            case 'attendance':
                return $this->getAttendanceData($period);
            case 'donations':
                return $this->getDonationData($period);
            case 'members':
                return $this->getMemberGrowthData($period);
            case 'visitors':
                return $this->getVisitorData($period);
            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
    }

    private function getAttendanceData($days)
    {
        $data = Attendance::select(
            DB::raw('DATE(attendance_date) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('attendance_date', '>=', now()->subDays($days))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json([
            'labels' => $data->pluck('date')->map(fn($d) => date('M d', strtotime($d))),
            'data' => $data->pluck('count'),
        ]);
    }

    private function getDonationData($days)
    {
        $data = Donation::select(
            DB::raw('DATE(donation_date) as date'),
            DB::raw('SUM(amount) as total')
        )
        ->where('donation_date', '>=', now()->subDays($days))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json([
            'labels' => $data->pluck('date')->map(fn($d) => date('M d', strtotime($d))),
            'data' => $data->pluck('total'),
        ]);
    }

    private function getMemberGrowthData($days)
    {
        $data = Member::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays($days))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Calculate cumulative
        $cumulative = 0;
        $cumulativeData = $data->map(function($item) use (&$cumulative) {
            $cumulative += $item->count;
            return $cumulative;
        });

        return response()->json([
            'labels' => $data->pluck('date')->map(fn($d) => date('M d', strtotime($d))),
            'data' => $cumulativeData,
        ]);
    }

    private function getVisitorData($days)
    {
        $data = Visitor::select(
            DB::raw('DATE(visit_date) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('visit_date', '>=', now()->subDays($days))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json([
            'labels' => $data->pluck('date')->map(fn($d) => date('M d', strtotime($d))),
            'data' => $data->pluck('count'),
        ]);
    }
}
