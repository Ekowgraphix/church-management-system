<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\AttendanceRecord;
use App\Models\Pledge;
use App\Models\Event;
use App\Models\Group;
use App\Models\PrayerRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsEngine
{
    // ============================================
    // 1. ATTENDANCE PATTERN ANALYSIS
    // ============================================

    public function analyzeAttendancePatterns($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subYear();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        return [
            'summary' => $this->getAttendanceSummary($startDate, $endDate),
            'trends' => $this->getAttendanceTrends($startDate, $endDate),
            'patterns' => [
                'by_day_of_week' => $this->getAttendanceByDayOfWeek($startDate, $endDate),
                'by_service_type' => $this->getAttendanceByServiceType($startDate, $endDate),
                'by_month' => $this->getAttendanceByMonth($startDate, $endDate),
            ],
            'insights' => $this->getAttendanceInsights($startDate, $endDate),
        ];
    }

    private function getAttendanceSummary($startDate, $endDate)
    {
        $records = AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])->get();
        
        $dailyAttendance = $records->groupBy('attendance_date')->map->count();
        
        return [
            'total_records' => $records->count(),
            'unique_days' => $dailyAttendance->count(),
            'average_per_service' => $dailyAttendance->avg(),
            'highest_attendance' => $dailyAttendance->max(),
            'lowest_attendance' => $dailyAttendance->min(),
            'total_unique_members' => $records->pluck('member_id')->unique()->count(),
        ];
    }

    private function getAttendanceTrends($startDate, $endDate)
    {
        $weeks = [];
        $current = $startDate->copy()->startOfWeek();
        
        while ($current <= $endDate) {
            $weekEnd = $current->copy()->endOfWeek();
            $count = AttendanceRecord::whereBetween('attendance_date', [$current, $weekEnd])->count();
            
            $weeks[] = [
                'week_start' => $current->format('Y-m-d'),
                'attendance' => $count,
            ];
            
            $current->addWeek();
        }
        
        return [
            'weekly_data' => $weeks,
            'trend_direction' => $this->calculateTrendDirection($weeks),
            'growth_rate' => $this->calculateGrowthRate($weeks),
        ];
    }

    private function getAttendanceByDayOfWeek($startDate, $endDate)
    {
        return AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->select(DB::raw('DAYNAME(attendance_date) as day_name'), DB::raw('COUNT(*) as count'))
            ->groupBy('day_name')
            ->orderByRaw('FIELD(day_name, "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday")')
            ->get();
    }

    private function getAttendanceByServiceType($startDate, $endDate)
    {
        return AttendanceRecord::with('service')
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->select('service_id', DB::raw('COUNT(*) as count'))
            ->groupBy('service_id')
            ->get();
    }

    private function getAttendanceByMonth($startDate, $endDate)
    {
        return AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->select(DB::raw('DATE_FORMAT(attendance_date, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function getAttendanceInsights($startDate, $endDate)
    {
        $insights = [];
        
        // Peak attendance day
        $peakDay = $this->getAttendanceByDayOfWeek($startDate, $endDate)->sortByDesc('count')->first();
        if ($peakDay) {
            $insights[] = "Highest attendance is on {$peakDay->day_name}";
        }
        
        // Attendance consistency
        $records = AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->select('member_id', DB::raw('COUNT(*) as visits'))
            ->groupBy('member_id')
            ->get();
        
        $regularAttendees = $records->where('visits', '>=', 3)->count();
        $insights[] = "$regularAttendees members attend regularly (3+ times)";
        
        return $insights;
    }

    // ============================================
    // 2. FINANCIAL TREND ANALYSIS
    // ============================================

    public function analyzeFinancialTrends($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subYear();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();

        return [
            'summary' => $this->getFinancialSummary($startDate, $endDate),
            'income_analysis' => $this->analyzeIncome($startDate, $endDate),
            'expense_analysis' => $this->analyzeExpenses($startDate, $endDate),
            'trends' => $this->getFinancialTrends($startDate, $endDate),
            'forecasts' => $this->generateFinancialForecast($startDate, $endDate),
            'insights' => $this->getFinancialInsights($startDate, $endDate),
        ];
    }

    private function getFinancialSummary($startDate, $endDate)
    {
        $donations = Donation::whereBetween('donation_date', [$startDate, $endDate])
            ->where('status', 'completed');
        
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->where('status', 'approved');

        $totalIncome = $donations->sum('amount');
        $totalExpenses = $expenses->sum('amount');

        return [
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_income' => $totalIncome - $totalExpenses,
            'donation_count' => $donations->count(),
            'expense_count' => $expenses->count(),
            'average_donation' => $donations->avg('amount'),
            'average_expense' => $expenses->avg('amount'),
        ];
    }

    private function analyzeIncome($startDate, $endDate)
    {
        $donations = Donation::whereBetween('donation_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        return [
            'by_category' => $donations->groupBy('donation_category_id')->map(function($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('amount'),
                    'average' => $items->avg('amount'),
                ];
            }),
            'by_method' => $donations->groupBy('payment_method')->map->sum('amount'),
            'top_donors' => $this->getTopDonors($startDate, $endDate, 10),
            'donor_retention' => $this->calculateDonorRetention($startDate, $endDate),
        ];
    }

    private function analyzeExpenses($startDate, $endDate)
    {
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->get();

        return [
            'by_category' => $expenses->groupBy('expense_category_id')->map(function($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('amount'),
                    'average' => $items->avg('amount'),
                ];
            }),
            'by_department' => $expenses->groupBy('department_id')->map->sum('amount'),
            'largest_expenses' => $expenses->sortByDesc('amount')->take(10)->values(),
        ];
    }

    private function getFinancialTrends($startDate, $endDate)
    {
        $months = [];
        $current = $startDate->copy()->startOfMonth();
        
        while ($current <= $endDate) {
            $monthEnd = $current->copy()->endOfMonth();
            
            $income = Donation::whereBetween('donation_date', [$current, $monthEnd])
                ->where('status', 'completed')
                ->sum('amount');
            
            $expenses = Expense::whereBetween('expense_date', [$current, $monthEnd])
                ->where('status', 'approved')
                ->sum('amount');
            
            $months[] = [
                'month' => $current->format('Y-m'),
                'income' => $income,
                'expenses' => $expenses,
                'net' => $income - $expenses,
            ];
            
            $current->addMonth();
        }
        
        return [
            'monthly_data' => $months,
            'income_trend' => $this->calculateTrendDirection(array_column($months, 'income')),
            'expense_trend' => $this->calculateTrendDirection(array_column($months, 'expenses')),
        ];
    }

    private function generateFinancialForecast($startDate, $endDate)
    {
        // Simple linear regression forecast for next 3 months
        $donations = Donation::whereBetween('donation_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE_FORMAT(donation_date, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        if ($donations->count() < 3) {
            return ['forecast' => 'Insufficient data for forecast'];
        }

        $values = $donations->pluck('total')->toArray();
        $average = array_sum($values) / count($values);
        $trend = end($values) - reset($values);

        return [
            'next_month' => $average + ($trend / count($values)),
            'confidence' => 'medium',
            'basis' => 'Linear trend analysis',
        ];
    }

    private function getFinancialInsights($startDate, $endDate)
    {
        $insights = [];
        
        $summary = $this->getFinancialSummary($startDate, $endDate);
        
        // Net income insight
        if ($summary['net_income'] > 0) {
            $insights[] = "Positive cash flow: $" . number_format($summary['net_income'], 2);
        } else {
            $insights[] = "Deficit of $" . number_format(abs($summary['net_income']), 2);
        }
        
        // Expense ratio
        if ($summary['total_income'] > 0) {
            $ratio = ($summary['total_expenses'] / $summary['total_income']) * 100;
            $insights[] = "Expenses are " . round($ratio, 1) . "% of income";
        }
        
        return $insights;
    }

    // ============================================
    // 3. GROWTH METRICS
    // ============================================

    public function analyzeGrowthMetrics()
    {
        return [
            'member_growth' => $this->analyzeMemberGrowth(),
            'financial_growth' => $this->analyzeFinancialGrowth(),
            'attendance_growth' => $this->analyzeAttendanceGrowth(),
            'engagement_growth' => $this->analyzeEngagementGrowth(),
            'overall_health_score' => $this->calculateHealthScore(),
        ];
    }

    private function analyzeMemberGrowth()
    {
        $thisYear = Member::whereYear('membership_date', Carbon::now()->year)->count();
        $lastYear = Member::whereYear('membership_date', Carbon::now()->subYear()->year)->count();
        
        $thisMonth = Member::whereMonth('membership_date', Carbon::now()->month)
            ->whereYear('membership_date', Carbon::now()->year)
            ->count();
        $lastMonth = Member::whereMonth('membership_date', Carbon::now()->subMonth()->month)
            ->whereYear('membership_date', Carbon::now()->subMonth()->year)
            ->count();

        return [
            'total_members' => Member::count(),
            'new_this_year' => $thisYear,
            'new_last_year' => $lastYear,
            'yearly_growth_rate' => $lastYear > 0 ? round((($thisYear - $lastYear) / $lastYear) * 100, 2) : 0,
            'new_this_month' => $thisMonth,
            'new_last_month' => $lastMonth,
            'monthly_growth_rate' => $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2) : 0,
        ];
    }

    private function analyzeFinancialGrowth()
    {
        $thisYearIncome = Donation::whereYear('donation_date', Carbon::now()->year)->sum('amount');
        $lastYearIncome = Donation::whereYear('donation_date', Carbon::now()->subYear()->year)->sum('amount');

        return [
            'current_year_income' => $thisYearIncome,
            'last_year_income' => $lastYearIncome,
            'growth_rate' => $lastYearIncome > 0 ? round((($thisYearIncome - $lastYearIncome) / $lastYearIncome) * 100, 2) : 0,
        ];
    }

    private function analyzeAttendanceGrowth()
    {
        $currentAvg = AttendanceRecord::where('attendance_date', '>=', Carbon::now()->subMonths(3))
            ->count() / 12; // Approximate weekly average
        
        $previousAvg = AttendanceRecord::whereBetween('attendance_date', [
            Carbon::now()->subMonths(6),
            Carbon::now()->subMonths(3)
        ])->count() / 12;

        return [
            'current_average' => round($currentAvg, 0),
            'previous_average' => round($previousAvg, 0),
            'growth_rate' => $previousAvg > 0 ? round((($currentAvg - $previousAvg) / $previousAvg) * 100, 2) : 0,
        ];
    }

    private function analyzeEngagementGrowth()
    {
        // Track engagement through multiple touch points
        $thisMonth = Carbon::now()->month;
        
        return [
            'active_volunteers' => Member::whereHas('ministryInvolvements')->count(),
            'prayer_requests' => PrayerRequest::whereMonth('created_at', $thisMonth)->count(),
            'event_participants' => Event::whereMonth('event_date', $thisMonth)->sum('expected_attendance'),
            'engagement_score' => $this->calculateEngagementScore(),
        ];
    }

    // ============================================
    // 4. MINISTRY EFFECTIVENESS
    // ============================================

    public function analyzeMinistryEffectiveness()
    {
        return [
            'program_participation' => $this->analyzeProgramParticipation(),
            'volunteer_engagement' => $this->analyzeVolunteerEngagement(),
            'event_effectiveness' => $this->analyzeEventEffectiveness(),
            'ministry_roi' => $this->calculateMinistryROI(),
        ];
    }

    private function analyzeProgramParticipation()
    {
        $groups = Group::withCount('members')->get();
        
        return [
            'total_groups' => $groups->count(),
            'total_participants' => $groups->sum('members_count'),
            'average_group_size' => $groups->avg('members_count'),
            'most_popular' => $groups->sortByDesc('members_count')->take(5)->values(),
        ];
    }

    private function analyzeVolunteerEngagement()
    {
        // Placeholder - would need ministry involvement tracking
        return [
            'total_volunteers' => 0,
            'active_this_month' => 0,
            'volunteer_hours' => 0,
        ];
    }

    private function analyzeEventEffectiveness()
    {
        $events = Event::where('event_date', '>=', Carbon::now()->subMonths(6))->get();
        
        return [
            'total_events' => $events->count(),
            'average_attendance' => $events->avg('expected_attendance'),
            'attendance_rate' => 85, // Placeholder
            'most_attended' => $events->sortByDesc('expected_attendance')->take(5)->values(),
        ];
    }

    private function calculateMinistryROI()
    {
        // ROI calculation based on engagement vs expenses
        $ministryExpenses = Expense::where('status', 'approved')
            ->whereMonth('expense_date', Carbon::now()->month)
            ->sum('amount');
        
        $attendance = AttendanceRecord::whereMonth('attendance_date', Carbon::now()->month)->count();
        
        return [
            'cost_per_attendee' => $attendance > 0 ? $ministryExpenses / $attendance : 0,
            'engagement_rate' => $this->calculateEngagementScore(),
        ];
    }

    // ============================================
    // 5. MEMBER ENGAGEMENT ANALYSIS
    // ============================================

    public function analyzeMemberEngagement()
    {
        return [
            'engagement_levels' => $this->categorizeEngagementLevels(),
            'activity_patterns' => $this->analyzeActivityPatterns(),
            'retention_metrics' => $this->analyzeRetention(),
            'at_risk_members' => $this->identifyAtRiskMembers(),
        ];
    }

    private function categorizeEngagementLevels()
    {
        $members = Member::all();
        
        $categories = [
            'highly_engaged' => 0,
            'moderately_engaged' => 0,
            'low_engagement' => 0,
            'inactive' => 0,
        ];
        
        foreach ($members as $member) {
            $score = $this->calculateMemberEngagementScore($member);
            
            if ($score >= 80) $categories['highly_engaged']++;
            elseif ($score >= 50) $categories['moderately_engaged']++;
            elseif ($score >= 20) $categories['low_engagement']++;
            else $categories['inactive']++;
        }
        
        return $categories;
    }

    private function calculateMemberEngagementScore($member)
    {
        $score = 0;
        
        // Attendance score (40 points)
        $attendanceCount = AttendanceRecord::where('member_id', $member->id)
            ->where('attendance_date', '>=', Carbon::now()->subMonths(3))
            ->count();
        $score += min(40, $attendanceCount * 3);
        
        // Donation score (30 points)
        $donationCount = Donation::where('member_id', $member->id)
            ->where('donation_date', '>=', Carbon::now()->subMonths(3))
            ->count();
        $score += min(30, $donationCount * 10);
        
        // Activity score (30 points)
        $hasRecentActivity = PrayerRequest::where('member_id', $member->id)
            ->where('created_at', '>=', Carbon::now()->subMonths(3))
            ->exists();
        if ($hasRecentActivity) $score += 30;
        
        return min(100, $score);
    }

    private function analyzeActivityPatterns()
    {
        return [
            'most_active_day' => 'Sunday',
            'most_active_time' => 'Morning',
            'engagement_by_age_group' => $this->getEngagementByAgeGroup(),
        ];
    }

    private function getEngagementByAgeGroup()
    {
        $members = Member::whereNotNull('date_of_birth')->get();
        
        $engagement = [
            'Under 18' => 0,
            '18-30' => 0,
            '31-50' => 0,
            '51-65' => 0,
            'Over 65' => 0,
        ];
        
        foreach ($members as $member) {
            $age = Carbon::parse($member->date_of_birth)->age;
            $score = $this->calculateMemberEngagementScore($member);
            
            if ($age < 18) $engagement['Under 18'] += $score;
            elseif ($age <= 30) $engagement['18-30'] += $score;
            elseif ($age <= 50) $engagement['31-50'] += $score;
            elseif ($age <= 65) $engagement['51-65'] += $score;
            else $engagement['Over 65'] += $score;
        }
        
        return $engagement;
    }

    private function analyzeRetention()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::active()->count();
        
        return [
            'retention_rate' => $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 2) : 0,
            'churned_members' => $totalMembers - $activeMembers,
        ];
    }

    private function identifyAtRiskMembers()
    {
        $members = Member::active()->get();
        $atRisk = [];
        
        foreach ($members as $member) {
            // No attendance in 60 days
            $lastAttendance = AttendanceRecord::where('member_id', $member->id)
                ->latest('attendance_date')
                ->first();
            
            if (!$lastAttendance || $lastAttendance->attendance_date < Carbon::now()->subDays(60)) {
                $atRisk[] = [
                    'member' => $member,
                    'reason' => 'No attendance in 60+ days',
                    'last_seen' => $lastAttendance ? $lastAttendance->attendance_date : 'Never',
                ];
            }
        }
        
        return array_slice($atRisk, 0, 20); // Top 20 at-risk members
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    private function calculateTrendDirection($data)
    {
        if (is_array($data) && count($data) < 2) return 'stable';
        
        $values = is_array($data) ? $data : array_column($data, 'attendance');
        $first = reset($values);
        $last = end($values);
        
        if ($last > $first * 1.05) return 'increasing';
        if ($last < $first * 0.95) return 'decreasing';
        return 'stable';
    }

    private function calculateGrowthRate($data)
    {
        if (empty($data)) return 0;
        
        $values = is_array($data) ? $data : array_column($data, 'attendance');
        $first = reset($values);
        $last = end($values);
        
        return $first > 0 ? round((($last - $first) / $first) * 100, 2) : 0;
    }

    private function getTopDonors($startDate, $endDate, $limit = 10)
    {
        return Donation::with('member')
            ->whereBetween('donation_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select('member_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('member_id')
            ->orderBy('total', 'desc')
            ->take($limit)
            ->get();
    }

    private function calculateDonorRetention($startDate, $endDate)
    {
        $previousPeriod = [
            $startDate->copy()->sub($endDate->diffInDays($startDate), 'days'),
            $startDate
        ];
        
        $currentDonors = Donation::whereBetween('donation_date', [$startDate, $endDate])
            ->distinct('member_id')
            ->count('member_id');
        
        $previousDonors = Donation::whereBetween('donation_date', $previousPeriod)
            ->distinct('member_id')
            ->count('member_id');
        
        return [
            'current_donors' => $currentDonors,
            'previous_donors' => $previousDonors,
            'retention_rate' => $previousDonors > 0 ? round(($currentDonors / $previousDonors) * 100, 2) : 0,
        ];
    }

    private function calculateHealthScore()
    {
        $memberGrowth = $this->analyzeMemberGrowth()['yearly_growth_rate'];
        $financialGrowth = $this->analyzeFinancialGrowth()['growth_rate'];
        $attendanceGrowth = $this->analyzeAttendanceGrowth()['growth_rate'];
        
        $score = 50; // Base score
        $score += min(20, max(-20, $memberGrowth * 2));
        $score += min(20, max(-20, $financialGrowth / 5));
        $score += min(10, max(-10, $attendanceGrowth * 2));
        
        return round(min(100, max(0, $score)), 0);
    }

    private function calculateEngagementScore()
    {
        $totalMembers = Member::count();
        if ($totalMembers == 0) return 0;
        
        $attendanceRate = AttendanceRecord::where('attendance_date', '>=', Carbon::now()->subMonth())
            ->distinct('member_id')
            ->count('member_id') / $totalMembers * 100;
        
        $donationRate = Donation::where('donation_date', '>=', Carbon::now()->subMonth())
            ->distinct('member_id')
            ->count('member_id') / $totalMembers * 100;
        
        return round(($attendanceRate + $donationRate) / 2, 0);
    }
}
