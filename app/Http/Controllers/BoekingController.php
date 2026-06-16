<?php

namespace App\Http\Controllers;

use App\Models\Boeking;
use App\Models\Vlucht;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BoekingController extends Controller
{
    public function create(Request $request, Vlucht $vlucht): Response
    {
        $stoelklasse = $request->get('klasse', 'economy');

        if (! in_array($stoelklasse, ['economy', 'business'])) {
            $stoelklasse = 'economy';
        }

        $vlucht->load(['luchtvaartmaatschappij', 'gate']);
        $prijs = $stoelklasse === 'business' ? $vlucht->prijs_business : $vlucht->prijs_economy;

        return Inertia::render('Boekingen/Create', [
            'vlucht' => [
                'id'                     => $vlucht->id,
                'vlucht_nummer'          => $vlucht->vlucht_nummer,
                'vertrek_luchthaven'     => $vlucht->vertrek_luchthaven,
                'aankomst_luchthaven'    => $vlucht->aankomst_luchthaven,
                'vertrek_tijd'           => $vlucht->vertrek_tijd->format('Y-m-d H:i'),
                'aankomst_tijd'          => $vlucht->aankomst_tijd->format('Y-m-d H:i'),
                'vliegtuig_type'         => $vlucht->vliegtuig_type,
                'duur'                   => $vlucht->duur,
                'luchtvaartmaatschappij' => $vlucht->luchtvaartmaatschappij?->naam,
            ],
            'stoelklasse' => $stoelklasse,
            'prijs'       => $prijs,
        ]);
    }

    public function bevestigen(Request $request): Response
    {
        $data = $request->validate([
            'vlucht_id'         => 'required|exists:vluchten,id',
            'stoelklasse'       => 'required|in:economy,business',
            'stoel_voorkeur'    => 'nullable|in:raam,midden,gangpad',
            'naam_reiziger'     => 'required|string|max:255',
            'email_reiziger'    => 'required|email|max:255',
            'telefoon_reiziger' => 'nullable|string|max:20',
        ]);

        $vlucht = Vlucht::with('luchtvaartmaatschappij')->findOrFail($data['vlucht_id']);
        $prijs  = $data['stoelklasse'] === 'business'
            ? $vlucht->prijs_business
            : $vlucht->prijs_economy;

        return Inertia::render('Boekingen/Bevestigen', [
            'data'  => $data,
            'prijs' => $prijs,
            'vlucht' => [
                'id'                     => $vlucht->id,
                'vlucht_nummer'          => $vlucht->vlucht_nummer,
                'vertrek_luchthaven'     => $vlucht->vertrek_luchthaven,
                'aankomst_luchthaven'    => $vlucht->aankomst_luchthaven,
                'vertrek_tijd'           => $vlucht->vertrek_tijd->format('Y-m-d H:i'),
                'aankomst_tijd'          => $vlucht->aankomst_tijd->format('Y-m-d H:i'),
                'luchtvaartmaatschappij' => $vlucht->luchtvaartmaatschappij?->naam,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vlucht_id'         => 'required|exists:vluchten,id',
            'stoelklasse'       => 'required|in:economy,business',
            'stoel_voorkeur'    => 'nullable|in:raam,midden,gangpad',
            'naam_reiziger'     => 'required|string|max:255',
            'email_reiziger'    => 'required|email|max:255',
            'telefoon_reiziger' => 'nullable|string|max:20',
        ]);

        $vlucht = Vlucht::findOrFail($validated['vlucht_id']);
        $prijs  = $validated['stoelklasse'] === 'business'
            ? $vlucht->prijs_business
            : $vlucht->prijs_economy;

        $boeking = Boeking::create([
            ...$validated,
            'prijs'  => $prijs,
            'status' => 'bevestigd',
        ]);

        return redirect()->route('boekingen.show', $boeking->boekings_nummer);
    }

    public function show(string $boekingsNummer): Response
    {
        $boeking = Boeking::where('boekings_nummer', $boekingsNummer)
            ->with(['vlucht.luchtvaartmaatschappij', 'vlucht.gate'])
            ->firstOrFail();

        return Inertia::render('Boekingen/Show', [
            'boeking' => [
                'boekings_nummer'   => $boeking->boekings_nummer,
                'naam_reiziger'     => $boeking->naam_reiziger,
                'email_reiziger'    => $boeking->email_reiziger,
                'telefoon_reiziger' => $boeking->telefoon_reiziger,
                'stoelklasse'       => $boeking->stoelklasse,
                'stoel_voorkeur'    => $boeking->stoel_voorkeur,
                'stoel_nummer'      => $boeking->stoel_nummer,
                'prijs'             => $boeking->prijs,
                'status'            => $boeking->status,
                'aangemaakt_op'     => $boeking->created_at->format('d-m-Y H:i'),
                'vlucht' => [
                    'vlucht_nummer'          => $boeking->vlucht->vlucht_nummer,
                    'vertrek_luchthaven'     => $boeking->vlucht->vertrek_luchthaven,
                    'aankomst_luchthaven'    => $boeking->vlucht->aankomst_luchthaven,
                    'vertrek_tijd'           => $boeking->vlucht->vertrek_tijd->format('d-m-Y H:i'),
                    'aankomst_tijd'          => $boeking->vlucht->aankomst_tijd->format('d-m-Y H:i'),
                    'vliegtuig_type'         => $boeking->vlucht->vliegtuig_type,
                    'duur'                   => $boeking->vlucht->duur,
                    'luchtvaartmaatschappij' => $boeking->vlucht->luchtvaartmaatschappij?->naam,
                    'gate' => $boeking->vlucht->gate ? [
                        'nummer'   => $boeking->vlucht->gate->nummer,
                        'terminal' => $boeking->vlucht->gate->terminal,
                    ] : null,
                ],
            ],
        ]);
    }
}
