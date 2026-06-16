<?php

namespace App\Http\Controllers;

use App\Models\Boeking;
use App\Models\Coordinator;
use App\Models\Luchtvaartmaatschappij;
use App\Models\Vlucht;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DirecteurController extends Controller
{
    public function loginForm(): Response
    {
        return Inertia::render('Directeur/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'gebruikersnaam' => 'required|string',
            'wachtwoord'     => 'required|string',
        ]);

        if (Auth::guard('directeur')->attempt([
            'gebruikersnaam' => $credentials['gebruikersnaam'],
            'wachtwoord'     => $credentials['wachtwoord'],
        ])) {
            $request->session()->regenerate();
            return redirect('/directeur/dashboard');
        }

        return back()->withErrors([
            'gebruikersnaam' => 'Gebruikersnaam of wachtwoord is onjuist.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('directeur')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/directeur/inloggen');
    }

    public function dashboard(): Response
    {
        $directeur = Auth::guard('directeur')->user();

        $stats = [
            'totale_omzet'          => (float) Boeking::where('status', 'bevestigd')->sum('prijs'),
            'totaal_boekingen'      => Boeking::count(),
            'actieve_coordinatoren' => Coordinator::where('actief', true)->count(),
            'vluchten_vandaag'      => Vlucht::whereDate('vertrek_tijd', today())->count(),
        ];

        $recente_boekingen = Boeking::with('vlucht')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Boeking $b) => [
                'id'              => $b->id,
                'boekings_nummer' => $b->boekings_nummer,
                'naam_reiziger'   => $b->naam_reiziger,
                'prijs'           => $b->prijs,
                'aangemaakt_op'   => $b->created_at->format('d-m-Y'),
            ]);

        return Inertia::render('Directeur/Dashboard', [
            'directeur'        => ['naam' => $directeur->naam],
            'stats'            => $stats,
            'recente_boekingen' => $recente_boekingen,
        ]);
    }

    public function boekingen(Request $request): Response
    {
        $query = Boeking::with('vlucht');

        if ($request->filled('zoek')) {
            $zoek = $request->zoek;
            $query->where(function ($q) use ($zoek) {
                $q->where('naam_reiziger', 'like', "%{$zoek}%")
                  ->orWhere('email_reiziger', 'like', "%{$zoek}%")
                  ->orWhere('boekings_nummer', 'like', "%{$zoek}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('van')) {
            $query->whereDate('created_at', '>=', $request->van);
        }

        if ($request->filled('tot')) {
            $query->whereDate('created_at', '<=', $request->tot);
        }

        $boekingen = $query->latest()->get()->map(fn (Boeking $b) => [
            'id'              => $b->id,
            'boekings_nummer' => $b->boekings_nummer,
            'naam_reiziger'   => $b->naam_reiziger,
            'email_reiziger'  => $b->email_reiziger,
            'stoelklasse'     => $b->stoelklasse,
            'prijs'           => $b->prijs,
            'status'          => $b->status,
            'aangemaakt_op'   => $b->created_at->format('d-m-Y H:i'),
            'vlucht_nummer'   => $b->vlucht?->vlucht_nummer,
        ]);

        $stats = [
            'totale_omzet' => (float) Boeking::where('status', 'bevestigd')->sum('prijs'),
            'totaal'       => Boeking::count(),
            'bevestigd'    => Boeking::where('status', 'bevestigd')->count(),
            'geannuleerd'  => Boeking::where('status', 'geannuleerd')->count(),
        ];

        return Inertia::render('Directeur/Boekingen/Index', [
            'boekingen' => $boekingen,
            'stats'     => $stats,
            'filters'   => $request->only(['zoek', 'status', 'van', 'tot']),
        ]);
    }

    public function coordinatoren(): Response
    {
        $coordinatoren = Coordinator::with('luchtvaartmaatschappij')
            ->orderBy('naam')
            ->get()
            ->map(fn (Coordinator $c) => [
                'id'                     => $c->id,
                'naam'                   => $c->naam,
                'gebruikersnaam'         => $c->gebruikersnaam,
                'email'                  => $c->email,
                'actief'                 => $c->actief,
                'luchtvaartmaatschappij' => $c->luchtvaartmaatschappij?->naam,
            ]);

        return Inertia::render('Directeur/Coordinatoren/Index', [
            'coordinatoren' => $coordinatoren,
        ]);
    }

    public function coordinatorCreate(): Response
    {
        $maatschappijen = Luchtvaartmaatschappij::orderBy('naam')->get(['id', 'naam', 'iata_code']);

        return Inertia::render('Directeur/Coordinatoren/Create', [
            'maatschappijen' => $maatschappijen,
        ]);
    }

    public function coordinatorStore(Request $request)
    {
        $validated = $request->validate([
            'naam'                     => 'required|string|max:255',
            'gebruikersnaam'           => 'required|string|max:255|unique:coordinatoren',
            'email'                    => 'nullable|email|max:255',
            'wachtwoord'               => 'required|string|min:6',
            'wachtwoord_bevestiging'   => 'same:wachtwoord',
            'actief'                   => 'boolean',
            'luchtvaartmaatschappij_id' => 'nullable|exists:luchtvaartmaatschappijen,id',
        ]);

        Coordinator::create([
            'naam'                     => $validated['naam'],
            'gebruikersnaam'           => $validated['gebruikersnaam'],
            'email'                    => $validated['email'] ?? null,
            'wachtwoord'               => $validated['wachtwoord'],
            'actief'                   => $validated['actief'] ?? true,
            'luchtvaartmaatschappij_id' => $validated['luchtvaartmaatschappij_id'] ?? null,
        ]);

        return redirect('/directeur/coordinatoren');
    }

    public function coordinatorEdit(Coordinator $coordinator): Response
    {
        $maatschappijen = Luchtvaartmaatschappij::orderBy('naam')->get(['id', 'naam', 'iata_code']);

        return Inertia::render('Directeur/Coordinatoren/Edit', [
            'coordinator' => [
                'id'                     => $coordinator->id,
                'naam'                   => $coordinator->naam,
                'gebruikersnaam'         => $coordinator->gebruikersnaam,
                'email'                  => $coordinator->email,
                'actief'                 => $coordinator->actief,
                'luchtvaartmaatschappij_id' => $coordinator->luchtvaartmaatschappij_id,
            ],
            'maatschappijen' => $maatschappijen,
        ]);
    }

    public function coordinatorUpdate(Request $request, Coordinator $coordinator)
    {
        $validated = $request->validate([
            'naam'                   => 'required|string|max:255',
            'gebruikersnaam'         => 'required|string|max:255|unique:coordinatoren,gebruikersnaam,' . $coordinator->id,
            'email'                  => 'nullable|email|max:255',
            'wachtwoord'             => 'nullable|string|min:6',
            'wachtwoord_bevestiging' => 'nullable|same:wachtwoord',
            'actief'                 => 'boolean',
        ]);

        $update = [
            'naam'           => $validated['naam'],
            'gebruikersnaam' => $validated['gebruikersnaam'],
            'email'          => $validated['email'] ?? null,
            'actief'         => $validated['actief'] ?? $coordinator->actief,
        ];

        if (! empty($validated['wachtwoord'])) {
            $update['wachtwoord'] = $validated['wachtwoord'];
        }

        $coordinator->update($update);

        return redirect('/directeur/coordinatoren');
    }

    public function coordinatorDelete(Coordinator $coordinator)
    {
        $coordinator->delete();
        return redirect('/directeur/coordinatoren');
    }
}
