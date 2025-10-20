<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['member', 'category', 'recordedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('member', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('donation_category_id', $request->category_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('donation_date', [$request->start_date, $request->end_date]);
        }

        $donations = $query->latest('donation_date')->paginate(20);
        $categories = DonationCategory::where('is_active', true)->get();

        $stats = [
            'total_donations' => Donation::where('status', 'completed')->sum('amount'),
            'monthly_donations' => Donation::whereMonth('donation_date', Carbon::now()->month)
                ->where('status', 'completed')->sum('amount'),
            'total_donors' => Donation::distinct('member_id')->count('member_id'),
        ];

        return view('donations.index', compact('donations', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = DonationCategory::where('is_active', true)->get();
        $members = Member::active()->get();

        return view('donations.create', compact('categories', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'donation_category_id' => 'required|exists:donation_categories,id',
            'amount' => 'required|numeric|min:0',
            'donation_date' => 'required|date',
            'payment_method' => 'required|in:cash,check,card,bank_transfer,mobile_money,online',
            'reference_number' => 'nullable|string|max:255',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly,yearly',
            'recurring_end_date' => 'nullable|date',
            'is_anonymous' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['transaction_id'] = 'DON-' . strtoupper(Str::random(10));
        $validated['receipt_number'] = 'RCP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $validated['recorded_by'] = auth()->id() ?? \App\Models\User::first()->id ?? null;
        $validated['status'] = 'completed';

        $donation = Donation::create($validated);

        return redirect()->route('donations.index')
            ->with('success', 'Donation recorded successfully.');
    }

    public function show(Donation $donation)
    {
        $donation->load(['member', 'category', 'recordedBy']);

        return view('donations.show', compact('donation'));
    }

    public function edit(Donation $donation)
    {
        $categories = DonationCategory::where('is_active', true)->get();
        $members = Member::active()->get();

        return view('donations.edit', compact('donation', 'categories', 'members'));
    }

    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'donation_category_id' => 'required|exists:donation_categories,id',
            'amount' => 'required|numeric|min:0',
            'donation_date' => 'required|date',
            'payment_method' => 'required|in:cash,check,card,bank_transfer,mobile_money,online',
            'reference_number' => 'nullable|string|max:255',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly,yearly',
            'recurring_end_date' => 'nullable|date',
            'is_anonymous' => 'boolean',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        $donation->update($validated);

        return redirect()->route('donations.index')
            ->with('success', 'Donation updated successfully.');
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();

        return redirect()->route('donations.index')
            ->with('success', 'Donation deleted successfully.');
    }

    public function receipt(Donation $donation)
    {
        $donation->load(['member', 'category']);

        return view('donations.receipt', compact('donation'));
    }
}
