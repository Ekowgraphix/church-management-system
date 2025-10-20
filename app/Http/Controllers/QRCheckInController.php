<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCheckInController extends Controller
{
    public function showScanner()
    {
        return view('qr-checkin.scanner');
    }

    public function generateMemberQR(Member $member)
    {
        if (!$member->qr_code) {
            $member->qr_code = 'QR-' . strtoupper(Str::random(12));
            $member->save();
        }

        // Use SVG format which doesn't require imagick
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($member->qr_code);

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml');
    }

    public function showMemberQR(Member $member)
    {
        if (!$member->qr_code) {
            $member->qr_code = 'QR-' . strtoupper(Str::random(12));
            $member->save();
        }

        return view('qr-checkin.member-qr', compact('member'));
    }

    public function processQRCheckIn(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $member = Member::where('qr_code', $request->qr_code)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code'
            ], 404);
        }

        // Check if already checked in today
        $today = now()->format('Y-m-d');
        $existingAttendance = Attendance::where('member_id', $member->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked in today',
                'member' => [
                    'name' => $member->full_name,
                    'time' => $existingAttendance->check_in_time->format('h:i A')
                ]
            ], 400);
        }

        // Get default service or first available service
        $service = \App\Models\Service::where('is_active', true)->first();
        
        // Create attendance record
        $attendance = Attendance::create([
            'member_id' => $member->id,
            'service_id' => $service ? $service->id : null,
            'attendance_date' => $today,
            'check_in_time' => now(),
            'check_in_method' => 'qr_code',
        ]);

        // Update member's last scan time
        $member->update(['last_qr_scan' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful!',
            'member' => [
                'name' => $member->full_name,
                'phone' => $member->phone,
                'time' => $attendance->check_in_time->format('h:i A')
            ]
        ]);
    }

    public function showBulkGenerate()
    {
        return view('qr-checkin.bulk-generate');
    }

    public function bulkGenerateQR()
    {
        $members = Member::whereNull('qr_code')->get();
        
        foreach ($members as $member) {
            $member->qr_code = 'QR-' . strtoupper(Str::random(12));
            $member->save();
        }

        return redirect()->back()->with('success', "Generated QR codes for {$members->count()} members");
    }
}
