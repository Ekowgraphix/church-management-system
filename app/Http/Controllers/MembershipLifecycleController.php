<?php

namespace App\Http\Controllers;

use App\Traits\SyncsStorage;
use App\Models\Member;
use App\Models\MemberOnboarding;
use App\Models\MembershipClass;
use App\Models\MemberTransfer;
use App\Models\MemberStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MembershipLifecycleController extends Controller
{
    use SyncsStorage;
    // Onboarding Dashboard
    public function onboarding()
    {
        $onboardings = MemberOnboarding::with(['member', 'mentor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'inquiry' => MemberOnboarding::where('stage', 'inquiry')->count(),
            'visitor' => MemberOnboarding::where('stage', 'visitor')->count(),
            'in_class' => MemberOnboarding::where('stage', 'new_member_class')->count(),
            'baptism' => MemberOnboarding::where('stage', 'baptism')->count(),
            'completed' => MemberOnboarding::where('stage', 'membership_complete')->count(),
        ];

        return view('membership.onboarding', compact('onboardings', 'stats'));
    }

    // Update Onboarding Stage
    public function updateStage(Request $request, Member $member)
    {
        $validated = $request->validate([
            'stage' => 'required|in:inquiry,visitor,new_member_class,baptism,membership_complete',
            'date_field' => 'required|string',
            'date_value' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $onboarding = $member->onboarding ?? $member->onboarding()->create(['stage' => 'inquiry']);
        
        $onboarding->update([
            'stage' => $validated['stage'],
            $validated['date_field'] => $validated['date_value'],
            'notes' => $validated['notes'] ?? $onboarding->notes,
        ]);

        // Update member status if completing onboarding
        if ($validated['stage'] === 'membership_complete') {
            $member->update(['membership_status' => 'active']);
            
            $member->statusHistory()->create([
                'previous_status' => $member->membership_status,
                'new_status' => 'active',
                'reason' => 'Completed onboarding process',
                'changed_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Onboarding stage updated successfully!');
    }

    // Membership Classes
    public function classes()
    {
        $classes = MembershipClass::withCount('members')->latest()->paginate(20);
        return view('membership.classes', compact('classes'));
    }

    public function createClass()
    {
        return view('membership.classes-create');
    }

    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'instructor' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'requirements' => 'nullable|string',
        ]);

        MembershipClass::create($validated);

        return redirect()->route('membership.classes')
            ->with('success', 'Membership class created successfully!');
    }

    public function showClass(MembershipClass $class)
    {
        $class->load('members');
        $availableMembers = Member::active()
            ->whereDoesntHave('membershipClasses', function($q) use ($class) {
                $q->where('membership_class_id', $class->id);
            })
            ->orderBy('first_name')
            ->get();

        return view('membership.classes-show', compact('class', 'availableMembers'));
    }

    public function enrollMember(Request $request, MembershipClass $class)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $class->members()->attach($validated['member_id'], [
            'enrolled_date' => now(),
            'status' => 'enrolled',
        ]);

        return back()->with('success', 'Member enrolled successfully!');
    }

    public function updateEnrollment(Request $request, MembershipClass $class, Member $member)
    {
        $validated = $request->validate([
            'status' => 'required|in:enrolled,in_progress,completed,dropped',
            'attendance_count' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $updateData = ['status' => $validated['status']];
        
        if ($validated['status'] === 'completed') {
            $updateData['completed_date'] = now();
        }
        
        if (isset($validated['attendance_count'])) {
            $updateData['attendance_count'] = $validated['attendance_count'];
        }
        
        if (isset($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $class->members()->updateExistingPivot($member->id, $updateData);

        return back()->with('success', 'Enrollment updated successfully!');
    }

    // Transfer Management
    public function transfers()
    {
        $transfers = MemberTransfer::with(['member', 'approvedBy'])
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => MemberTransfer::where('status', 'pending')->count(),
            'approved' => MemberTransfer::where('status', 'approved')->count(),
            'completed' => MemberTransfer::where('status', 'completed')->count(),
            'transfer_in' => MemberTransfer::where('transfer_type', 'transfer_in')->count(),
            'transfer_out' => MemberTransfer::where('transfer_type', 'transfer_out')->count(),
        ];

        return view('membership.transfers', compact('transfers', 'stats'));
    }

    public function createTransfer()
    {
        $members = Member::active()->orderBy('first_name')->get();
        return view('membership.transfers-create', compact('members'));
    }

    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'transfer_type' => 'required|in:transfer_in,transfer_out',
            'previous_church' => 'nullable|string|max:255',
            'new_church' => 'nullable|string|max:255',
            'pastor_name' => 'nullable|string|max:255',
            'pastor_email' => 'nullable|email',
            'pastor_phone' => 'nullable|string|max:50',
            'request_date' => 'required|date',
            'reason' => 'nullable|string',
            'letter_of_transfer' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('letter_of_transfer')) {
            $validated['letter_of_transfer'] = $request->file('letter_of_transfer')
                ->store('transfer_letters', 'public');
        }

        MemberTransfer::create($validated);
        
        // Sync storage to public (for systems without symlink support)
        $this->syncStorageToPublic();

        return redirect()->route('membership.transfers')
            ->with('success', 'Transfer request created successfully!');
    }

    public function approveTransfer(MemberTransfer $transfer)
    {
        $transfer->update([
            'status' => 'approved',
            'approval_date' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Transfer approved successfully!');
    }

    public function completeTransfer(MemberTransfer $transfer)
    {
        $transfer->update([
            'status' => 'completed',
            'transfer_date' => now(),
        ]);

        // Update member status if transfer out
        if ($transfer->transfer_type === 'transfer_out') {
            $transfer->member->update(['membership_status' => 'transferred']);
            
            $transfer->member->statusHistory()->create([
                'previous_status' => $transfer->member->membership_status,
                'new_status' => 'transferred',
                'reason' => 'Transferred to ' . $transfer->new_church,
                'changed_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Transfer completed successfully!');
    }

    // Status History
    public function statusHistory(Member $member)
    {
        $history = $member->statusHistory()
            ->with('changedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('membership.status-history', compact('member', 'history'));
    }

    public function changeStatus(Request $request, Member $member)
    {
        $validated = $request->validate([
            'new_status' => 'required|string|max:50',
            'reason' => 'required|string',
        ]);

        $previousStatus = $member->membership_status;

        $member->update(['membership_status' => $validated['new_status']]);

        $member->statusHistory()->create([
            'previous_status' => $previousStatus,
            'new_status' => $validated['new_status'],
            'reason' => $validated['reason'],
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Member status updated successfully!');
    }
}
