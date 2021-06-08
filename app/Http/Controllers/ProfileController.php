<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function render()
    {
        return view('settings', [
            'user' => Auth::user()
        ]);
    }
    public function renderPassword()
    {
        return view('password-settings');
    }

    public function renderNichePreferences()
    {
        return view('niche-preferences');
    }
}
