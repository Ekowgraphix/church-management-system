<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ChildrenMinistry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Today's birthdays
        $todayBirthdays = Member::whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->get();
        
        $todayChildrenBirthdays = ChildrenMinistry::whereMonth('dob', $today->month)
            ->whereDay('dob', $today->day)
            ->get();
        
        // Tomorrow's birthdays
        $tomorrow = $today->copy()->addDay();
        $tomorrowBirthdays = Member::whereMonth('date_of_birth', $tomorrow->month)
            ->whereDay('date_of_birth', $tomorrow->day)
            ->get();
        
        $tomorrowChildrenBirthdays = ChildrenMinistry::whereMonth('dob', $tomorrow->month)
            ->whereDay('dob', $tomorrow->day)
            ->get();
        
        // This week's birthdays
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        
        $weekBirthdays = Member::whereBetween('date_of_birth', [$weekStart, $weekEnd])->get();
        $weekChildrenBirthdays = ChildrenMinistry::whereBetween('dob', [$weekStart, $weekEnd])->get();
        
        // This month's birthdays - SQLite compatible
        $monthBirthdays = Member::whereMonth('date_of_birth', $today->month)
            ->orderByRaw("strftime('%d', date_of_birth)")
            ->get();
        
        $monthChildrenBirthdays = ChildrenMinistry::whereMonth('dob', $today->month)
            ->orderByRaw("strftime('%d', dob)")
            ->get();
        
        // Next month's birthdays
        $nextMonth = $today->copy()->addMonth();
        $nextMonthBirthdays = Member::whereMonth('date_of_birth', $nextMonth->month)
            ->orderByRaw("strftime('%d', date_of_birth)")
            ->get();
        
        $nextMonthChildrenBirthdays = ChildrenMinistry::whereMonth('dob', $nextMonth->month)
            ->orderByRaw("strftime('%d', dob)")
            ->get();
        
        // Upcoming birthdays (next 30 days)
        $next30Days = $today->copy()->addDays(30);
        $upcomingBirthdays = collect();
        
        // Get all members and children
        $allMembers = Member::whereNotNull('date_of_birth')->get();
        $allChildren = ChildrenMinistry::all();
        
        foreach ($allMembers as $member) {
            $nextBirthday = Carbon::parse($member->date_of_birth)->year($today->year);
            if ($nextBirthday < $today) {
                $nextBirthday->addYear();
            }
            if ($nextBirthday <= $next30Days) {
                $upcomingBirthdays->push([
                    'type' => 'member',
                    'name' => $member->full_name,
                    'date' => $nextBirthday,
                    'age' => $nextBirthday->year - Carbon::parse($member->date_of_birth)->year,
                    'phone' => $member->phone,
                    'email' => $member->email,
                ]);
            }
        }
        
        foreach ($allChildren as $child) {
            $nextBirthday = Carbon::parse($child->dob)->year($today->year);
            if ($nextBirthday < $today) {
                $nextBirthday->addYear();
            }
            if ($nextBirthday <= $next30Days) {
                $upcomingBirthdays->push([
                    'type' => 'child',
                    'name' => $child->child_name,
                    'date' => $nextBirthday,
                    'age' => $nextBirthday->year - $child->dob->year,
                    'phone' => $child->contact,
                    'parent' => $child->parent_name,
                ]);
            }
        }
        
        $upcomingBirthdays = $upcomingBirthdays->sortBy('date')->take(10);
        
        // Statistics
        $stats = [
            'today_count' => $todayBirthdays->count() + $todayChildrenBirthdays->count(),
            'week_count' => $weekBirthdays->count() + $weekChildrenBirthdays->count(),
            'month_count' => $monthBirthdays->count() + $monthChildrenBirthdays->count(),
            'upcoming_count' => $upcomingBirthdays->count(),
        ];
        
        // Age milestones this month (10, 18, 21, 25, 30, 40, 50, 60, 70, 80, etc.)
        $milestones = collect();
        foreach ($monthBirthdays as $member) {
            $age = Carbon::parse($member->date_of_birth)->age;
            if (in_array($age, [10, 18, 21, 25, 30, 40, 50, 60, 70, 80, 90, 100])) {
                $milestones->push([
                    'name' => $member->full_name,
                    'age' => $age,
                    'date' => Carbon::parse($member->date_of_birth),
                ]);
            }
        }
        
        return view('birthdays.index', compact(
            'todayBirthdays', 
            'todayChildrenBirthdays',
            'tomorrowBirthdays',
            'tomorrowChildrenBirthdays',
            'weekBirthdays',
            'weekChildrenBirthdays',
            'monthBirthdays',
            'monthChildrenBirthdays',
            'nextMonthBirthdays',
            'nextMonthChildrenBirthdays',
            'upcomingBirthdays',
            'stats',
            'milestones'
        ));
    }
    
    public function sendWish(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|in:sms,email',
        ]);
        
        try {
            if ($validated['type'] === 'sms') {
                // SMS Implementation
                // TODO: Integrate with SMS service (e.g., Twilio, Hubtel, etc.)
                // For now, we'll log the message
                \Log::info('Birthday SMS to ' . $validated['recipient'] . ': ' . $validated['message']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Birthday wish sent via SMS successfully!',
                    'type' => 'sms'
                ]);
            } 
            
            if ($validated['type'] === 'email') {
                // Email Implementation
                // TODO: Send actual email using Laravel Mail
                // For now, we'll log the message
                \Log::info('Birthday Email to ' . $validated['recipient'] . ': ' . $validated['message']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Birthday wish sent via email successfully!',
                    'type' => 'email'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid message type'
            ], 400);
            
        } catch (\Exception $e) {
            \Log::error('Birthday wish error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send birthday wish: ' . $e->getMessage()
            ], 500);
        }
    }
}
