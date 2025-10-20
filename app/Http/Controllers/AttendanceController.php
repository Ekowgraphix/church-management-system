<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Service;
use App\Models\Member;
use App\Models\Visitor;
use App\Models\AttendanceQrCode;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['member', 'visitor']);

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        } else {
            $query->whereDate('attendance_date', Carbon::today());
        }

        $attendances = $query->latest('check_in_time')->paginate(50);
        $members = Member::active()->orderBy('first_name')->get();
        $services = Service::where('is_active', true)->orderBy('day_of_week')->get();

        $stats = [
            'total_today' => AttendanceRecord::whereDate('attendance_date', Carbon::today())->count(),
            'members_today' => AttendanceRecord::whereDate('attendance_date', Carbon::today())
                ->whereNotNull('member_id')->count(),
            'visitors_today' => AttendanceRecord::whereDate('attendance_date', Carbon::today())
                ->whereNotNull('visitor_id')->count(),
            'total_members' => Member::active()->count(),
        ];

        return view('attendance.index', compact('attendances', 'members', 'services', 'stats'));
    }

    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'service_id' => 'required|exists:services,id',
            'attendance_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Check if already marked for this service today
        $existing = AttendanceRecord::where('member_id', $validated['member_id'])
            ->where('service_id', $validated['service_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->first();

        if ($existing) {
            return back()->with('info', 'Attendance already marked for this member and service.');
        }
        $validated['check_in_time'] = now();
        $validated['check_in_method'] = 'manual';

        AttendanceRecord::create($validated);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance marked successfully!');
    }

    public function checkInByQr(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
            'service_id' => 'required|exists:services,id',
        ]);

        $qrCode = AttendanceQrCode::where('qr_code', $validated['qr_code'])
            ->where('is_active', true)
            ->first();

        if (!$qrCode) {
            return response()->json(['error' => 'Invalid QR code'], 404);
        }

        if ($qrCode->expires_at && $qrCode->expires_at < now()) {
            return response()->json(['error' => 'QR code has expired'], 400);
        }

        // Check if already checked in today
        $existingAttendance = AttendanceRecord::where('member_id', $qrCode->member_id)
            ->where('service_id', $validated['service_id'])
            ->whereDate('attendance_date', Carbon::today())
            ->first();

        if ($existingAttendance) {
            return response()->json(['error' => 'Already checked in'], 400);
        }

        $attendance = AttendanceRecord::create([
            'service_id' => $validated['service_id'],
            'member_id' => $qrCode->member_id,
            'attendance_date' => Carbon::today(),
            'check_in_time' => now(),
            'check_in_method' => 'qr_code',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'attendance' => $attendance,
        ]);
    }

    public function generateQrCode(Member $member)
    {
        $qrCode = AttendanceQrCode::firstOrCreate(
            ['member_id' => $member->id],
            [
                'qr_code' => 'MEM-QR-' . $member->id . '-' . \Str::random(10),
                'is_active' => true,
            ]
        );

        $qrImage = QrCode::size(300)->generate($qrCode->qr_code);

        return view('attendance.qr-code', compact('member', 'qrCode', 'qrImage'));
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $attendanceData = AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->selectRaw('attendance_date, COUNT(*) as total')
            ->groupBy('attendance_date')
            ->orderBy('attendance_date')
            ->get();

        $topAttendees = Member::withCount([
            'attendanceRecords' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('attendance_date', [$startDate, $endDate]);
            }
        ])
        ->orderByDesc('attendance_records_count')
        ->take(10)
        ->get();

        return view('attendance.report', compact('attendanceData', 'topAttendees', 'startDate', 'endDate'));
    }

    // Member Attendance History
    public function memberHistory(Member $member)
    {
        $attendanceRecords = $member->attendanceRecords()
            ->with('service')
            ->orderBy('attendance_date', 'desc')
            ->paginate(50);

        $stats = [
            'total_attendances' => $member->attendanceRecords()->count(),
            'this_month' => $member->attendanceRecords()
                ->whereMonth('attendance_date', Carbon::now()->month)
                ->count(),
            'this_year' => $member->attendanceRecords()
                ->whereYear('attendance_date', Carbon::now()->year)
                ->count(),
            'last_attendance' => $member->attendanceRecords()
                ->latest('attendance_date')
                ->first(),
        ];

        // Calculate attendance rate (last 3 months)
        $totalSundays = Carbon::now()->subMonths(3)->diffInWeeks(Carbon::now()) * 1; // Approximate
        $attendedSundays = $member->attendanceRecords()
            ->where('attendance_date', '>=', Carbon::now()->subMonths(3))
            ->whereHas('service', function($q) {
                $q->where('day_of_week', 0); // Sunday
            })
            ->distinct('attendance_date')
            ->count();
        
        $stats['attendance_rate'] = $totalSundays > 0 ? round(($attendedSundays / $totalSundays) * 100, 1) : 0;

        return view('attendance.member-history', compact('member', 'attendanceRecords', 'stats'));
    }

    // Mobile Check-in Interface
    public function mobileCheckin()
    {
        $services = Service::where('is_active', true)
            ->orderBy('day_of_week')
            ->get();
        
        return view('attendance.mobile-checkin', compact('services'));
    }

    // QR Code Scanner
    public function qrScanner()
    {
        $services = Service::where('is_active', true)
            ->orderBy('day_of_week')
            ->get();
        
        return view('attendance.qr-scanner', compact('services'));
    }

    // Check Absences and Send Notifications
    public function checkAbsences()
    {
        $lastSunday = Carbon::now()->previous(Carbon::SUNDAY);
        
        $activeMembers = Member::where('membership_status', 'active')->get();
        $absentMembers = [];

        foreach ($activeMembers as $member) {
            $attended = AttendanceRecord::where('member_id', $member->id)
                ->whereDate('attendance_date', $lastSunday)
                ->exists();

            if (!$attended) {
                $absentMembers[] = $member;
                
                // Send notification (email/SMS)
                // You can integrate with your email/SMS service here
            }
        }

        return view('attendance.absences', compact('absentMembers', 'lastSunday'));
    }

    // Attendance Analytics
    public function analytics(Request $request)
    {
        $period = $request->input('period', 'month'); // week, month, year
        
        $startDate = match($period) {
            'week' => Carbon::now()->startOfWeek(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $endDate = Carbon::now();

        // Daily attendance trend
        $dailyTrend = AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->selectRaw('DATE(attendance_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Service-wise attendance
        $serviceWise = AttendanceRecord::with('service')
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get()
            ->groupBy('service_id')
            ->map(function($records) {
                return [
                    'service' => $records->first()->service->service_name ?? 'Unknown',
                    'count' => $records->count()
                ];
            });

        // Average attendance
        $avgAttendance = AttendanceRecord::whereBetween('attendance_date', [$startDate, $endDate])
            ->selectRaw('DATE(attendance_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->avg('count');

        // Top attendees
        $topAttendees = Member::withCount([
            'attendanceRecords' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('attendance_date', [$startDate, $endDate]);
            }
        ])
        ->orderByDesc('attendance_records_count')
        ->take(10)
        ->get();

        return view('attendance.analytics', compact(
            'dailyTrend', 
            'serviceWise', 
            'avgAttendance', 
            'topAttendees',
            'period',
            'startDate',
            'endDate'
        ));
    }
}
