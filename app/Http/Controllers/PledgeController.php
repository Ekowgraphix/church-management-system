<?php

namespace App\Http\Controllers;

use App\Models\Pledge;
use App\Models\PledgeCampaign;
use App\Models\PledgePayment;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PledgeController extends Controller
{
    // Pledge Campaigns
    public function campaigns()
    {
        $campaigns = PledgeCampaign::withCount('pledges')->latest()->paginate(20);
        
        $stats = [
            'active_campaigns' => PledgeCampaign::where('status', 'active')->count(),
            'total_pledged' => PledgeCampaign::sum('pledged_amount'),
            'total_collected' => PledgeCampaign::sum('collected_amount'),
            'fulfillment_rate' => PledgeCampaign::avg('fulfilled_pledges'),
        ];

        return view('pledges.campaigns', compact('campaigns', 'stats'));
    }

    public function createCampaign()
    {
        return view('pledges.campaigns-create');
    }

    public function storeCampaign(Request $request)
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'send_reminders' => 'boolean',
            'reminder_frequency_days' => 'required|integer|min:1|max:365',
            'notes' => 'nullable|string',
        ]);

        PledgeCampaign::create($validated);

        return redirect()->route('pledge-campaigns.index')
            ->with('success', 'Pledge campaign created successfully!');
    }

    public function showCampaign(PledgeCampaign $campaign)
    {
        $campaign->load(['pledges.member', 'pledges.payments']);
        
        $stats = [
            'total_pledges' => $campaign->pledges->count(),
            'active_pledges' => $campaign->pledges->where('status', 'active')->count(),
            'fulfilled_pledges' => $campaign->pledges->where('status', 'fulfilled')->count(),
            'total_pledged' => $campaign->pledges->sum('pledge_amount'),
            'total_collected' => $campaign->pledges->sum('amount_paid'),
        ];

        return view('pledges.campaigns-show', compact('campaign', 'stats'));
    }

    public function campaignReport(PledgeCampaign $campaign)
    {
        $pledges = $campaign->pledges()->with('member', 'payments')->get();
        
        $topDonors = $campaign->pledges()
            ->with('member')
            ->orderBy('amount_paid', 'desc')
            ->take(10)
            ->get();

        return view('pledges.campaigns-report', compact('campaign', 'pledges', 'topDonors'));
    }

    // Regular Pledges
    public function index()
    {
        $pledges = Pledge::with(['member', 'campaign'])->latest()->paginate(20);
        
        $stats = [
            'active_pledges' => Pledge::where('status', 'active')->count(),
            'total_pledged' => Pledge::sum('pledge_amount'),
            'total_paid' => Pledge::sum('amount_paid'),
            'completion_rate' => Pledge::avg('amount_paid') / (Pledge::avg('pledge_amount') ?: 1) * 100,
        ];

        return view('pledges.index', compact('pledges', 'stats'));
    }

    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        $campaigns = PledgeCampaign::active()->orderBy('campaign_name')->get();
        
        return view('pledges.create', compact('members', 'campaigns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'campaign_id' => 'nullable|exists:pledge_campaigns,id',
            'pledge_amount' => 'required|numeric|min:0.01',
            'pledge_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'frequency' => 'nullable|in:one-time,weekly,monthly,quarterly,yearly',
            'notes' => 'nullable|string',
        ]);

        $validated['pledge_number'] = 'PLG-' . time();
        $validated['status'] = 'active';
        $validated['amount_paid'] = 0;

        $pledge = Pledge::create($validated);

        // Update campaign totals
        if ($pledge->campaign_id) {
            $campaign = $pledge->campaign;
            $campaign->increment('pledged_amount', $pledge->pledge_amount);
            $campaign->increment('total_pledges');
        }

        return redirect()->route('pledges.show', $pledge)
            ->with('success', 'Pledge created successfully!');
    }

    public function show(Pledge $pledge)
    {
        $pledge->load(['member', 'campaign', 'payments']);
        
        return view('pledges.show', compact('pledge'));
    }

    public function edit(Pledge $pledge)
    {
        $members = Member::active()->orderBy('first_name')->get();
        $campaigns = PledgeCampaign::active()->orderBy('campaign_name')->get();
        
        return view('pledges.edit', compact('pledge', 'members', 'campaigns'));
    }

    public function update(Request $request, Pledge $pledge)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'campaign_id' => 'nullable|exists:pledge_campaigns,id',
            'pledge_amount' => 'required|numeric|min:0.01',
            'pledge_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'frequency' => 'nullable|in:one-time,weekly,monthly,quarterly,yearly',
            'status' => 'required|in:active,fulfilled,cancelled',
            'notes' => 'nullable|string',
        ]);

        $pledge->update($validated);

        return redirect()->route('pledges.show', $pledge)
            ->with('success', 'Pledge updated successfully!');
    }

    public function destroy(Pledge $pledge)
    {
        $pledge->delete();
        
        return redirect()->route('pledges.index')
            ->with('success', 'Pledge deleted successfully!');
    }

    public function recordPayment(Request $request, Pledge $pledge)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payment = PledgePayment::create([
            'pledge_id' => $pledge->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
        ]);

        // Update pledge amount paid
        $pledge->increment('amount_paid', $validated['amount']);

        // Check if fulfilled
        if ($pledge->amount_paid >= $pledge->pledge_amount) {
            $pledge->update(['status' => 'fulfilled']);
            
            // Update campaign fulfilled count
            if ($pledge->campaign_id) {
                $pledge->campaign->increment('fulfilled_pledges');
            }
        }

        // Update campaign collected amount
        if ($pledge->campaign_id) {
            $pledge->campaign->increment('collected_amount', $validated['amount']);
        }

        return back()->with('success', 'Payment recorded successfully!');
    }
}
