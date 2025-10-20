<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visitor;
use App\Models\AttendanceRecord;
use App\Models\Donation;
use App\Models\Expense;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $financialReportService;

    public function __construct(FinancialReportService $financialReportService)
    {
        $this->financialReportService = $financialReportService;
    }
    public function index()
    {
        return view('reports.index');
    }

    public function membership(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfYear());
        $endDate = $request->input('end_date', Carbon::now());

        $data = [
            'total_members' => Member::count(),
            'active_members' => Member::active()->count(),
            'inactive_members' => Member::inactive()->count(),
            'new_members' => Member::whereBetween('membership_date', [$startDate, $endDate])->count(),
            'gender_distribution' => Member::select('gender', DB::raw('count(*) as count'))
                ->groupBy('gender')->get(),
            'age_distribution' => $this->getAgeDistribution(),
            'marital_status' => Member::select('marital_status', DB::raw('count(*) as count'))
                ->groupBy('marital_status')->get(),
        ];

        return view('reports.membership', compact('data', 'startDate', 'endDate'));
    }

    public function financial(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $data = [
            'total_donations' => Donation::whereBetween('donation_date', [$startDate, $endDate])
                ->where('status', 'completed')->sum('amount'),
            'total_expenses' => Expense::whereBetween('expense_date', [$startDate, $endDate])
                ->where('status', 'paid')->sum('amount'),
            'donations_by_category' => Donation::with('category')
                ->whereBetween('donation_date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->select('donation_category_id', DB::raw('SUM(amount) as total'))
                ->groupBy('donation_category_id')->get(),
            'expenses_by_category' => Expense::with('category')
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->where('status', 'paid')
                ->select('expense_category_id', DB::raw('SUM(amount) as total'))
                ->groupBy('expense_category_id')->get(),
            'top_donors' => Donation::with('member')
                ->whereBetween('donation_date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->select('member_id', DB::raw('SUM(amount) as total'))
                ->groupBy('member_id')
                ->orderByDesc('total')
                ->take(10)->get(),
        ];

        $data['net_income'] = $data['total_donations'] - $data['total_expenses'];

        return view('reports.financial', compact('data', 'startDate', 'endDate'));
    }

    public function attendance(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $data = [
            'total_attendance' => AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])->count(),
            'average_attendance' => AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
                ->select(DB::raw('AVG(daily_count) as average'))
                ->from(DB::raw('(SELECT attendance_date, COUNT(*) as daily_count FROM attendance_records GROUP BY attendance_date) as daily_attendance'))
                ->value('average'),
            'attendance_by_service' => AttendanceRecord::with('service')
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->select('service_id', DB::raw('COUNT(*) as count'))
                ->groupBy('service_id')->get(),
            'attendance_trend' => AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
                ->select('attendance_date', DB::raw('COUNT(*) as count'))
                ->groupBy('attendance_date')
                ->orderBy('attendance_date')->get(),
        ];

        return view('reports.attendance', compact('data', 'startDate', 'endDate'));
    }

    private function getAgeDistribution()
    {
        // SQLite compatible age distribution
        $members = Member::whereNotNull('date_of_birth')->get();
        
        $distribution = [
            'Under 18' => 0,
            '18-30' => 0,
            '31-50' => 0,
            'Over 50' => 0,
        ];
        
        foreach ($members as $member) {
            $age = Carbon::parse($member->date_of_birth)->age;
            if ($age < 18) {
                $distribution['Under 18']++;
            } elseif ($age >= 18 && $age <= 30) {
                $distribution['18-30']++;
            } elseif ($age >= 31 && $age <= 50) {
                $distribution['31-50']++;
            } else {
                $distribution['Over 50']++;
            }
        }
        
        return collect($distribution)->map(function($count, $group) {
            return (object)['age_group' => $group, 'count' => $count];
        })->values();
    }

    // Comprehensive Financial Reports
    public function monthlyStatement(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $data = $this->financialReportService->generateMonthlyStatement($month, $year);

        return view('reports.financial.monthly', compact('data', 'month', 'year'));
    }

    public function annualReport(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $data = $this->financialReportService->generateAnnualReport($year);

        return view('reports.financial.annual', compact('data', 'year'));
    }

    public function customPeriodReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $data = $this->financialReportService->generateCustomReport($startDate, $endDate);

        return view('reports.financial.custom', compact('data', 'startDate', 'endDate'));
    }

    public function departmentAnalysis(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $data = $this->financialReportService->generateDepartmentReport($startDate, $endDate);

        return view('reports.financial.departments', compact('data', 'startDate', 'endDate'));
    }

    public function trendAnalysis(Request $request)
    {
        $months = $request->input('months', 12);

        $data = $this->financialReportService->generateTrendAnalysis($months);

        return view('reports.financial.trends', compact('data', 'months'));
    }

    public function budgetVsActual(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $data = $this->financialReportService->generateBudgetVsActual($year);

        return view('reports.financial.budget-vs-actual', compact('data', 'year'));
    }

    // Export Reports
    public function exportMonthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $data = $this->financialReportService->generateMonthlyStatement($month, $year);

        return response()->json($data);
    }

    public function exportAnnual(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $data = $this->financialReportService->generateAnnualReport($year);

        return response()->json($data);
    }
}
