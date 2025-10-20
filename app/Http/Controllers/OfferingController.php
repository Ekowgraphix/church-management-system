<?php

namespace App\Http\Controllers;

use App\Models\Offering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OfferingController extends Controller
{
    public function index(Request $request)
    {
        // Check if offerings table exists, otherwise redirect to donations
        try {
            $query = Offering::query();

            // Filters
            if ($request->filled('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('date', '<=', $request->date_to);
            }
            if ($request->filled('service_name')) {
                $query->where('service_name', 'like', '%' . $request->service_name . '%');
            }
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }
            if ($request->filled('collected_by')) {
                $query->where('collected_by', 'like', '%' . $request->collected_by . '%');
            }

            $offerings = $query->latest('date')->paginate(20);

            // Summary Statistics
            $stats = [
                'today' => Offering::today()->sum('amount'),
                'week' => Offering::thisWeek()->sum('amount'),
                'month' => Offering::thisMonth()->sum('amount'),
                'year' => Offering::thisYear()->sum('amount'),
            ];

            // Category breakdown for this month
            $categoryBreakdown = Offering::thisMonth()
                ->select('category', DB::raw('SUM(amount) as total'))
                ->groupBy('category')
                ->get();

            // Monthly trend (last 12 months) - SQLite compatible
            $monthlyTrend = Offering::where('date', '>=', now()->subMonths(12))
                ->select(
                    DB::raw('strftime("%Y", date) as year'),
                    DB::raw('strftime("%m", date) as month'),
                    DB::raw('SUM(amount) as total')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
            ->orderBy('month')
            ->get();

            // Service type analysis
            $serviceAnalysis = Offering::thisYear()
                ->select('service_name', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
                ->groupBy('service_name')
                ->orderByDesc('total')
                ->get();

            return view('offerings.index', compact(
                'offerings',
                'stats',
                'categoryBreakdown',
                'monthlyTrend',
                'serviceAnalysis'
            ));
            
        } catch (\Exception $e) {
            // If offerings table doesn't exist, redirect to donations page
            return redirect()->route('donations.index')
                ->with('info', 'Offerings feature is not yet set up. You can manage offerings through the Donations page.');
        }
    }

    public function create()
    {
        try {
            // Check if table exists
            Offering::count();
            return view('offerings.create');
        } catch (\Exception $e) {
            return redirect()->route('donations.index')
                ->with('info', 'Offerings feature is not yet set up. You can manage offerings through the Donations page.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
            'date' => 'required|date',
            'service_name' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:Cash,Mobile Money,Bank Transfer,Cheque,Card',
            'collected_by' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

            Offering::create($validated);

            return redirect()->route('offerings.index')
                ->with('success', 'Offering recorded successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('donations.index')
                ->with('error', 'Offerings feature is not yet set up. You can manage offerings through the Donations page.');
        }
    }

    public function edit(Offering $offering)
    {
        return view('offerings.edit', compact('offering'));
    }

    public function update(Request $request, Offering $offering)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'service_name' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:Cash,Mobile Money,Bank Transfer,Cheque,Card',
            'collected_by' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $offering->update($validated);

        return redirect()->route('offerings.index')
            ->with('success', 'Offering updated successfully!');
    }

    public function destroy(Offering $offering)
    {
        $offering->delete();

        return redirect()->route('offerings.index')
            ->with('success', 'Offering deleted successfully!');
    }

    // Export to PDF
    public function exportPdf(Request $request)
    {
        $query = Offering::query();

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $offerings = $query->latest('date')->get();
        $total = $offerings->sum('amount');

        $pdf = Pdf::loadView('offerings.pdf', compact('offerings', 'total'));
        return $pdf->download('offerings-report-' . date('Y-m-d') . '.pdf');
    }

    // Export to Excel (CSV)
    public function exportExcel(Request $request)
    {
        $query = Offering::query();

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $offerings = $query->latest('date')->get();

        $filename = 'offerings-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($offerings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Service', 'Category', 'Amount', 'Payment Method', 'Collected By', 'Remarks']);

            foreach ($offerings as $offering) {
                fputcsv($file, [
                    $offering->date->format('Y-m-d'),
                    $offering->service_name,
                    $offering->category,
                    number_format($offering->amount, 2),
                    $offering->payment_method,
                    $offering->collected_by,
                    $offering->remarks,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Analytics API for charts - SQLite compatible
    public function analytics(Request $request)
    {
        $period = $request->get('period', 'year'); // month, year

        if ($period === 'month') {
            $data = Offering::thisMonth()
                ->select(
                    DB::raw('date(date) as date'),
                    DB::raw('SUM(amount) as total')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } else {
            $data = Offering::thisYear()
                ->select(
                    DB::raw('strftime("%m", date) as month'),
                    DB::raw('SUM(amount) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return response()->json($data);
    }

    // AI Summary
    public function aiSummary(Request $request)
    {
        $month = $request->get('month', now()->format('F Y'));
        
        $offerings = Offering::thisMonth()->get();
        $total = $offerings->sum('amount');
        $count = $offerings->count();
        $avgPerService = $count > 0 ? $total / $count : 0;
        
        $categoryBreakdown = Offering::thisMonth()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category');

        $summary = [
            'period' => $month,
            'total_amount' => number_format($total, 2),
            'total_services' => $count,
            'average_per_service' => number_format($avgPerService, 2),
            'breakdown' => $categoryBreakdown,
            'prompt' => "Generate a professional financial summary for church offerings in {$month}. Total collected: GHS {$total}. Number of services: {$count}. Average per service: GHS {$avgPerService}. Category breakdown: " . json_encode($categoryBreakdown)
        ];

        return response()->json($summary);
    }

    // Generate Receipt
    public function receipt(Offering $offering)
    {
        $pdf = Pdf::loadView('offerings.receipt', compact('offering'));
        return $pdf->download('offering-receipt-' . $offering->id . '.pdf');
    }
}
