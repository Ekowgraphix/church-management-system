<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function dashboard()
    {
        $users = \App\Models\User::latest()->limit(10)->get();
        
        return view('settings.dashboard', compact('users'));
    }
    
    public function index()
    {
        $settings = [
            'church_name' => 'K.G.C Emmanuel Temple',
            'church_email' => 'info@church.com',
            'church_phone' => '0241234567',
            'church_address' => 'Accra, Ghana',
        ];

        $stats = [
            'total_members' => DB::table('members')->count(),
            'total_visitors' => DB::table('visitors')->count(),
            'total_donations' => DB::table('donations')->count(),
            'total_equipment' => DB::table('equipment')->count(),
        ];

        return view('settings.index', compact('settings', 'stats'));
    }

    public function update(Request $request)
    {
        // Settings update logic here
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}
