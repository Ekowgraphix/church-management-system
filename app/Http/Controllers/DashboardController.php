<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visitor;
use App\Models\AttendanceRecord;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Followup;
use App\Models\Equipment;
use App\Models\Event;
use App\Models\SmallGroup;
use App\Models\PrayerRequest;
use App\Models\MediaFile;
use App\Models\WelfareFund;
use App\Models\WelfareRequest;
use App\Models\Partnership;
use App\Models\CounsellingSession;
use App\Models\Child;
use App\Models\Offering;
use App\Models\Tithe;
use App\Models\SmsCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // AdminOnly middleware ensures only Admin can access this
        // All other roles are blocked at middleware level
        
        // Core Statistics
        $stats = [
            // Members & People
            'total_members' => Member::count(),
            'active_members' => Member::active()->count(),
            'new_members_this_month' => Member::whereMonth('created_at', Carbon::now()->month)->count(),
            'total_visitors' => Visitor::whereMonth('visit_date', Carbon::now()->month)->count(),
            
            // Financial
            'total_donations' => Donation::whereMonth('donation_date', Carbon::now()->month)
                ->where('status', 'completed')
                ->sum('amount'),
            'total_expenses' => Expense::whereMonth('expense_date', Carbon::now()->month)
                ->where('status', 'paid')
                ->sum('amount'),
            'total_tithes' => Donation::where('donation_type', 'tithe')->whereMonth('donation_date', Carbon::now()->month)->sum('amount'),
            'total_offerings' => Donation::where('donation_type', 'offering')->whereMonth('donation_date', Carbon::now()->month)->sum('amount'),
            'net_income' => 0, // Will calculate below
            
            // Ministry Activities
            'total_events' => Event::where('event_date', '>=', Carbon::now())->whereNotNull('event_date')->count(),
            'upcoming_events' => Event::where('event_date', '>=', Carbon::now())->whereNotNull('event_date')->take(3)->count(),
            'total_small_groups' => SmallGroup::where('status', 'active')->count(),
            'pending_followups' => Followup::pending()->count(),
            'overdue_followups' => Followup::overdue()->count(),
            
            // Prayer & Spiritual
            'total_prayer_requests' => PrayerRequest::count(),
            'pending_prayers' => PrayerRequest::where('status', 'pending')->count(),
            
            // New Modules (with error handling for missing tables)
            'total_media_files' => $this->safeCount('App\Models\MediaFile'),
            'total_equipment' => $this->safeCount('App\Models\Equipment'),
            'welfare_requests_pending' => $this->safeCount('App\Models\WelfareRequest', ['status' => 'pending']),
            'active_partnerships' => $this->safeCount('App\Models\Partnership', ['status' => 'active']),
            'counselling_sessions' => $this->safeMonthCount('App\Models\CounsellingSession', 'session_date'),
            'total_children' => $this->safeCount('App\Models\Child'),
            'sms_sent_this_month' => $this->safeMonthCount('App\Models\SmsCampaign', 'created_at'),
        ];
        
        // Calculate net income
        $totalIncome = $stats['total_donations'] + $stats['total_tithes'] + $stats['total_offerings'];
        $stats['net_income'] = $totalIncome - $stats['total_expenses'];
        
        // Welfare balance (with error handling)
        try {
            $welfareIncome = WelfareFund::where('type', 'income')->sum('amount');
            $welfareExpense = WelfareFund::where('type', 'expense')->sum('amount');
            $stats['welfare_balance'] = $welfareIncome - $welfareExpense;
        } catch (\Exception $e) {
            $stats['welfare_balance'] = 0;
        }

        // Attendance trend for the last 12 weeks (SQLite compatible)
        $attendanceTrend = AttendanceRecord::select(
                DB::raw("strftime('%W', attendance_date) as week"),
                DB::raw('COUNT(*) as count')
            )
            ->where('attendance_date', '>=', Carbon::now()->subWeeks(12))
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        // Monthly donations for the last 6 months (SQLite compatible)
        $donationTrend = Donation::select(
                DB::raw("strftime('%m', donation_date) as month"),
                DB::raw('SUM(amount) as total')
            )
            ->where('donation_date', '>=', Carbon::now()->subMonths(6))
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent activities from all modules
        $recentMembers = Member::latest()->take(5)->get();
        $recentVisitors = Visitor::latest()->take(5)->get();
        $recentDonations = Donation::with('member', 'category')
            ->latest()
            ->take(5)
            ->get();
        // Recent records (with error handling for missing tables)
        try {
            $recentEquipment = Equipment::latest()->take(5)->get();
        } catch (\Exception $e) {
            $recentEquipment = collect();
        }
        
        $recentPrayers = PrayerRequest::with('member')->latest()->take(5)->get();
        
        try {
            $recentWelfareRequests = WelfareRequest::latest()->take(5)->get();
        } catch (\Exception $e) {
            $recentWelfareRequests = collect();
        }
        
        try {
            $upcomingCounselling = CounsellingSession::where('session_date', '>=', Carbon::now())
                ->orderBy('session_date')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $upcomingCounselling = collect();
        }

        // Upcoming birthdays (SQLite compatible)
        $upcomingBirthdays = Member::whereRaw("strftime('%m', date_of_birth) = ?", [Carbon::now()->format('m')])
            ->whereRaw("strftime('%d', date_of_birth) >= ?", [Carbon::now()->format('d')])
            ->orderByRaw("strftime('%d', date_of_birth)")
            ->take(5)
            ->get();
            
        // Upcoming events
        $upcomingEvents = Event::where('event_date', '>=', Carbon::now())
            ->whereNotNull('event_date')
            ->orderBy('event_date')
            ->take(5)
            ->get();
            
        // Quick stats for cards
        $quickStats = [
            'attendance_today' => AttendanceRecord::whereDate('attendance_date', Carbon::today())->count(),
            'events_this_week' => Event::whereBetween('event_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereNotNull('event_date')->count(),
            'birthdays_this_month' => Member::whereRaw("strftime('%m', date_of_birth) = ?", [Carbon::now()->format('m')])->count(),
            'new_prayers_this_week' => PrayerRequest::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
        ];

        return view('dashboard.index', compact(
            'stats',
            'attendanceTrend',
            'donationTrend',
            'recentMembers',
            'recentVisitors',
            'recentDonations',
            'recentEquipment',
            'recentPrayers',
            'recentWelfareRequests',
            'upcomingCounselling',
            'upcomingBirthdays',
            'upcomingEvents',
            'quickStats'
        ));
    }

    /**
     * Safely count records from a model, returning 0 if table doesn't exist
     */
    private function safeCount($modelClass, $conditions = [])
    {
        try {
            if (!class_exists($modelClass)) {
                return 0;
            }

            $query = $modelClass::query();
            
            foreach ($conditions as $field => $value) {
                $query->where($field, $value);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            // Table doesn't exist or other error
            return 0;
        }
    }

    /**
     * Safely count records from current month
     */
    private function safeMonthCount($modelClass, $dateField = 'created_at')
    {
        try {
            if (!class_exists($modelClass)) {
                return 0;
            }

            return $modelClass::whereMonth($dateField, Carbon::now()->month)->count();
        } catch (\Exception $e) {
            // Table doesn't exist or other error
            return 0;
        }
    }
}
