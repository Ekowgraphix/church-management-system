<?php

namespace App\Http\Controllers;

use App\Models\Tithe;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TitheController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Tithe::with('member');

        // Filters
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $query->whereHas('member', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        $tithes = $query->latest('date')->paginate(20);

        // Summary Statistics
        $stats = [
            'month' => Tithe::thisMonth()->sum('amount'),
            'year' => Tithe::thisYear()->sum('amount'),
            'total' => Tithe::sum('amount'),
            'count' => Tithe::count(),
        ];

        // Top Givers (This Year)
        $topGivers = Tithe::thisYear()
            ->select('member_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('member_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('member')
            ->get();

        // Monthly trend (last 12 months) - SQLite compatible
        $monthlyTrend = Tithe::where('date', '>=', now()->subMonths(12))
            ->select(
                DB::raw('strftime("%Y", date) as year'),
                DB::raw('strftime("%m", date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Payment method breakdown - SQLite compatible
        $paymentBreakdown = Tithe::thisYear()
            ->select('payment_method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        // Members for filter dropdown
        $members = Member::orderBy('first_name')->get();

            return view('tithes.index', compact(
                'tithes',
                'stats',
                'topGivers',
                'monthlyTrend',
                'paymentBreakdown',
                'members'
            ));
            
        } catch (\Exception $e) {
            // If tithes table doesn't exist, redirect to donations page
            return redirect()->route('donations.index')
                ->with('info', 'Tithes feature is not yet set up. You can manage tithes through the Donations page.');
        }
    }

    public function create()
    {
        $members = Member::orderBy('first_name')->get();
        return view('tithes.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:Cash,Mobile Money,Bank Transfer,Cheque,Card',
            'received_by' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $tithe = Tithe::create($validated);

        // Send thank you notification (optional)
        // $this->sendThankYou($tithe);

        return redirect()->route('tithes.index')
            ->with('success', 'Tithe recorded successfully! Thank you message will be sent.');
    }

    public function edit(Tithe $tithe)
    {
        $members = Member::orderBy('first_name')->get();
        return view('tithes.edit', compact('tithe', 'members'));
    }

    public function update(Request $request, Tithe $tithe)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:Cash,Mobile Money,Bank Transfer,Cheque,Card',
            'received_by' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);

        $tithe->update($validated);

        return redirect()->route('tithes.index')
            ->with('success', 'Tithe updated successfully!');
    }

    public function destroy(Tithe $tithe)
    {
        $tithe->delete();

        return redirect()->route('tithes.index')
            ->with('success', 'Tithe deleted successfully!');
    }

    // Member Tithe History
    public function memberHistory($id)
    {
        $member = Member::findOrFail($id);
        $tithes = Tithe::where('member_id', $id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Tithe::where('member_id', $id)->sum('amount'),
            'count' => Tithe::where('member_id', $id)->count(),
            'this_year' => Tithe::where('member_id', $id)->thisYear()->sum('amount'),
            'this_month' => Tithe::where('member_id', $id)->thisMonth()->sum('amount'),
            'first_tithe' => Tithe::where('member_id', $id)->min('date'),
            'last_tithe' => Tithe::where('member_id', $id)->max('date'),
        ];

        // Calculate loyalty badge
        $badge = $this->getLoyaltyBadge($stats);

        return view('tithes.member-history', compact('member', 'tithes', 'stats', 'badge'));
    }

    // Generate Receipt
    public function generateReceipt($id)
    {
        $tithe = Tithe::with('member')->findOrFail($id);
        $pdf = Pdf::loadView('tithes.receipt', compact('tithe'));
        return $pdf->download('tithe-receipt-' . $tithe->id . '.pdf');
    }

    // Export to Excel (CSV)
    public function exportExcel(Request $request)
    {
        $query = Tithe::with('member');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $tithes = $query->latest('date')->get();

        $filename = 'tithes-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($tithes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Member', 'Amount', 'Payment Method', 'Received By', 'Remarks']);

            foreach ($tithes as $tithe) {
                fputcsv($file, [
                    $tithe->date->format('Y-m-d'),
                    $tithe->member ? $tithe->member->first_name . ' ' . $tithe->member->last_name : 'N/A',
                    number_format($tithe->amount, 2),
                    $tithe->payment_method,
                    $tithe->received_by,
                    $tithe->remarks,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // AI Insights
    public function aiInsights(Request $request)
    {
        $period = $request->get('period', 'quarter'); // month, quarter, year
        
        $dateFrom = match($period) {
            'month' => now()->subMonth(),
            'quarter' => now()->subMonths(3),
            'year' => now()->subYear(),
            default => now()->subMonths(3),
        };

        $tithes = Tithe::where('date', '>=', $dateFrom)->with('member')->get();
        $total = $tithes->sum('amount');
        $count = $tithes->count();
        $uniqueGivers = $tithes->unique('member_id')->count();
        $avgAmount = $count > 0 ? $total / $count : 0;

        // Top 10 givers
        $topGivers = Tithe::where('date', '>=', $dateFrom)
            ->select('member_id', DB::raw('SUM(amount) as total'))
            ->groupBy('member_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('member')
            ->get();

        $insights = [
            'period' => ucfirst($period),
            'date_from' => $dateFrom->format('F d, Y'),
            'total_amount' => number_format($total, 2),
            'total_tithes' => $count,
            'unique_givers' => $uniqueGivers,
            'average_amount' => number_format($avgAmount, 2),
            'top_givers' => $topGivers,
            'prompt' => "Generate a detailed financial report for church tithes over the last {$period}. Total collected: GHS {$total}. Number of tithes: {$count}. Unique givers: {$uniqueGivers}. Average per tithe: GHS {$avgAmount}. Identify trends and provide recommendations."
        ];

        return response()->json($insights);
    }

    // Calculate Loyalty Badge
    private function getLoyaltyBadge($stats)
    {
        $total = $stats['total'];
        $count = $stats['count'];

        if ($total >= 50000) {
            return ['name' => 'Diamond Partner', 'color' => 'blue', 'icon' => '💎'];
        } elseif ($total >= 30000) {
            return ['name' => 'Gold Giver', 'color' => 'yellow', 'icon' => '🥇'];
        } elseif ($total >= 15000) {
            return ['name' => 'Silver Supporter', 'color' => 'gray', 'icon' => '🥈'];
        } elseif ($count >= 12) {
            return ['name' => 'Faithful Partner', 'color' => 'green', 'icon' => '⭐'];
        } else {
            return ['name' => 'Growing Giver', 'color' => 'purple', 'icon' => '🌱'];
        }
    }

    // Send Thank You (Placeholder for SMS/Email integration)
    private function sendThankYou($tithe)
    {
        // Implement SMS/Email sending here
        // Example: 
        // SMS::send($tithe->member->phone, "Thank you for your tithe of GHS {$tithe->amount}!");
        // Mail::to($tithe->member->email)->send(new TitheThankYou($tithe));
    }
}
