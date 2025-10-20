<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Expense;
use App\Models\Budget;
use App\Models\Department;
use App\Models\ExpenseCategory;
use App\Models\Pledge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReportService
{
    // Monthly Financial Statement
    public function generateMonthlyStatement($month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        return [
            'period' => $startDate->format('F Y'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'income' => $this->getIncomeData($startDate, $endDate),
            'expenses' => $this->getExpenseData($startDate, $endDate),
            'pledges' => $this->getPledgeData($startDate, $endDate),
            'summary' => $this->getSummary($startDate, $endDate),
        ];
    }

    // Annual Report
    public function generateAnnualReport($year)
    {
        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = Carbon::create($year, 12, 31)->endOfYear();

        return [
            'year' => $year,
            'income' => $this->getIncomeData($startDate, $endDate),
            'expenses' => $this->getExpenseData($startDate, $endDate),
            'pledges' => $this->getPledgeData($startDate, $endDate),
            'monthly_trend' => $this->getMonthlyTrend($year),
            'summary' => $this->getSummary($startDate, $endDate),
            'comparisons' => $this->getYearComparison($year),
        ];
    }

    // Custom Period Report
    public function generateCustomReport($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return [
            'period' => $start->format('M d, Y') . ' - ' . $end->format('M d, Y'),
            'start_date' => $start,
            'end_date' => $end,
            'income' => $this->getIncomeData($start, $end),
            'expenses' => $this->getExpenseData($start, $end),
            'pledges' => $this->getPledgeData($start, $end),
            'daily_trend' => $this->getDailyTrend($start, $end),
            'summary' => $this->getSummary($start, $end),
        ];
    }

    // Department-wise Analysis
    public function generateDepartmentReport($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $departments = Department::with(['expenses' => function($q) use ($start, $end) {
            $q->whereBetween('expense_date', [$start, $end]);
        }, 'budgets'])->get();

        $report = [];
        foreach ($departments as $dept) {
            $budgetAllocated = $dept->budgets()
                ->where('start_date', '<=', $end)
                ->where('end_date', '>=', $start)
                ->sum('allocated_amount');

            $totalSpent = $dept->expenses->sum('amount');

            $report[] = [
                'department' => $dept->department_name,
                'budget_allocated' => $budgetAllocated,
                'total_spent' => $totalSpent,
                'remaining' => $budgetAllocated - $totalSpent,
                'utilization' => $budgetAllocated > 0 ? round(($totalSpent / $budgetAllocated) * 100, 2) : 0,
                'expense_count' => $dept->expenses->count(),
            ];
        }

        return [
            'period' => $start->format('M d, Y') . ' - ' . $end->format('M d, Y'),
            'departments' => $report,
            'total_budget' => array_sum(array_column($report, 'budget_allocated')),
            'total_spent' => array_sum(array_column($report, 'total_spent')),
        ];
    }

    // Trend Analysis
    public function generateTrendAnalysis($months = 12)
    {
        $trends = [];
        $startDate = Carbon::now()->subMonths($months);

        for ($i = 0; $i < $months; $i++) {
            $monthStart = Carbon::now()->subMonths($months - $i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($months - $i)->endOfMonth();

            $income = Donation::whereBetween('donation_date', [$monthStart, $monthEnd])
                ->where('status', 'completed')
                ->sum('amount');

            $expenses = Expense::whereBetween('expense_date', [$monthStart, $monthEnd])
                ->where('status', 'approved')
                ->sum('amount');

            $trends[] = [
                'month' => $monthStart->format('M Y'),
                'income' => $income,
                'expenses' => $expenses,
                'net' => $income - $expenses,
            ];
        }

        return [
            'period' => $months . ' months',
            'trends' => $trends,
            'average_income' => round(array_sum(array_column($trends, 'income')) / $months, 2),
            'average_expenses' => round(array_sum(array_column($trends, 'expenses')) / $months, 2),
            'growth_rate' => $this->calculateGrowthRate($trends),
        ];
    }

    // Budget vs Actual Report
    public function generateBudgetVsActual($year)
    {
        $categories = ExpenseCategory::with(['budgets' => function($q) use ($year) {
            $q->where('fiscal_year', $year);
        }])->get();

        $report = [];
        foreach ($categories as $category) {
            $budgetAmount = $category->budgets->sum('allocated_amount');
            $actualAmount = Expense::where('expense_category_id', $category->id)
                ->whereYear('expense_date', $year)
                ->where('status', 'approved')
                ->sum('amount');

            $report[] = [
                'category' => $category->category_name,
                'budgeted' => $budgetAmount,
                'actual' => $actualAmount,
                'variance' => $budgetAmount - $actualAmount,
                'variance_percentage' => $budgetAmount > 0 ? round((($actualAmount - $budgetAmount) / $budgetAmount) * 100, 2) : 0,
            ];
        }

        return [
            'year' => $year,
            'categories' => $report,
            'total_budgeted' => array_sum(array_column($report, 'budgeted')),
            'total_actual' => array_sum(array_column($report, 'actual')),
        ];
    }

    // Helper Methods
    private function getIncomeData($startDate, $endDate)
    {
        $donations = Donation::whereBetween('donation_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        return [
            'total_donations' => $donations->sum('amount'),
            'donation_count' => $donations->count(),
            'average_donation' => $donations->count() > 0 ? round($donations->avg('amount'), 2) : 0,
            'by_category' => $donations->groupBy('donation_category_id')->map(function($items) {
                return [
                    'category' => $items->first()->category->category_name ?? 'Uncategorized',
                    'amount' => $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })->values(),
        ];
    }

    private function getExpenseData($startDate, $endDate)
    {
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->get();

        return [
            'total_expenses' => $expenses->sum('amount'),
            'expense_count' => $expenses->count(),
            'average_expense' => $expenses->count() > 0 ? round($expenses->avg('amount'), 2) : 0,
            'by_category' => $expenses->groupBy('expense_category_id')->map(function($items) {
                return [
                    'category' => $items->first()->category->category_name ?? 'Uncategorized',
                    'amount' => $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })->values(),
        ];
    }

    private function getPledgeData($startDate, $endDate)
    {
        $pledges = Pledge::whereBetween('pledge_date', [$startDate, $endDate])->get();

        return [
            'total_pledged' => $pledges->sum('pledge_amount'),
            'total_collected' => $pledges->sum('amount_paid'),
            'pledge_count' => $pledges->count(),
            'fulfillment_rate' => $pledges->sum('pledge_amount') > 0 ? 
                round(($pledges->sum('amount_paid') / $pledges->sum('pledge_amount')) * 100, 2) : 0,
        ];
    }

    private function getSummary($startDate, $endDate)
    {
        $income = Donation::whereBetween('donation_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->sum('amount');

        return [
            'total_income' => $income,
            'total_expenses' => $expenses,
            'net_income' => $income - $expenses,
            'expense_ratio' => $income > 0 ? round(($expenses / $income) * 100, 2) : 0,
        ];
    }

    private function getMonthlyTrend($year)
    {
        $trends = [];
        for ($month = 1; $month <= 12; $month++) {
            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = Carbon::create($year, $month, 1)->endOfMonth();

            $income = Donation::whereBetween('donation_date', [$start, $end])
                ->where('status', 'completed')
                ->sum('amount');

            $expenses = Expense::whereBetween('expense_date', [$start, $end])
                ->where('status', 'approved')
                ->sum('amount');

            $trends[] = [
                'month' => $start->format('M'),
                'income' => $income,
                'expenses' => $expenses,
                'net' => $income - $expenses,
            ];
        }

        return $trends;
    }

    private function getDailyTrend($startDate, $endDate)
    {
        $trends = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $income = Donation::whereDate('donation_date', $current)
                ->where('status', 'completed')
                ->sum('amount');

            $expenses = Expense::whereDate('expense_date', $current)
                ->where('status', 'approved')
                ->sum('amount');

            if ($income > 0 || $expenses > 0) {
                $trends[] = [
                    'date' => $current->format('M d'),
                    'income' => $income,
                    'expenses' => $expenses,
                ];
            }

            $current->addDay();
        }

        return $trends;
    }

    private function getYearComparison($year)
    {
        $currentYear = $this->getYearSummary($year);
        $previousYear = $this->getYearSummary($year - 1);

        return [
            'current_year' => $currentYear,
            'previous_year' => $previousYear,
            'income_growth' => $previousYear['income'] > 0 ? 
                round((($currentYear['income'] - $previousYear['income']) / $previousYear['income']) * 100, 2) : 0,
            'expense_growth' => $previousYear['expenses'] > 0 ? 
                round((($currentYear['expenses'] - $previousYear['expenses']) / $previousYear['expenses']) * 100, 2) : 0,
        ];
    }

    private function getYearSummary($year)
    {
        $start = Carbon::create($year, 1, 1);
        $end = Carbon::create($year, 12, 31);

        return [
            'income' => Donation::whereBetween('donation_date', [$start, $end])
                ->where('status', 'completed')
                ->sum('amount'),
            'expenses' => Expense::whereBetween('expense_date', [$start, $end])
                ->where('status', 'approved')
                ->sum('amount'),
        ];
    }

    private function calculateGrowthRate($trends)
    {
        if (count($trends) < 2) return 0;

        $first = $trends[0]['income'];
        $last = $trends[count($trends) - 1]['income'];

        return $first > 0 ? round((($last - $first) / $first) * 100, 2) : 0;
    }
}
