<?php

namespace App\Http\Controllers;

use App\Models\Welfare;
use App\Models\WelfareFund;
use App\Models\WelfareRequest;
use App\Models\Member;
use Illuminate\Http\Request;

class WelfareController extends Controller
{
    public function index()
    {
        $funds = WelfareFund::latest()->paginate(20);
        $totalIncome = WelfareFund::where('type', 'income')->sum('amount');
        $totalExpense = WelfareFund::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;
        
        return view('welfare.index', compact('funds', 'totalIncome', 'totalExpense', 'balance'));
    }
    
    public function dashboard()
    {
        $totalIncome = WelfareFund::where('type', 'income')->sum('amount');
        $totalExpense = WelfareFund::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;
        
        $pendingRequests = WelfareRequest::where('status', 'pending')->count();
        $approvedRequests = WelfareRequest::where('status', 'approved')->count();
        $completedRequests = WelfareRequest::where('status', 'completed')->count();
        
        $recentFunds = WelfareFund::latest()->take(10)->get();
        $recentRequests = WelfareRequest::latest()->take(10)->get();
        
        return view('welfare.dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'pendingRequests',
            'approvedRequests',
            'completedRequests',
            'recentFunds',
            'recentRequests'
        ));
    }
    
    public function requests()
    {
        $requests = WelfareRequest::latest()->paginate(20);
        return view('welfare.requests', compact('requests'));
    }
    
    public function approveRequest($id)
    {
        $request = WelfareRequest::findOrFail($id);
        $request->update(['status' => 'approved']);
        
        return back()->with('success', 'Request approved successfully!');
    }
    
    public function declineRequest($id)
    {
        $request = WelfareRequest::findOrFail($id);
        $request->update(['status' => 'declined']);
        
        return back()->with('success', 'Request declined.');
    }

    public function create()
    {
        return view('welfare.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'approved_by' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
        ]);

        WelfareFund::create($validated);

        return redirect()->route('welfare.index')
            ->with('success', 'Welfare fund transaction added successfully!');
    }

    public function show($id)
    {
        $fund = WelfareFund::findOrFail($id);
        return view('welfare.show', compact('fund'));
    }

    public function edit($id)
    {
        $fund = WelfareFund::findOrFail($id);
        return view('welfare.edit', compact('fund'));
    }

    public function update(Request $request, $id)
    {
        $fund = WelfareFund::findOrFail($id);
        
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'approved_by' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
        ]);

        $fund->update($validated);

        return redirect()->route('welfare.index')
            ->with('success', 'Welfare fund transaction updated successfully!');
    }

    public function destroy($id)
    {
        $fund = WelfareFund::findOrFail($id);
        $fund->delete();
        
        return redirect()->route('welfare.index')
            ->with('success', 'Transaction deleted successfully!');
    }
}
