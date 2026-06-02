<?php

namespace App\Http\Controllers;

use App\Models\Luchtvaartmaatschappij;
use App\Models\Vlucht;
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

    public function dashboard(Request $request): Response
    {
        $coordinator = Auth::guard('coordinator')->user();
        $datum       = $request->get('datum', now()->toDateString());
        $weekStart   = \Carbon\Carbon::parse($datum)->startOfWeek();
        $weekEind    = $weekStart->copy()->endOfWeek();

        $maatschappijen = Luchtvaartmaatschappij::with([
            'vluchten' => fn ($q) => $q
                ->whereBetween('vertrek_tijd', [$weekStart, $weekEind])
                ->with('gate')
                ->orderBy('vertrek_tijd'),
        ])->get()->map(fn (Luchtvaartmaatschappij $m) => [
            'id'        => $m->id,
            'naam'      => $m->naam,
            'iata_code' => $m->iata_code,
            'land'      => $m->land,
            'vluchten'  => $m->vluchten->map(fn (Vlucht $v) => [
                'id'                  => $v->id,
                'vlucht_nummer'       => $v->vlucht_nummer,
                'vertrek_luchthaven'  => $v->vertrek_luchthaven,
                'aankomst_luchthaven' => $v->aankomst_luchthaven,
                'vertrek_tijd'        => $v->vertrek_tijd->format('Y-m-d H:i'),
                'aankomst_tijd'       => $v->aankomst_tijd->format('Y-m-d H:i'),
                'vliegtuig_type'      => $v->vliegtuig_type,
                'status'              => $v->status,
                'gate'                => $v->gate ? $v->gate->terminal . $v->gate->nummer : '-',
            ]),
        ]);

        $dagvluchten = Vlucht::with('luchtvaartmaatschappij')
            ->whereDate('vertrek_tijd', $datum)
            ->orderBy('vertrek_tijd')
            ->get()
            ->map(fn (Vlucht $v) => [
                'id'                     => $v->id,
                'vlucht_nummer'          => $v->vlucht_nummer,
                'vertrek_luchthaven'     => $v->vertrek_luchthaven,
                'aankomst_luchthaven'    => $v->aankomst_luchthaven,
                'vertrek_tijd'           => $v->vertrek_tijd->format('H:i'),
                'aankomst_tijd'          => $v->aankomst_tijd->format('H:i'),
                'status'                 => $v->status,
                'luchtvaartmaatschappij' => $v->luchtvaartmaatschappij?->naam,
            ]);

        return Inertia::render('Coordinator/Dashboard', [
            'coordinator'    => ['naam' => $coordinator->naam],
            'maatschappijen' => $maatschappijen,
            'dagvluchten'    => $dagvluchten,
            'datum'          => $datum,
            'weekStart'      => $weekStart->toDateString(),
            'weekEind'       => $weekEind->toDateString(),
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
