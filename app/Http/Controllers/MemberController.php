<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberEmergencyContact;
use App\Models\MemberDocument;
use App\Models\MemberStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\SyncsStorage;

class MemberController extends Controller
{
    use SyncsStorage;
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('membership_status', $request->status);
        }

        $members = $query->latest()->paginate(20);

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'wedding_anniversary' => 'nullable|date',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'membership_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Generate unique member ID
        $validated['member_id'] = 'MEM-' . strtoupper(Str::random(8));
        
        // Set default membership status if not provided
        if (!isset($validated['membership_status'])) {
            $validated['membership_status'] = 'active';
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('members/photos', 'public');
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        $member = Member::create($validated);
        
        // Sync storage to public (for systems without symlink support)
        $this->syncStorageToPublic();

        return redirect()->route('members.index')
            ->with('success', 'Member created successfully! You can now view or edit the member.');
    }

    public function show(Member $member)
    {
        $member->load([
            'emergencyContacts',
            'documents.uploadedBy',
            'attendanceRecords',
            'donations',
            'pledges'
        ]);

        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'nullable|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'wedding_anniversary' => 'nullable|date',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'membership_status' => 'required|in:active,inactive,transferred,deceased',
            'membership_date' => 'nullable|date',
            'baptism_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Track status change
        if ($member->membership_status !== $validated['membership_status']) {
            MemberStatusHistory::create([
                'member_id' => $member->id,
                'previous_status' => $member->membership_status,
                'new_status' => $validated['membership_status'],
                'changed_by' => auth()->id(),
            ]);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($member->profile_photo) {
                Storage::disk('public')->delete($member->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('members/photos', 'public');
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        $member->update($validated);
        
        // Sync storage to public (for systems without symlink support)
        $this->syncStorageToPublic();

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully!');
    }

    public function destroy(Member $member)
    {
        if ($member->profile_photo) {
            Storage::disk('public')->delete($member->profile_photo);
        }

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }

    // Emergency Contacts Management
    public function addEmergencyContact(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $member->emergencyContacts()->create($validated);

        return back()->with('success', 'Emergency contact added successfully.');
    }

    public function updateEmergencyContact(Request $request, Member $member, $contactId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        $contact = $member->emergencyContacts()->findOrFail($contactId);
        $contact->update($validated);

        return back()->with('success', 'Emergency contact updated successfully.');
    }

    public function deleteEmergencyContact(Member $member, $contactId)
    {
        $contact = $member->emergencyContacts()->findOrFail($contactId);
        $contact->delete();

        return back()->with('success', 'Emergency contact deleted successfully.');
    }

    // Document Management
    public function uploadDocument(Request $request, Member $member)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:100',
            'document_name' => 'required|string|max:255',
            'document' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = $file->store('member_documents', 'public');

            $member->documents()->create([
                'document_type' => $validated['document_type'],
                'document_name' => $validated['document_name'],
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
            
            // Sync storage to public (for systems without symlink support)
            $this->syncStorageToPublic();

            return back()->with('success', 'Document uploaded successfully.');
        }

        return back()->with('error', 'Document upload failed.');
    }

    public function deleteDocument(Member $member, $documentId)
    {
        $document = $member->documents()->findOrFail($documentId);
        
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

    public function downloadDocument(Member $member, $documentId)
    {
        $document = $member->documents()->findOrFail($documentId);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'Document file not found.');
        }

        return Storage::disk('public')->download($document->file_path, $document->document_name);
    }
}
