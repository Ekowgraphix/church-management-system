<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaign;
use App\Models\EmailCampaignRecipient;
use App\Models\Member;
use App\Mail\CampaignEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::with('creator')->latest()->paginate(20);
        return view('email-campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $members = Member::where('membership_status', 'active')
            ->whereNotNull('email')
            ->get();
        return view('email-campaigns.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'scheduled_at' => 'nullable|date|after:now',
            'recipients' => 'required|array|min:1',
        ]);

        $campaign = EmailCampaign::create([
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'status' => $validated['scheduled_at'] ? 'scheduled' : 'draft',
            'created_by' => auth()->id(),
        ]);

        $members = Member::whereIn('id', $validated['recipients'])->get();
        
        foreach ($members as $member) {
            if ($member->email) {
                EmailCampaignRecipient::create([
                    'email_campaign_id' => $campaign->id,
                    'member_id' => $member->id,
                    'email' => $member->email,
                    'status' => 'pending',
                ]);
            }
        }

        $campaign->update(['total_recipients' => $campaign->recipients()->count()]);

        return redirect()->route('email-campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully.');
    }

    public function show(EmailCampaign $emailCampaign)
    {
        $emailCampaign->load(['recipients.member']);
        return view('email-campaigns.show', compact('emailCampaign'));
    }

    public function edit(EmailCampaign $emailCampaign)
    {
        return view('email-campaigns.edit', compact('emailCampaign'));
    }

    public function update(Request $request, EmailCampaign $emailCampaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,scheduled,sent,cancelled',
        ]);

        $emailCampaign->update($validated);

        return redirect()->route('email-campaigns.show', $emailCampaign)
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(EmailCampaign $emailCampaign)
    {
        $emailCampaign->delete();

        return redirect()->route('email-campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function send(EmailCampaign $emailCampaign)
    {
        if ($emailCampaign->status !== 'draft' && $emailCampaign->status !== 'scheduled') {
            return back()->with('error', 'Campaign cannot be sent.');
        }

        $emailCampaign->update([
            'status' => 'sending',
            'sent_at' => now(),
        ]);

        // In production, use queues for this
        foreach ($emailCampaign->recipients as $recipient) {
            try {
                // Actually send email
                Mail::to($recipient->email)->send(
                    new CampaignEmail($emailCampaign, $recipient->member)
                );
                
                $recipient->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                
                $emailCampaign->increment('sent_count');
            } catch (\Exception $e) {
                $recipient->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }
        }

        $emailCampaign->update(['status' => 'sent']);

        return redirect()->route('email-campaigns.show', $emailCampaign)
            ->with('success', 'Campaign sent successfully.');
    }
}
