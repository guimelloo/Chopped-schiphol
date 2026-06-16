<?php

namespace Database\Seeders;

use App\Models\Boeking;
use App\Models\Coordinator;
use App\Models\Directeur;
use App\Models\Gate;
use App\Models\Luchtvaartmaatschappij;
use App\Models\Reiziger;
use App\Models\User;
use App\Models\Vlucht;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name'  => 'Test Gebruiker',
            'email' => 'test@example.com',
        ]);

        // Luchtvaartmaatschappijen
        $klm = Luchtvaartmaatschappij::create([
            'naam'      => 'KLM Royal Dutch Airlines',
            'iata_code' => 'KL',
            'land'      => 'Nederland',
        ]);
        $transavia = Luchtvaartmaatschappij::create([
            'naam'      => 'Transavia',
            'iata_code' => 'HV',
            'land'      => 'Nederland',
        ]);
        $easyjet = Luchtvaartmaatschappij::create([
            'naam'      => 'easyJet',
            'iata_code' => 'U2',
            'land'      => 'Verenigd Koninkrijk',
        ]);
        $tui = Luchtvaartmaatschappij::create([
            'naam'      => 'TUI fly',
            'iata_code' => 'TB',
            'land'      => 'Nederland',
        ]);

        // Gates
        $gateD42 = Gate::create(['nummer' => 'D42', 'terminal' => 'D']);
        $gateB22 = Gate::create(['nummer' => 'B22', 'terminal' => 'B']);
        $gateF16 = Gate::create(['nummer' => 'F16', 'terminal' => 'F']);
        $gateE10 = Gate::create(['nummer' => 'E10', 'terminal' => 'E']);
        $gateC05 = Gate::create(['nummer' => 'C05', 'terminal' => 'C']);
        $gateD18 = Gate::create(['nummer' => 'D18', 'terminal' => 'D']);

        // Vluchten
        $vluchten = [
            [
                'vlucht_nummer'           => 'KL1073',
                'luchtvaartmaatschappij_id' => $klm->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Londen Heathrow (LHR)',
                'vertrek_tijd'            => now()->addDays(1)->setTime(7, 30),
                'aankomst_tijd'           => now()->addDays(1)->setTime(8, 45),
                'vliegtuig_type'          => 'Airbus A320',
                'prijs_economy'           => 89.00,
                'prijs_business'          => 299.00,
                'stoelen_economy'         => 120,
                'stoelen_business'        => 20,
                'services'                => ['Maaltijd', 'Handbagage', 'Entertainment', 'Wi-Fi'],
                'gate_id'                 => $gateD42->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'KL1075',
                'luchtvaartmaatschappij_id' => $klm->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Parijs Charles de Gaulle (CDG)',
                'vertrek_tijd'            => now()->addDays(1)->setTime(10, 0),
                'aankomst_tijd'           => now()->addDays(1)->setTime(11, 30),
                'vliegtuig_type'          => 'Boeing 737-800',
                'prijs_economy'           => 79.00,
                'prijs_business'          => 249.00,
                'stoelen_economy'         => 150,
                'stoelen_business'        => 24,
                'services'                => ['Maaltijd', 'Handbagage', 'Entertainment'],
                'gate_id'                 => $gateB22->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'KL0601',
                'luchtvaartmaatschappij_id' => $klm->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'New York JFK (JFK)',
                'vertrek_tijd'            => now()->addDays(2)->setTime(9, 15),
                'aankomst_tijd'           => now()->addDays(2)->setTime(12, 30),
                'vliegtuig_type'          => 'Boeing 777-300ER',
                'prijs_economy'           => 459.00,
                'prijs_business'          => 1899.00,
                'stoelen_economy'         => 270,
                'stoelen_business'        => 35,
                'services'                => ['Maaltijd', 'Handbagage', 'Entertainment', 'Wi-Fi', 'Lounge toegang'],
                'gate_id'                 => $gateD18->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'HV5432',
                'luchtvaartmaatschappij_id' => $transavia->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Barcelona El Prat (BCN)',
                'vertrek_tijd'            => now()->addDays(1)->setTime(6, 45),
                'aankomst_tijd'           => now()->addDays(1)->setTime(9, 15),
                'vliegtuig_type'          => 'Boeing 737-800',
                'prijs_economy'           => 59.00,
                'prijs_business'          => 189.00,
                'stoelen_economy'         => 160,
                'stoelen_business'        => 16,
                'services'                => ['Snack', 'Handbagage'],
                'gate_id'                 => $gateF16->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'HV6120',
                'luchtvaartmaatschappij_id' => $transavia->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Malaga (AGP)',
                'vertrek_tijd'            => now()->addDays(3)->setTime(14, 20),
                'aankomst_tijd'           => now()->addDays(3)->setTime(17, 45),
                'vliegtuig_type'          => 'Boeing 737-700',
                'prijs_economy'           => 69.00,
                'prijs_business'          => 199.00,
                'stoelen_economy'         => 140,
                'stoelen_business'        => 12,
                'services'                => ['Snack', 'Handbagage'],
                'gate_id'                 => $gateE10->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'U28341',
                'luchtvaartmaatschappij_id' => $easyjet->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Rome Fiumicino (FCO)',
                'vertrek_tijd'            => now()->addDays(2)->setTime(11, 30),
                'aankomst_tijd'           => now()->addDays(2)->setTime(14, 0),
                'vliegtuig_type'          => 'Airbus A319',
                'prijs_economy'           => 49.00,
                'prijs_business'          => 149.00,
                'stoelen_economy'         => 140,
                'stoelen_business'        => 12,
                'services'                => ['Handbagage'],
                'gate_id'                 => $gateC05->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'U29102',
                'luchtvaartmaatschappij_id' => $easyjet->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Lissabon (LIS)',
                'vertrek_tijd'            => now()->addDays(4)->setTime(8, 0),
                'aankomst_tijd'           => now()->addDays(4)->setTime(10, 45),
                'vliegtuig_type'          => 'Airbus A320neo',
                'prijs_economy'           => 55.00,
                'prijs_business'          => 165.00,
                'stoelen_economy'         => 165,
                'stoelen_business'        => 12,
                'services'                => ['Handbagage', 'Snack'],
                'gate_id'                 => $gateB22->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'TB1842',
                'luchtvaartmaatschappij_id' => $tui->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Palma de Mallorca (PMI)',
                'vertrek_tijd'            => now()->addDays(1)->setTime(15, 0),
                'aankomst_tijd'           => now()->addDays(1)->setTime(17, 45),
                'vliegtuig_type'          => 'Boeing 737 MAX 8',
                'prijs_economy'           => 99.00,
                'prijs_business'          => 279.00,
                'stoelen_economy'         => 170,
                'stoelen_business'        => 18,
                'services'                => ['Maaltijd', 'Handbagage', 'Entertainment'],
                'gate_id'                 => $gateD42->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'KL1807',
                'luchtvaartmaatschappij_id' => $klm->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Frankfurt (FRA)',
                'vertrek_tijd'            => now()->addDays(1)->setTime(17, 15),
                'aankomst_tijd'           => now()->addDays(1)->setTime(18, 30),
                'vliegtuig_type'          => 'Embraer 190',
                'prijs_economy'           => 69.00,
                'prijs_business'          => 219.00,
                'stoelen_economy'         => 96,
                'stoelen_business'        => 10,
                'services'                => ['Maaltijd', 'Handbagage'],
                'gate_id'                 => $gateE10->id,
                'status'                  => 'gepland',
            ],
            [
                'vlucht_nummer'           => 'HV5501',
                'luchtvaartmaatschappij_id' => $transavia->id,
                'vertrek_luchthaven'      => 'Amsterdam (AMS)',
                'aankomst_luchthaven'     => 'Madrid Barajas (MAD)',
                'vertrek_tijd'            => now()->addDays(2)->setTime(13, 0),
                'aankomst_tijd'           => now()->addDays(2)->setTime(15, 50),
                'vliegtuig_type'          => 'Boeing 737-800',
                'prijs_economy'           => 74.00,
                'prijs_business'          => 224.00,
                'stoelen_economy'         => 155,
                'stoelen_business'        => 16,
                'services'                => ['Snack', 'Handbagage', 'Wi-Fi'],
                'gate_id'                 => $gateF16->id,
                'status'                  => 'gepland',
            ],
        ];

        foreach ($vluchten as $data) {
            Vlucht::create($data);
        }

        // Coordinatoren (wachtwoord gehasht via bcrypt via model cast)
        Coordinator::create([
            'naam'                    => 'Jan de Vries',
            'gebruikersnaam'          => 'jan.devries',
            'wachtwoord'              => 'wachtwoord123',
            'luchtvaartmaatschappij_id' => $klm->id,
        ]);
        Coordinator::create([
            'naam'                    => 'Maria van Dijk',
            'gebruikersnaam'          => 'maria.vandijk',
            'wachtwoord'              => 'wachtwoord456',
            'luchtvaartmaatschappij_id' => $transavia->id,
        ]);
        Coordinator::create([
            'naam'            => 'Beheer Schiphol',
            'gebruikersnaam'  => 'admin',
            'wachtwoord'      => 'admin123',
            'luchtvaartmaatschappij_id' => null,
        ]);

        // Directeur
        Directeur::create([
            'naam'           => 'Pieter Smit',
            'gebruikersnaam' => 'pieter.smit',
            'wachtwoord'     => Hash::make('directeur123'),
        ]);

        // Reizigers
        $reiziger1 = Reiziger::create([
            'naam'            => 'Emma Janssen',
            'email'           => 'emma@example.com',
            'telefoon'        => '+31 6 12345678',
            'paspoort_nummer' => 'NL123456789',
        ]);
        $reiziger2 = Reiziger::create([
            'naam'            => 'Thomas de Boer',
            'email'           => 'thomas@example.com',
            'telefoon'        => '+31 6 87654321',
            'paspoort_nummer' => 'NL987654321',
        ]);

        // Voorbeeldboekingen
        $eersteVlucht = Vlucht::where('vlucht_nummer', 'KL1073')->first();
        if ($eersteVlucht) {
            Boeking::create([
                'boekings_nummer'   => 'SCH-DEMO0001',
                'vlucht_id'         => $eersteVlucht->id,
                'reiziger_id'       => $reiziger1->id,
                'stoelklasse'       => 'economy',
                'stoel_voorkeur'    => 'raam',
                'prijs'             => $eersteVlucht->prijs_economy,
                'status'            => 'bevestigd',
                'naam_reiziger'     => $reiziger1->naam,
                'email_reiziger'    => $reiziger1->email,
                'telefoon_reiziger' => $reiziger1->telefoon,
            ]);
            Boeking::create([
                'boekings_nummer'   => 'SCH-DEMO0002',
                'vlucht_id'         => $eersteVlucht->id,
                'reiziger_id'       => $reiziger2->id,
                'stoelklasse'       => 'business',
                'stoel_voorkeur'    => 'gangpad',
                'prijs'             => $eersteVlucht->prijs_business,
                'status'            => 'bevestigd',
                'naam_reiziger'     => $reiziger2->naam,
                'email_reiziger'    => $reiziger2->email,
                'telefoon_reiziger' => $reiziger2->telefoon,
            ]);
        }
    }
}
