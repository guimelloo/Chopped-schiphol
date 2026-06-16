<?php

namespace Database\Seeders;

use App\Models\Boeking;
use App\Models\Coordinator;
use App\Models\Directeur;
use App\Models\Gate;
use App\Models\Luchtvaartmaatschappij;
use App\Models\Reiziger;
use App\Models\User;
use App\Models\Verlanglijst;
use App\Models\Vlucht;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── User ─────────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Test Gebruiker',
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        // ─── Luchtvaartmaatschappijen ─────────────────────────────────────────
        $klm = Luchtvaartmaatschappij::create([
            'naam'      => 'KLM Royal Dutch Airlines',
            'iata_code' => 'KL',
            'land'      => 'Nederland',
            'logo_url'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/KLM_logo.svg/120px-KLM_logo.svg.png',
        ]);
        $transavia = Luchtvaartmaatschappij::create([
            'naam'      => 'Transavia',
            'iata_code' => 'HV',
            'land'      => 'Nederland',
            'logo_url'  => null,
        ]);
        $easyjet = Luchtvaartmaatschappij::create([
            'naam'      => 'easyJet',
            'iata_code' => 'U2',
            'land'      => 'Verenigd Koninkrijk',
            'logo_url'  => null,
        ]);
        $tui = Luchtvaartmaatschappij::create([
            'naam'      => 'TUI fly',
            'iata_code' => 'TB',
            'land'      => 'Nederland',
            'logo_url'  => null,
        ]);
        $lufthansa = Luchtvaartmaatschappij::create([
            'naam'      => 'Lufthansa',
            'iata_code' => 'LH',
            'land'      => 'Duitsland',
            'logo_url'  => null,
        ]);

        // ─── Gates ────────────────────────────────────────────────────────────
        $gateD42 = Gate::create(['nummer' => 'D42', 'terminal' => 'D', 'type' => 'uitgebreid']);
        $gateB22 = Gate::create(['nummer' => 'B22', 'terminal' => 'B', 'type' => 'standaard']);
        $gateF16 = Gate::create(['nummer' => 'F16', 'terminal' => 'F', 'type' => 'standaard']);
        $gateE10 = Gate::create(['nummer' => 'E10', 'terminal' => 'E', 'type' => 'standaard']);
        $gateC05 = Gate::create(['nummer' => 'C05', 'terminal' => 'C', 'type' => 'standaard']);
        $gateD18 = Gate::create(['nummer' => 'D18', 'terminal' => 'D', 'type' => 'uitgebreid']);
        $gateA03 = Gate::create(['nummer' => 'A03', 'terminal' => 'A', 'type' => 'standaard']);
        $gateG01 = Gate::create(['nummer' => 'G01', 'terminal' => 'G', 'type' => 'uitgebreid']);

        // ─── Vluchten ─────────────────────────────────────────────────────────
        $today = now();

        $vluchtenData = [
            // Vandaag
            [
                'vlucht_nummer'             => 'KL1073',
                'luchtvaartmaatschappij_id' => $klm->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Londen Heathrow (LHR)',
                'vertrek_tijd'              => $today->copy()->setTime(7, 30),
                'aankomst_tijd'             => $today->copy()->setTime(8, 45),
                'vliegtuig_type'            => 'Airbus A320',
                'prijs_economy'             => 89.00,
                'prijs_business'            => 299.00,
                'stoelen_economy'           => 120,
                'stoelen_business'          => 20,
                'services'                  => ['Maaltijd', 'Handbagage', 'Entertainment', 'Wi-Fi'],
                'gate_id'                   => $gateD42->id,
                'status'                    => 'vertrokken',
            ],
            [
                'vlucht_nummer'             => 'HV5432',
                'luchtvaartmaatschappij_id' => $transavia->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Barcelona El Prat (BCN)',
                'vertrek_tijd'              => $today->copy()->setTime(9, 0),
                'aankomst_tijd'             => $today->copy()->setTime(11, 30),
                'vliegtuig_type'            => 'Boeing 737-800',
                'prijs_economy'             => 59.00,
                'prijs_business'            => 189.00,
                'stoelen_economy'           => 160,
                'stoelen_business'          => 16,
                'services'                  => ['Snack', 'Handbagage'],
                'gate_id'                   => $gateF16->id,
                'status'                    => 'gepland',
            ],
            [
                'vlucht_nummer'             => 'KL1807',
                'luchtvaartmaatschappij_id' => $klm->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Frankfurt (FRA)',
                'vertrek_tijd'              => $today->copy()->setTime(14, 15),
                'aankomst_tijd'             => $today->copy()->setTime(15, 30),
                'vliegtuig_type'            => 'Embraer 190',
                'prijs_economy'             => 69.00,
                'prijs_business'            => 219.00,
                'stoelen_economy'           => 96,
                'stoelen_business'          => 10,
                'services'                  => ['Maaltijd', 'Handbagage'],
                'gate_id'                   => $gateE10->id,
                'status'                    => 'gepland',
            ],
            [
                'vlucht_nummer'             => 'LH2344',
                'luchtvaartmaatschappij_id' => $lufthansa->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'München (MUC)',
                'vertrek_tijd'              => $today->copy()->setTime(17, 45),
                'aankomst_tijd'             => $today->copy()->setTime(19, 10),
                'vliegtuig_type'            => 'Airbus A321',
                'prijs_economy'             => 74.00,
                'prijs_business'            => 229.00,
                'stoelen_economy'           => 145,
                'stoelen_business'          => 16,
                'services'                  => ['Maaltijd', 'Handbagage', 'Entertainment'],
                'gate_id'                   => $gateA03->id,
                'status'                    => 'gepland',
            ],
            // Morgen
            [
                'vlucht_nummer'             => 'KL1075',
                'luchtvaartmaatschappij_id' => $klm->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Parijs Charles de Gaulle (CDG)',
                'vertrek_tijd'              => $today->copy()->addDays(1)->setTime(10, 0),
                'aankomst_tijd'             => $today->copy()->addDays(1)->setTime(11, 30),
                'vliegtuig_type'            => 'Boeing 737-800',
                'prijs_economy'             => 79.00,
                'prijs_business'            => 249.00,
                'stoelen_economy'           => 150,
                'stoelen_business'          => 24,
                'services'                  => ['Maaltijd', 'Handbagage', 'Entertainment'],
                'gate_id'                   => $gateB22->id,
                'status'                    => 'gepland',
            ],
            [
                'vlucht_nummer'             => 'KL0601',
                'luchtvaartmaatschappij_id' => $klm->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'New York JFK (JFK)',
                'vertrek_tijd'              => $today->copy()->addDays(1)->setTime(9, 15),
                'aankomst_tijd'             => $today->copy()->addDays(1)->setTime(12, 30),
                'vliegtuig_type'            => 'Boeing 777-300ER',
                'prijs_economy'             => 459.00,
                'prijs_business'            => 1899.00,
                'stoelen_economy'           => 270,
                'stoelen_business'          => 35,
                'services'                  => ['Maaltijd', 'Handbagage', 'Entertainment', 'Wi-Fi', 'Lounge toegang'],
                'gate_id'                   => $gateD18->id,
                'status'                    => 'gepland',
            ],
            [
                'vlucht_nummer'             => 'U28341',
                'luchtvaartmaatschappij_id' => $easyjet->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Rome Fiumicino (FCO)',
                'vertrek_tijd'              => $today->copy()->addDays(1)->setTime(11, 30),
                'aankomst_tijd'             => $today->copy()->addDays(1)->setTime(14, 0),
                'vliegtuig_type'            => 'Airbus A319',
                'prijs_economy'             => 49.00,
                'prijs_business'            => 149.00,
                'stoelen_economy'           => 140,
                'stoelen_business'          => 12,
                'services'                  => ['Handbagage'],
                'gate_id'                   => $gateC05->id,
                'status'                    => 'gepland',
            ],
            [
                'vlucht_nummer'             => 'TB1842',
                'luchtvaartmaatschappij_id' => $tui->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Palma de Mallorca (PMI)',
                'vertrek_tijd'              => $today->copy()->addDays(1)->setTime(15, 0),
                'aankomst_tijd'             => $today->copy()->addDays(1)->setTime(17, 45),
                'vliegtuig_type'            => 'Boeing 737 MAX 8',
                'prijs_economy'             => 99.00,
                'prijs_business'            => 279.00,
                'stoelen_economy'           => 170,
                'stoelen_business'          => 18,
                'services'                  => ['Maaltijd', 'Handbagage', 'Entertainment'],
                'gate_id'                   => $gateG01->id,
                'status'                    => 'gepland',
            ],
            // Overmorgen
            [
                'vlucht_nummer'             => 'HV6120',
                'luchtvaartmaatschappij_id' => $transavia->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Malaga (AGP)',
                'vertrek_tijd'              => $today->copy()->addDays(2)->setTime(6, 45),
                'aankomst_tijd'             => $today->copy()->addDays(2)->setTime(10, 15),
                'vliegtuig_type'            => 'Boeing 737-700',
                'prijs_economy'             => 69.00,
                'prijs_business'            => 199.00,
                'stoelen_economy'           => 140,
                'stoelen_business'          => 12,
                'services'                  => ['Snack', 'Handbagage'],
                'gate_id'                   => $gateF16->id,
                'status'                    => 'gepland',
            ],
            [
                'vlucht_nummer'             => 'HV5501',
                'luchtvaartmaatschappij_id' => $transavia->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Madrid Barajas (MAD)',
                'vertrek_tijd'              => $today->copy()->addDays(2)->setTime(13, 0),
                'aankomst_tijd'             => $today->copy()->addDays(2)->setTime(15, 50),
                'vliegtuig_type'            => 'Boeing 737-800',
                'prijs_economy'             => 74.00,
                'prijs_business'            => 224.00,
                'stoelen_economy'           => 155,
                'stoelen_business'          => 16,
                'services'                  => ['Snack', 'Handbagage', 'Wi-Fi'],
                'gate_id'                   => $gateE10->id,
                'status'                    => 'gepland',
            ],
            [
                'vlucht_nummer'             => 'U29102',
                'luchtvaartmaatschappij_id' => $easyjet->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Lissabon (LIS)',
                'vertrek_tijd'              => $today->copy()->addDays(3)->setTime(8, 0),
                'aankomst_tijd'             => $today->copy()->addDays(3)->setTime(10, 45),
                'vliegtuig_type'            => 'Airbus A320neo',
                'prijs_economy'             => 55.00,
                'prijs_business'            => 165.00,
                'stoelen_economy'           => 165,
                'stoelen_business'          => 12,
                'services'                  => ['Handbagage', 'Snack'],
                'gate_id'                   => $gateB22->id,
                'status'                    => 'gepland',
            ],
            [
                'vlucht_nummer'             => 'LH5890',
                'luchtvaartmaatschappij_id' => $lufthansa->id,
                'vertrek_luchthaven'        => 'Amsterdam Schiphol (AMS)',
                'aankomst_luchthaven'       => 'Singapore Changi (SIN)',
                'vertrek_tijd'              => $today->copy()->addDays(4)->setTime(22, 0),
                'aankomst_tijd'             => $today->copy()->addDays(5)->setTime(16, 30),
                'vliegtuig_type'            => 'Airbus A380',
                'prijs_economy'             => 680.00,
                'prijs_business'            => 2499.00,
                'stoelen_economy'           => 400,
                'stoelen_business'          => 60,
                'services'                  => ['Maaltijd', 'Handbagage', 'Entertainment', 'Wi-Fi', 'Lounge toegang', 'Flat bed'],
                'gate_id'                   => $gateG01->id,
                'status'                    => 'gepland',
            ],
        ];

        $vluchten = [];
        foreach ($vluchtenData as $data) {
            $vluchten[] = Vlucht::create($data);
        }

        // ─── Coordinatoren ────────────────────────────────────────────────────
        $janDeVries = Coordinator::create([
            'naam'                     => 'Jan de Vries',
            'gebruikersnaam'           => 'jan.devries',
            'email'                    => 'jan.devries@schiphol.nl',
            'wachtwoord'               => 'wachtwoord123',
            'luchtvaartmaatschappij_id' => $klm->id,
            'actief'                   => true,
        ]);
        $mariaVanDijk = Coordinator::create([
            'naam'                     => 'Maria van Dijk',
            'gebruikersnaam'           => 'maria.vandijk',
            'email'                    => 'maria.vandijk@schiphol.nl',
            'wachtwoord'               => 'wachtwoord456',
            'luchtvaartmaatschappij_id' => $transavia->id,
            'actief'                   => true,
        ]);
        $beheerAdmin = Coordinator::create([
            'naam'                     => 'Beheer Schiphol',
            'gebruikersnaam'           => 'admin',
            'email'                    => 'admin@schiphol.nl',
            'wachtwoord'               => 'admin123',
            'luchtvaartmaatschappij_id' => null,
            'actief'                   => true,
        ]);
        Coordinator::create([
            'naam'                     => 'Sophie Bakker',
            'gebruikersnaam'           => 'sophie.bakker',
            'email'                    => 'sophie.bakker@schiphol.nl',
            'wachtwoord'               => 'sophie789',
            'luchtvaartmaatschappij_id' => $easyjet->id,
            'actief'                   => false,
        ]);

        // ─── Directeur ────────────────────────────────────────────────────────
        Directeur::create([
            'naam'           => 'Pieter Smit',
            'gebruikersnaam' => 'directeur',
            'wachtwoord'     => 'directeur123',
        ]);

        // ─── Reizigers ────────────────────────────────────────────────────────
        $reizigers = [
            Reiziger::create(['naam' => 'Emma Janssen',   'email' => 'emma@example.com',   'telefoon' => '+31 6 12345678', 'paspoort_nummer' => 'NL123456789']),
            Reiziger::create(['naam' => 'Thomas de Boer', 'email' => 'thomas@example.com', 'telefoon' => '+31 6 87654321', 'paspoort_nummer' => 'NL987654321']),
            Reiziger::create(['naam' => 'Lisa Vermeer',   'email' => 'lisa@example.com',   'telefoon' => '+31 6 55566677', 'paspoort_nummer' => 'NL456789123']),
            Reiziger::create(['naam' => 'Mark Hofman',    'email' => 'mark@example.com',   'telefoon' => '+31 6 99988877', 'paspoort_nummer' => 'NL321654987']),
            Reiziger::create(['naam' => 'Anna Pieters',   'email' => 'anna@example.com',   'telefoon' => '+31 6 11122233', 'paspoort_nummer' => 'NL741852963']),
        ];

        // ─── Boekingen ────────────────────────────────────────────────────────
        $boekingData = [
            // Vlucht 0 = KL1073 (vandaag, vertrokken)
            ['vlucht' => 0, 'reiziger' => 0, 'klasse' => 'economy',  'voorkeur' => 'raam',    'status' => 'bevestigd',    'num' => 'SCH-2024001'],
            ['vlucht' => 0, 'reiziger' => 1, 'klasse' => 'business', 'voorkeur' => 'gangpad', 'status' => 'bevestigd',    'num' => 'SCH-2024002'],
            ['vlucht' => 0, 'reiziger' => 2, 'klasse' => 'economy',  'voorkeur' => 'midden',  'status' => 'bevestigd',    'num' => 'SCH-2024003'],
            // Vlucht 1 = HV5432 (vandaag)
            ['vlucht' => 1, 'reiziger' => 3, 'klasse' => 'economy',  'voorkeur' => 'raam',    'status' => 'bevestigd',    'num' => 'SCH-2024004'],
            ['vlucht' => 1, 'reiziger' => 4, 'klasse' => 'economy',  'voorkeur' => '',        'status' => 'in_afwachting','num' => 'SCH-2024005'],
            // Vlucht 4 = KL1075 (morgen)
            ['vlucht' => 4, 'reiziger' => 0, 'klasse' => 'business', 'voorkeur' => 'raam',    'status' => 'bevestigd',    'num' => 'SCH-2024006'],
            ['vlucht' => 4, 'reiziger' => 2, 'klasse' => 'economy',  'voorkeur' => 'gangpad', 'status' => 'bevestigd',    'num' => 'SCH-2024007'],
            // Vlucht 5 = KL0601 (New York, morgen)
            ['vlucht' => 5, 'reiziger' => 1, 'klasse' => 'business', 'voorkeur' => 'raam',    'status' => 'bevestigd',    'num' => 'SCH-2024008'],
            ['vlucht' => 5, 'reiziger' => 3, 'klasse' => 'economy',  'voorkeur' => 'midden',  'status' => 'bevestigd',    'num' => 'SCH-2024009'],
            ['vlucht' => 5, 'reiziger' => 4, 'klasse' => 'economy',  'voorkeur' => '',        'status' => 'geannuleerd',  'num' => 'SCH-2024010'],
            // Vlucht 6 = U28341 (Rome, morgen)
            ['vlucht' => 6, 'reiziger' => 0, 'klasse' => 'economy',  'voorkeur' => 'gangpad', 'status' => 'bevestigd',    'num' => 'SCH-2024011'],
            // Vlucht 8 = HV6120 (Malaga, overmorgen)
            ['vlucht' => 8, 'reiziger' => 2, 'klasse' => 'economy',  'voorkeur' => 'raam',    'status' => 'bevestigd',    'num' => 'SCH-2024012'],
            ['vlucht' => 8, 'reiziger' => 1, 'klasse' => 'economy',  'voorkeur' => 'midden',  'status' => 'bevestigd',    'num' => 'SCH-2024013'],
        ];

        foreach ($boekingData as $bd) {
            $vlucht  = $vluchten[$bd['vlucht']];
            $reiziger = $reizigers[$bd['reiziger']];
            $prijs   = $bd['klasse'] === 'business' ? $vlucht->prijs_business : $vlucht->prijs_economy;

            Boeking::create([
                'boekings_nummer'   => $bd['num'],
                'vlucht_id'         => $vlucht->id,
                'reiziger_id'       => $reiziger->id,
                'stoelklasse'       => $bd['klasse'],
                'stoel_voorkeur'    => $bd['voorkeur'] ?: null,
                'prijs'             => $prijs,
                'status'            => $bd['status'],
                'naam_reiziger'     => $reiziger->naam,
                'email_reiziger'    => $reiziger->email,
                'telefoon_reiziger' => $reiziger->telefoon,
            ]);
        }

        // ─── Verlanglijst (voor jan.devries) ──────────────────────────────────
        Verlanglijst::create([
            'coordinator_id'    => $janDeVries->id,
            'maatschappij_naam' => 'KLM',
            'bestemming'        => 'Tokyo Narita (NRT)',
            'gewenste_datum'    => now()->addMonths(2)->format('Y-m-d'),
            'stoelklasse'       => 'economy',
            'prioriteit'        => 'hoog',
            'opmerkingen'       => 'Hoge vraag vanuit reisbureaus. Zo snel mogelijk toevoegen.',
        ]);
        Verlanglijst::create([
            'coordinator_id'    => $janDeVries->id,
            'maatschappij_naam' => 'Transavia',
            'bestemming'        => 'Dubai (DXB)',
            'gewenste_datum'    => now()->addMonths(3)->format('Y-m-d'),
            'stoelklasse'       => 'business',
            'prioriteit'        => 'normaal',
            'opmerkingen'       => null,
        ]);
        Verlanglijst::create([
            'coordinator_id'    => $mariaVanDijk->id,
            'maatschappij_naam' => null,
            'bestemming'        => 'Athene (ATH)',
            'gewenste_datum'    => null,
            'stoelklasse'       => 'economy',
            'prioriteit'        => 'laag',
            'opmerkingen'       => 'Zomer seizoen interessant',
        ]);
    }
}
