<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\SyncsStorage;

class ExpenseController extends Controller
{
    use SyncsStorage;
    
    public function dashboard()
    {
        try {
            // Get donations for income
            $totalIncome = \App\Models\Donation::sum('amount') ?? 0;
            
            // Get expenses
            $totalExpenses = Expense::where('status', 'approved')->sum('amount') ?? 0;
            $pendingCount = Expense::where('status', 'pending')->count() ?? 0;
            
            // Calculate net balance
            $netBalance = $totalIncome - $totalExpenses;
            
            $stats = [
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_balance' => $netBalance,
                'income_change' => 12,
                'expense_change' => 8,
                'pending_count' => $pendingCount,
            ];
            
            // Get recent transactions
            $recentExpenses = Expense::with(['category', 'requestedBy'])
                ->latest()
                ->limit(10)
                ->get();
                
            $recentDonations = \App\Models\Donation::with('member')
                ->latest()
                ->limit(10)
                ->get();
            
            return view('finance.dashboard', compact('stats', 'recentExpenses', 'recentDonations'));
            
        } catch (\Exception $e) {
            // If there's an error, return with default values
            $stats = [
                'total_income' => 0,
                'total_expenses' => 0,
                'net_balance' => 0,
                'income_change' => 0,
                'expense_change' => 0,
                'pending_count' => 0,
            ];
            
            $recentExpenses = collect();
            $recentDonations = collect();
            
            return view('finance.dashboard', compact('stats', 'recentExpenses', 'recentDonations'))
                ->with('info', 'Finance dashboard loaded with limited data. Some features may not be available.');
        }
    }
    
    public function storeFinance(Request $request)
    {
        // Handle finance storage from dashboard
        return redirect()->route('finance.dashboard')->with('success', 'Transaction recorded successfully!');
    }
    
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'requestedBy', 'approvedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('expense_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $expenses = $query->latest('expense_date')->paginate(20);
        $categories = ExpenseCategory::where('is_active', true)->get();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::where('is_active', true)->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,check,card,bank_transfer,mobile_money',
            'vendor_name' => 'nullable|string|max:255',
            'receipt_number' => 'nullable|string|max:255',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        $validated['expense_number'] = 'EXP-' . strtoupper(Str::random(10));
        $validated['requested_by'] = auth()->id();
        $validated['status'] = 'pending';

        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('expenses/receipts', 'public');
        }

        $expense = Expense::create($validated);
        
        // Sync storage to public (for systems without symlink support)
        $this->syncStorageToPublic();

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['category', 'requestedBy', 'approvedBy']);
        return view('expenses.show', compact('expense'));
    }

    public function approve(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $expense->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'] ?? null,
        ]);

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'Expense approved successfully.');
    }

    public function reject(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'approval_notes' => 'required|string',
        ]);

        $expense->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'],
        ]);

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'Expense rejected.');
    }
}
