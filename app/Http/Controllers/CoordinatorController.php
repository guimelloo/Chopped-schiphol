<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\Luchtvaartmaatschappij;
use App\Models\Verlanglijst;
use App\Models\Vlucht;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CoordinatorController extends Controller
{
    // ─── Auth ────────────────────────────────────────────────────────────────

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

    // ─── Dashboard ───────────────────────────────────────────────────────────

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

    // ─── Vluchten CRUD ───────────────────────────────────────────────────────

    public function vluchtenIndex(Request $request): Response
    {
        $query = Vlucht::with(['luchtvaartmaatschappij', 'gate']);

        if ($request->filled('zoek')) {
            $zoek = $request->zoek;
            $query->where(function ($q) use ($zoek) {
                $q->where('vlucht_nummer', 'like', "%{$zoek}%")
                  ->orWhere('vertrek_luchthaven', 'like', "%{$zoek}%")
                  ->orWhere('aankomst_luchthaven', 'like', "%{$zoek}%");
            });
        }

        $vluchten = $query->orderBy('vertrek_tijd')->get()
            ->map(fn (Vlucht $v) => $this->formatVluchtVoorCoordinator($v));

        return Inertia::render('Coordinator/Vluchten/Index', [
            'vluchten' => $vluchten,
            'filters'  => $request->only(['zoek']),
        ]);
    }

    public function vluchtenCreate(): Response
    {
        return Inertia::render('Coordinator/Vluchten/Create', [
            'maatschappijen' => Luchtvaartmaatschappij::orderBy('naam')->get(['id', 'naam', 'iata_code']),
            'gates'          => Gate::orderBy('terminal')->orderBy('nummer')->get(['id', 'terminal', 'nummer']),
        ]);
    }

    public function vluchtenStore(Request $request)
    {
        $validated = $request->validate([
            'luchtvaartmaatschappij_id' => 'required|exists:luchtvaartmaatschappijen,id',
            'vlucht_nummer'             => 'required|string|max:10',
            'vertrek_luchthaven'        => 'required|string|max:255',
            'aankomst_luchthaven'       => 'required|string|max:255',
            'vertrek_tijd'              => 'required|date',
            'aankomst_tijd'             => 'required|date|after:vertrek_tijd',
            'vliegtuig_type'            => 'required|string|max:100',
            'prijs_economy'             => 'nullable|numeric|min:0',
            'prijs_business'            => 'nullable|numeric|min:0',
            'beschikbaar_economy'       => 'nullable|integer|min:0',
            'beschikbaar_business'      => 'nullable|integer|min:0',
            'gate_id'                   => 'nullable|exists:gates,id',
            'status'                    => 'required|in:gepland,vertrokken,geland,geannuleerd',
            'services'                  => 'nullable|string',
        ]);

        Vlucht::create([
            'luchtvaartmaatschappij_id' => $validated['luchtvaartmaatschappij_id'],
            'vlucht_nummer'             => $validated['vlucht_nummer'],
            'vertrek_luchthaven'        => $validated['vertrek_luchthaven'],
            'aankomst_luchthaven'       => $validated['aankomst_luchthaven'],
            'vertrek_tijd'              => $validated['vertrek_tijd'],
            'aankomst_tijd'             => $validated['aankomst_tijd'],
            'vliegtuig_type'            => $validated['vliegtuig_type'],
            'prijs_economy'             => $validated['prijs_economy'] ?? 0,
            'prijs_business'            => $validated['prijs_business'] ?? 0,
            'stoelen_economy'           => $validated['beschikbaar_economy'] ?? 0,
            'stoelen_business'          => $validated['beschikbaar_business'] ?? 0,
            'gate_id'                   => $validated['gate_id'] ?: null,
            'status'                    => $validated['status'],
            'services'                  => $this->parseServices($validated['services'] ?? ''),
        ]);

        return redirect('/coordinator/vluchten');
    }

    public function vluchtenEdit(Vlucht $vlucht): Response
    {
        $vlucht->load(['luchtvaartmaatschappij', 'gate']);

        return Inertia::render('Coordinator/Vluchten/Edit', [
            'vlucht'         => $this->formatVluchtVoorCoordinator($vlucht),
            'maatschappijen' => Luchtvaartmaatschappij::orderBy('naam')->get(['id', 'naam', 'iata_code']),
            'gates'          => Gate::orderBy('terminal')->orderBy('nummer')->get(['id', 'terminal', 'nummer']),
        ]);
    }

    public function vluchtenUpdate(Request $request, Vlucht $vlucht)
    {
        $validated = $request->validate([
            'luchtvaartmaatschappij_id' => 'required|exists:luchtvaartmaatschappijen,id',
            'vlucht_nummer'             => 'required|string|max:10',
            'vertrek_luchthaven'        => 'required|string|max:255',
            'aankomst_luchthaven'       => 'required|string|max:255',
            'vertrek_tijd'              => 'required|date',
            'aankomst_tijd'             => 'required|date|after:vertrek_tijd',
            'vliegtuig_type'            => 'required|string|max:100',
            'prijs_economy'             => 'nullable|numeric|min:0',
            'prijs_business'            => 'nullable|numeric|min:0',
            'beschikbaar_economy'       => 'nullable|integer|min:0',
            'beschikbaar_business'      => 'nullable|integer|min:0',
            'gate_id'                   => 'nullable|exists:gates,id',
            'status'                    => 'required|in:gepland,vertrokken,geland,geannuleerd',
            'services'                  => 'nullable|string',
        ]);

        $vlucht->update([
            'luchtvaartmaatschappij_id' => $validated['luchtvaartmaatschappij_id'],
            'vlucht_nummer'             => $validated['vlucht_nummer'],
            'vertrek_luchthaven'        => $validated['vertrek_luchthaven'],
            'aankomst_luchthaven'       => $validated['aankomst_luchthaven'],
            'vertrek_tijd'              => $validated['vertrek_tijd'],
            'aankomst_tijd'             => $validated['aankomst_tijd'],
            'vliegtuig_type'            => $validated['vliegtuig_type'],
            'prijs_economy'             => $validated['prijs_economy'] ?? $vlucht->prijs_economy,
            'prijs_business'            => $validated['prijs_business'] ?? $vlucht->prijs_business,
            'stoelen_economy'           => $validated['beschikbaar_economy'] ?? $vlucht->stoelen_economy,
            'stoelen_business'          => $validated['beschikbaar_business'] ?? $vlucht->stoelen_business,
            'gate_id'                   => $validated['gate_id'] ?: null,
            'status'                    => $validated['status'],
            'services'                  => $this->parseServices($validated['services'] ?? ''),
        ]);

        return redirect('/coordinator/vluchten');
    }

    public function vluchtenDelete(Vlucht $vlucht)
    {
        $vlucht->delete();
        return redirect('/coordinator/vluchten');
    }

    // ─── Gates ───────────────────────────────────────────────────────────────

    public function gatesIndex(): Response
    {
        $gates = Gate::with(['vluchten' => fn ($q) => $q->where('status', '!=', 'geannuleerd')->orderBy('vertrek_tijd')])
            ->orderBy('terminal')
            ->orderBy('nummer')
            ->get()
            ->map(fn (Gate $g) => [
                'id'       => $g->id,
                'terminal' => $g->terminal,
                'nummer'   => $g->nummer,
                'type'     => $g->type ?? 'standaard',
                'vlucht'   => $g->vluchten->first() ? [
                    'id'                  => $g->vluchten->first()->id,
                    'vlucht_nummer'       => $g->vluchten->first()->vlucht_nummer,
                    'vertrek_luchthaven'  => $g->vluchten->first()->vertrek_luchthaven,
                    'aankomst_luchthaven' => $g->vluchten->first()->aankomst_luchthaven,
                    'vertrek_tijd'        => $g->vluchten->first()->vertrek_tijd->format('Y-m-d H:i'),
                ] : null,
            ]);

        $vluchten = Vlucht::where('status', '!=', 'geannuleerd')
            ->orderBy('vertrek_tijd')
            ->get()
            ->map(fn (Vlucht $v) => [
                'id'                  => $v->id,
                'vlucht_nummer'       => $v->vlucht_nummer,
                'vertrek_luchthaven'  => $v->vertrek_luchthaven,
                'aankomst_luchthaven' => $v->aankomst_luchthaven,
            ]);

        return Inertia::render('Coordinator/Gates/Index', [
            'gates'    => $gates,
            'vluchten' => $vluchten,
        ]);
    }

    public function gatesStore(Request $request)
    {
        $request->validate([
            'terminal' => 'required|string|max:1',
            'nummer'   => 'required|string|max:10',
            'type'     => 'nullable|in:standaard,uitgebreid',
        ]);

        Gate::create([
            'terminal' => strtoupper($request->terminal),
            'nummer'   => strtoupper($request->nummer),
            'type'     => $request->type ?? 'standaard',
        ]);

        return back();
    }

    public function gatesToewijzen(Request $request, Gate $gate)
    {
        $request->validate(['vlucht_id' => 'nullable|exists:vluchten,id']);

        // Remove this gate from any current flight
        Vlucht::where('gate_id', $gate->id)->update(['gate_id' => null]);

        if ($request->vlucht_id) {
            Vlucht::findOrFail($request->vlucht_id)->update(['gate_id' => $gate->id]);
        }

        return back();
    }

    // ─── Maatschappijen ──────────────────────────────────────────────────────

    public function maatschappijenIndex(): Response
    {
        $maatschappijen = Luchtvaartmaatschappij::withCount('vluchten')
            ->orderBy('naam')
            ->get()
            ->map(fn (Luchtvaartmaatschappij $m) => [
                'id'           => $m->id,
                'naam'         => $m->naam,
                'iata_code'    => $m->iata_code,
                'land'         => $m->land,
                'logo_url'     => $m->logo_url,
                'vluchten_count' => $m->vluchten_count,
            ]);

        return Inertia::render('Coordinator/Maatschappijen/Index', [
            'maatschappijen' => $maatschappijen,
        ]);
    }

    public function maatschappijenStore(Request $request)
    {
        $request->validate([
            'naam'      => 'required|string|max:255',
            'iata_code' => 'required|string|max:3|unique:luchtvaartmaatschappijen',
            'land'      => 'nullable|string|max:100',
            'logo_url'  => 'nullable|max:500',
        ]);

        Luchtvaartmaatschappij::create([
            'naam'      => $request->naam,
            'iata_code' => strtoupper($request->iata_code),
            'land'      => $request->land,
            'logo_url'  => $request->logo_url ?: null,
        ]);

        return back();
    }

    public function maatschappijenUpdate(Request $request, Luchtvaartmaatschappij $maatschappij)
    {
        $request->validate([
            'naam'      => 'required|string|max:255',
            'iata_code' => 'required|string|max:3|unique:luchtvaartmaatschappijen,iata_code,' . $maatschappij->id,
            'land'      => 'nullable|string|max:100',
            'logo_url'  => 'nullable|max:500',
        ]);

        $maatschappij->update([
            'naam'      => $request->naam,
            'iata_code' => strtoupper($request->iata_code),
            'land'      => $request->land,
            'logo_url'  => $request->logo_url ?: null,
        ]);

        return back();
    }

    public function maatschappijenDelete(Luchtvaartmaatschappij $maatschappij)
    {
        $maatschappij->delete();
        return back();
    }

    // ─── Verlanglijst ────────────────────────────────────────────────────────

    public function verlanglijstIndex(): Response
    {
        $coordinator  = Auth::guard('coordinator')->user();
        $verlanglijst = Verlanglijst::where('coordinator_id', $coordinator->id)
            ->orderByRaw("CASE prioriteit WHEN 'hoog' THEN 1 WHEN 'normaal' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn (Verlanglijst $v) => [
                'id'               => $v->id,
                'maatschappij_naam' => $v->maatschappij_naam,
                'bestemming'       => $v->bestemming,
                'gewenste_datum'   => $v->gewenste_datum?->format('Y-m-d'),
                'stoelklasse'      => $v->stoelklasse,
                'opmerkingen'      => $v->opmerkingen,
                'prioriteit'       => $v->prioriteit,
            ]);

        return Inertia::render('Coordinator/Verlanglijst/Index', [
            'verlanglijst'   => $verlanglijst,
            'maatschappijen' => Luchtvaartmaatschappij::orderBy('naam')->get(['id', 'naam', 'iata_code']),
        ]);
    }

    public function verlanglijstStore(Request $request)
    {
        $coordinator = Auth::guard('coordinator')->user();

        $request->validate([
            'maatschappij_naam' => 'nullable|string|max:255',
            'bestemming'        => 'required|string|max:255',
            'gewenste_datum'    => 'nullable|date',
            'stoelklasse'       => 'required|in:economy,business',
            'opmerkingen'       => 'nullable|string|max:1000',
            'prioriteit'        => 'required|in:hoog,normaal,laag',
        ]);

        Verlanglijst::create([
            'coordinator_id'   => $coordinator->id,
            'maatschappij_naam' => $request->maatschappij_naam,
            'bestemming'       => $request->bestemming,
            'gewenste_datum'   => $request->gewenste_datum ?: null,
            'stoelklasse'      => $request->stoelklasse,
            'opmerkingen'      => $request->opmerkingen,
            'prioriteit'       => $request->prioriteit,
        ]);

        return back();
    }

    public function verlanglijstDelete(Verlanglijst $verlanglijst)
    {
        $coordinator = Auth::guard('coordinator')->user();

        if ($verlanglijst->coordinator_id === $coordinator->id) {
            $verlanglijst->delete();
        }

        return back();
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function formatVluchtVoorCoordinator(Vlucht $v): array
    {
        return [
            'id'                        => $v->id,
            'vlucht_nummer'             => $v->vlucht_nummer,
            'luchtvaartmaatschappij_id' => $v->luchtvaartmaatschappij_id,
            'luchtvaartmaatschappij'    => $v->luchtvaartmaatschappij ? [
                'naam'      => $v->luchtvaartmaatschappij->naam,
                'iata_code' => $v->luchtvaartmaatschappij->iata_code,
            ] : null,
            'vertrek_luchthaven'   => $v->vertrek_luchthaven,
            'aankomst_luchthaven'  => $v->aankomst_luchthaven,
            'vertrek_tijd'         => $v->vertrek_tijd->format('Y-m-d H:i'),
            'aankomst_tijd'        => $v->aankomst_tijd->format('Y-m-d H:i'),
            'vliegtuig_type'       => $v->vliegtuig_type,
            'prijs_economy'        => $v->prijs_economy,
            'prijs_business'       => $v->prijs_business,
            'beschikbaar_economy'  => $v->stoelen_economy,
            'beschikbaar_business' => $v->stoelen_business,
            'services'             => $v->services ?? [],
            'gate_id'              => $v->gate_id,
            'gate'                 => $v->gate ? [
                'nummer'   => $v->gate->nummer,
                'terminal' => $v->gate->terminal,
            ] : null,
            'status' => $v->status,
        ];
    }

    private function parseServices(string $services): array
    {
        if (empty(trim($services))) {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode(',', $services))
        ));
    }
}
