<?php

namespace App\Http\Controllers;

use App\Models\Vlucht;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VluchtController extends Controller
{
    public function index(Request $request): Response
    {
        $klasse = $request->get('klasse', 'alle');

        $vluchten = Vlucht::with(['luchtvaartmaatschappij', 'gate'])
            ->when($klasse === 'economy', fn ($q) => $q->where('stoelen_economy', '>', 0))
            ->when($klasse === 'business', fn ($q) => $q->where('stoelen_business', '>', 0))
            ->where('status', '!=', 'geannuleerd')
            ->orderBy('vertrek_tijd')
            ->get()
            ->map(fn (Vlucht $v) => $this->formatVlucht($v));

        return Inertia::render('Vluchten/Index', [
            'vluchten'      => $vluchten,
            'actieveKlasse' => $klasse,
        ]);
    }

    public function show(Vlucht $vlucht): Response
    {
        $vlucht->load(['luchtvaartmaatschappij', 'gate']);

        return Inertia::render('Vluchten/Show', [
            'vlucht' => $this->formatVlucht($vlucht),
        ]);
    }

    public function zoek(Request $request): Response
    {
        $resultaten = [];

        if ($request->filled('vertrek_datum')) {
            $query = Vlucht::with(['luchtvaartmaatschappij', 'gate'])
                ->whereDate('vertrek_tijd', $request->vertrek_datum)
                ->where('status', '!=', 'geannuleerd');

            if ($request->filled('vertrek_luchthaven')) {
                $query->where('vertrek_luchthaven', 'like', '%' . $request->vertrek_luchthaven . '%');
            }

            if ($request->filled('aankomst_luchthaven')) {
                $query->where('aankomst_luchthaven', 'like', '%' . $request->aankomst_luchthaven . '%');
            }

            if ($request->filled('stoelklasse') && in_array($request->stoelklasse, ['economy', 'business'])) {
                $kolom = 'stoelen_' . $request->stoelklasse;
                $query->where($kolom, '>', 0);
            }

            $resultaten = $query->orderBy('vertrek_tijd')
                ->get()
                ->map(fn (Vlucht $v) => $this->formatVlucht($v));
        }

        return Inertia::render('Vluchten/Zoek', [
            'resultaten'  => $resultaten,
            'zoekwaarden' => $request->only([
                'vertrek_luchthaven',
                'aankomst_luchthaven',
                'vertrek_datum',
                'stoelklasse',
                'stoel_voorkeur',
            ]),
        ]);
    }

    private function formatVlucht(Vlucht $vlucht): array
    {
        return [
            'id'                      => $vlucht->id,
            'vlucht_nummer'           => $vlucht->vlucht_nummer,
            'vertrek_luchthaven'      => $vlucht->vertrek_luchthaven,
            'aankomst_luchthaven'     => $vlucht->aankomst_luchthaven,
            'vertrek_tijd'            => $vlucht->vertrek_tijd->format('Y-m-d H:i'),
            'aankomst_tijd'           => $vlucht->aankomst_tijd->format('Y-m-d H:i'),
            'vliegtuig_type'          => $vlucht->vliegtuig_type,
            'duur'                    => $vlucht->duur,
            'prijs_economy'           => $vlucht->prijs_economy,
            'prijs_business'          => $vlucht->prijs_business,
            'beschikbaar_economy'     => $vlucht->beschikbaar_economy,
            'beschikbaar_business'    => $vlucht->beschikbaar_business,
            'services'                => $vlucht->services ?? [],
            'status'                  => $vlucht->status,
            'luchtvaartmaatschappij'  => $vlucht->luchtvaartmaatschappij ? [
                'naam'      => $vlucht->luchtvaartmaatschappij->naam,
                'iata_code' => $vlucht->luchtvaartmaatschappij->iata_code,
            ] : null,
            'gate' => $vlucht->gate ? [
                'nummer'   => $vlucht->gate->nummer,
                'terminal' => $vlucht->gate->terminal,
            ] : null,
        ];
    }
}
