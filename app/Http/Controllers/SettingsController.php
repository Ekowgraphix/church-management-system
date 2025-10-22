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
        // Redirect to dashboard
        return redirect()->route('settings.dashboard');
    }

    public function update(Request $request)
    {
        // Settings update logic here
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}
