<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CoordinatorController extends Controller
{
    public function loginForm(): Response
    {
        return Inertia::render('Coordinator/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'gebruikersnaam' => 'required|string',
            'wachtwoord'     => 'required|string',
        ]);

        if (Auth::guard('coordinator')->attempt([
            'gebruikersnaam' => $credentials['gebruikersnaam'],
            'wachtwoord'     => $credentials['wachtwoord'],
        ], $request->boolean('onthouden'))) {
            $request->session()->regenerate();

            return redirect()->route('coordinator.dashboard');
        }

        return back()->withErrors([
            'gebruikersnaam' => 'Gebruikersnaam of wachtwoord is onjuist.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('coordinator')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('coordinator.login');
    }
}
