<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\Pledge;
use App\Models\PrayerRequest;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    // Get main dashboard statistics
    public function getDashboardStats()
    {
        return [
            'members' => $this->getMemberStats(),
            'financial' => $this->getFinancialStats(),
            'attendance' => $this->getAttendanceStats(),
            'recent_activity' => $this->getRecentActivity(),
            'upcoming_events' => $this->getUpcomingEvents(),
            'alerts' => $this->getAlerts(),
        ];
    }

    // Member statistics
    public function getMemberStats()
    {
        $total = Member::count();
        $active = Member::active()->count();
        $newThisMonth = Member::whereMonth('membership_date', Carbon::now()->month)
            ->whereYear('membership_date', Carbon::now()->year)
            ->count();

        return [
            'total_members' => $total,
            'active_members' => $active,
            'inactive_members' => $total - $active,
            'new_this_month' => $newThisMonth,
            'growth_rate' => $this->calculateGrowthRate('members'),
            'by_gender' => Member::select('gender', DB::raw('count(*) as count'))
                ->groupBy('gender')
                ->get(),
            'by_age_group' => $this->getAgeDistribution(),
        ];
    }

    // Financial statistics
    public function getFinancialStats()
    {
        $thisMonth = [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ];

        $donations = Donation::whereBetween('donation_date', $thisMonth)
            ->where('status', 'completed')
            ->sum('amount');

        $expenses = Expense::whereBetween('expense_date', $thisMonth)
            ->where('status', 'approved')
            ->sum('amount');

        return [
            'total_donations_month' => $donations,
            'total_expenses_month' => $expenses,
            'net_income_month' => $donations - $expenses,
            'total_donations_year' => Donation::whereYear('donation_date', Carbon::now()->year)
                ->where('status', 'completed')
                ->sum('amount'),
            'average_donation' => Donation::where('status', 'completed')
                ->avg('amount'),
            'top_donors' => $this->getTopDonors(5),
            'donation_trend' => $this->getDonationTrend(6),
            'expense_by_category' => $this->getExpensesByCategory(),
        ];
    }

    // Attendance statistics
    public function getAttendanceStats()
    {
        $lastSunday = Carbon::now()->previous('Sunday');
        $thisMonth = Carbon::now()->month;

        return [
            'last_service' => AttendanceRecord::whereDate('attendance_date', $lastSunday)->count(),
            'average_monthly' => AttendanceRecord::whereMonth('attendance_date', $thisMonth)
                ->select(DB::raw('AVG(count) as average'))
                ->from(DB::raw('(SELECT COUNT(*) as count FROM attendance_records WHERE MONTH(attendance_date) = ' . $thisMonth . ' GROUP BY attendance_date) as daily'))
                ->value('average') ?? 0,
            'trend' => $this->getAttendanceTrend(12),
            'by_service' => $this->getAttendanceByService(),
        ];
    }

    // Recent activity feed
    public function getRecentActivity($limit = 10)
    {
        $activities = [];

        // Recent members
        $recentMembers = Member::latest()->take(3)->get();
        foreach ($recentMembers as $member) {
            $activities[] = [
                'type' => 'member',
                'action' => 'joined',
                'description' => "{$member->full_name} joined the church",
                'time' => $member->created_at,
            ];
        }

        // Recent donations
        $recentDonations = Donation::with('member')->latest()->take(3)->get();
        foreach ($recentDonations as $donation) {
            $activities[] = [
                'type' => 'donation',
                'action' => 'donated',
                'description' => ($donation->member ? $donation->member->full_name : 'Anonymous') . " donated $" . number_format($donation->amount, 2),
                'time' => $donation->created_at,
            ];
        }

        // Recent prayer requests
        $recentPrayers = PrayerRequest::latest()->take(2)->get();
        foreach ($recentPrayers as $prayer) {
            $activities[] = [
                'type' => 'prayer',
                'action' => 'requested',
                'description' => "New prayer request: " . $prayer->title,
                'time' => $prayer->created_at,
            ];
        }

        // Sort by time and limit
        usort($activities, function($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        return array_slice($activities, 0, $limit);
    }

    // Upcoming events
    public function getUpcomingEvents($limit = 5)
    {
        return Event::where('event_date', '>=', Carbon::now())
            ->orderBy('event_date')
            ->take($limit)
            ->get();
    }

    // System alerts
    public function getAlerts()
    {
        $alerts = [];

        // Check for birthdays this week
        $birthdays = Member::whereRaw('DATE_FORMAT(date_of_birth, "%m-%d") BETWEEN ? AND ?', [
            Carbon::now()->format('m-d'),
            Carbon::now()->addDays(7)->format('m-d')
        ])->count();

        if ($birthdays > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "$birthdays birthday(s) this week",
                'icon' => 'cake',
            ];
        }

        // Check for low attendance
        $lastAttendance = AttendanceRecord::whereDate('attendance_date', Carbon::now()->previous('Sunday'))->count();
        $avgAttendance = AttendanceRecord::where('attendance_date', '>=', Carbon::now()->subMonths(3))
            ->select(DB::raw('AVG(count) as average'))
            ->from(DB::raw('(SELECT COUNT(*) as count FROM attendance_records GROUP BY attendance_date) as daily'))
            ->value('average') ?? 0;

        if ($lastAttendance < $avgAttendance * 0.8) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Attendance down from average',
                'icon' => 'alert-triangle',
            ];
        }

        // Check for pending expenses
        $pendingExpenses = Expense::where('status', 'pending')->count();
        if ($pendingExpenses > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "$pendingExpenses expense(s) awaiting approval",
                'icon' => 'file-text',
            ];
        }

        return $alerts;
    }

    // Widget data generators
    public function getWidgetData($widgetType, $config = [])
    {
        return match($widgetType) {
            'member_count' => ['value' => Member::count()],
            'attendance_today' => ['value' => AttendanceRecord::whereDate('attendance_date', Carbon::today())->count()],
            'donations_month' => ['value' => Donation::whereMonth('donation_date', Carbon::now()->month)->sum('amount')],
            'quick_stats' => $this->getQuickStats(),
            'attendance_chart' => $this->getAttendanceTrend(12),
            'financial_chart' => $this->getFinancialTrend(12),
            'member_growth' => $this->getMemberGrowthTrend(12),
            default => ['error' => 'Unknown widget type'],
        };
    }

    // Helper methods
    private function calculateGrowthRate($type)
    {
        // Calculate month-over-month growth rate
        $thisMonth = Member::whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonth = Member::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();

        return $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2) : 0;
    }

    private function getAgeDistribution()
    {
        $members = Member::whereNotNull('date_of_birth')->get();
        
        $distribution = [
            'Under 18' => 0,
            '18-30' => 0,
            '31-50' => 0,
            '51-65' => 0,
            'Over 65' => 0,
        ];
        
        foreach ($members as $member) {
            $age = Carbon::parse($member->date_of_birth)->age;
            if ($age < 18) $distribution['Under 18']++;
            elseif ($age <= 30) $distribution['18-30']++;
            elseif ($age <= 50) $distribution['31-50']++;
            elseif ($age <= 65) $distribution['51-65']++;
            else $distribution['Over 65']++;
        }
        
        return $distribution;
    }

    private function getTopDonors($limit = 5)
    {
        return Donation::with('member')
            ->select('member_id', DB::raw('SUM(amount) as total'))
            ->where('status', 'completed')
            ->groupBy('member_id')
            ->orderBy('total', 'desc')
            ->take($limit)
            ->get();
    }

    private function getDonationTrend($months = 6)
    {
        $trend = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $trend[] = [
                'month' => $month->format('M Y'),
                'amount' => Donation::whereYear('donation_date', $month->year)
                    ->whereMonth('donation_date', $month->month)
                    ->where('status', 'completed')
                    ->sum('amount'),
            ];
        }
        return $trend;
    }

    private function getExpensesByCategory()
    {
        return Expense::with('category')
            ->select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereMonth('expense_date', Carbon::now()->month)
            ->groupBy('expense_category_id')
            ->get();
    }

    private function getAttendanceTrend($weeks = 12)
    {
        $trend = [];
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $sunday = Carbon::now()->subWeeks($i)->previous('Sunday');
            $trend[] = [
                'date' => $sunday->format('M d'),
                'count' => AttendanceRecord::whereDate('attendance_date', $sunday)->count(),
            ];
        }
        return $trend;
    }

    private function getAttendanceByService()
    {
        return AttendanceRecord::with('service')
            ->select('service_id', DB::raw('COUNT(*) as count'))
            ->whereMonth('attendance_date', Carbon::now()->month)
            ->groupBy('service_id')
            ->get();
    }

    private function getQuickStats()
    {
        return [
            'total_members' => Member::count(),
            'todays_attendance' => AttendanceRecord::whereDate('attendance_date', Carbon::today())->count(),
            'this_month_donations' => Donation::whereMonth('donation_date', Carbon::now()->month)->sum('amount'),
            'active_pledges' => Pledge::where('status', 'active')->count(),
        ];
    }

    private function getFinancialTrend($months = 12)
    {
        $trend = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $income = Donation::whereYear('donation_date', $month->year)
                ->whereMonth('donation_date', $month->month)
                ->sum('amount');
            $expenses = Expense::whereYear('expense_date', $month->year)
                ->whereMonth('expense_date', $month->month)
                ->sum('amount');
            
            $trend[] = [
                'month' => $month->format('M Y'),
                'income' => $income,
                'expenses' => $expenses,
                'net' => $income - $expenses,
            ];
        }
        return $trend;
    }

    private function getMemberGrowthTrend($months = 12)
    {
        $trend = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $trend[] = [
                'month' => $month->format('M Y'),
                'new_members' => Member::whereYear('membership_date', $month->year)
                    ->whereMonth('membership_date', $month->month)
                    ->count(),
                'total_members' => Member::where('membership_date', '<=', $month->endOfMonth())->count(),
            ];
        }
        return $trend;
    }
}
