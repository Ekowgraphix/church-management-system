<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Attendance;
use App\Models\Service;
use App\Models\AttendanceRecord;
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

    /**
     * Generate QR code for a service
     */
    public function generateServiceQR(Service $service)
    {
        // Generate unique token if not exists
        if (!$service->qr_code_token) {
            $service->qr_code_token = 'SVC-' . strtoupper(Str::random(16)) . '-' . $service->id;
            $service->qr_code_generated_at = now();
            $service->save();
        }

        // Generate QR code as SVG
        $qrCode = QrCode::size(400)
            ->format('svg')
            ->errorCorrection('H')
            ->generate(route('qr.service.checkin', ['token' => $service->qr_code_token]));

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Show service QR code page
     */
    public function showServiceQR(Service $service)
    {
        // Generate token if not exists
        if (!$service->qr_code_token) {
            $service->qr_code_token = 'SVC-' . strtoupper(Str::random(16)) . '-' . $service->id;
            $service->qr_code_generated_at = now();
            $service->save();
        }

        return view('qr-checkin.service-qr', compact('service'));
    }

    /**
     * Show mobile scanner for service check-in
     */
    public function showServiceScanner()
    {
        // Get all active services, ordered by day and time
        $dayOrder = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $services = Service::where('is_active', true)
            ->get()
            ->sortBy(function($service) use ($dayOrder) {
                return array_search($service->day_of_week, $dayOrder);
            })
            ->values();

        // Get today's day for highlighting
        $today = strtolower(now()->format('l'));

        return view('qr-checkin.service-scanner', compact('services', 'today'));
    }

    /**
     * Process service QR code check-in
     */
    public function processServiceCheckIn(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            // Find service by token
            $service = Service::where('qr_code_token', $request->token)->first();

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code. Service not found.'
                ], 404);
            }

            if (!$service->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This service is not active'
                ], 400);
            }

            // Check authentication
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to check in. Please login first.'
                ], 401);
            }

            // Get current member
            $user = auth()->user();
            $member = $user->member;
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member profile not found for email: ' . $user->email . '. Please contact administrator.'
                ], 404);
            }

        // Check if already checked in today for this service
        $today = now()->format('Y-m-d');
        $existingAttendance = AttendanceRecord::where('member_id', $member->id)
            ->where('service_id', $service->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'You have already checked in to this service today',
                'data' => [
                    'service' => $service->name,
                    'time' => $existingAttendance->check_in_time
                ]
            ], 400);
        }

        // Create attendance record
        $attendance = AttendanceRecord::create([
            'service_id' => $service->id,
            'member_id' => $member->id,
            'attendance_date' => $today,
            'check_in_time' => now()->format('H:i:s'),
            'check_in_method' => 'qr_code',
            'check_in_location' => $request->latitude && $request->longitude 
                ? "{$request->latitude},{$request->longitude}" 
                : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful! Welcome to ' . $service->name,
            'data' => [
                'member_name' => $member->full_name,
                'service' => $service->name,
                'time' => now()->format('h:i A'),
                'date' => now()->format('F d, Y')
            ]
        ]);

        } catch (\Exception $e) {
            \Log::error('QR Check-in Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get service list for check-in
     */
    public function getActiveServices()
    {
        $services = Service::where('is_active', true)
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services
        ]);
    }
}
