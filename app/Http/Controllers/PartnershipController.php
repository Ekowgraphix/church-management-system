<?php

namespace App\Http\Controllers;

use App\Models\Partnership;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    public function index()
    {
        $partnerships = Partnership::latest()->paginate(20);
        return view('partnerships.index', compact('partnerships'));
    }

    public function create()
    {
        return view('partnerships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_name' => 'required|string|max:150',
            'contact' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'partnership_type' => 'required|string|max:100',
            'pledged_amount' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'renewal_date' => 'nullable|date',
            'status' => 'required|in:active,expired,pending',
            'notes' => 'nullable|string',
        ]);

        Partnership::create($validated);

        return redirect()->route('partnerships.index')
            ->with('success', 'Partnership created successfully!');
    }
    
    public function report()
    {
        $partnerships = Partnership::all();
        $totalPledged = Partnership::sum('pledged_amount');
        $totalPaid = Partnership::sum('amount_paid');
        $active = Partnership::where('status', 'active')->count();
        
        return view('partnerships.report', compact('partnerships', 'totalPledged', 'totalPaid', 'active'));
    }
    
    public function addPayment(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);
        
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0',
        ]);
        
        $partnership->amount_paid += $validated['payment_amount'];
        $partnership->save();
        
        return back()->with('success', 'Payment recorded successfully!');
    }

    public function show(Partnership $partnership)
    {
        return view('partnerships.show', compact('partnership'));
    }

    public function edit(Partnership $partnership)
    {
        return view('partnerships.edit', compact('partnership'));
    }

    public function update(Request $request, Partnership $partnership)
    {
        $validated = $request->validate([
            'partner_name' => 'required|string|max:150',
            'contact' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'partnership_type' => 'required|string|max:100',
            'pledged_amount' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'renewal_date' => 'nullable|date',
            'status' => 'required|in:active,expired,pending',
            'notes' => 'nullable|string',
        ]);

        $partnership->update($validated);

        return redirect()->route('partnerships.index')
            ->with('success', 'Partnership updated successfully!');
    }

    public function destroy(Partnership $partnership)
    {
        $partnership->delete();
        
        return redirect()->route('partnerships.index')
            ->with('success', 'Partnership deleted successfully!');
    }
}
