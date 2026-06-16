<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Boeking extends Model
{
    protected $table = 'boekingen';

    protected $fillable = [
        'boekings_nummer',
        'vlucht_id',
        'reiziger_id',
        'stoelklasse',
        'stoel_voorkeur',
        'stoel_nummer',
        'prijs',
        'status',
        'naam_reiziger',
        'email_reiziger',
        'telefoon_reiziger',
    ];

    protected function casts(): array
    {
        return [
            'prijs' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Boeking $boeking) {
            if (empty($boeking->boekings_nummer)) {
                $boeking->boekings_nummer = 'SCH-' . strtoupper(Str::random(8));
            }
        });
    }

    public function vlucht(): BelongsTo
    {
        return $this->belongsTo(Vlucht::class);
    }

    public function reiziger(): BelongsTo
    {
        return $this->belongsTo(Reiziger::class);
    }
}
