<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vlucht extends Model
{
    protected $table = 'vluchten';

    protected $fillable = [
        'vlucht_nummer',
        'luchtvaartmaatschappij_id',
        'vertrek_luchthaven',
        'aankomst_luchthaven',
        'vertrek_tijd',
        'aankomst_tijd',
        'vliegtuig_type',
        'prijs_economy',
        'prijs_business',
        'stoelen_economy',
        'stoelen_business',
        'services',
        'gate_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'vertrek_tijd'   => 'datetime',
            'aankomst_tijd'  => 'datetime',
            'services'       => 'array',
            'prijs_economy'  => 'decimal:2',
            'prijs_business' => 'decimal:2',
        ];
    }

    public function luchtvaartmaatschappij(): BelongsTo
    {
        return $this->belongsTo(Luchtvaartmaatschappij::class);
    }

    public function gate(): BelongsTo
    {
        return $this->belongsTo(Gate::class);
    }

    public function boekingen(): HasMany
    {
        return $this->hasMany(Boeking::class);
    }

    public function getDuurAttribute(): string
    {
        $minuten = $this->vertrek_tijd->diffInMinutes($this->aankomst_tijd);
        $uren    = (int) floor($minuten / 60);
        $rest    = $minuten % 60;

        return "{$uren}u {$rest}m";
    }

    public function getBeschikbaarEconomyAttribute(): int
    {
        $geboekt = $this->boekingen()->where('stoelklasse', 'economy')->count();

        return max(0, $this->stoelen_economy - $geboekt);
    }

    public function getBeschikbaarBusinessAttribute(): int
    {
        $geboekt = $this->boekingen()->where('stoelklasse', 'business')->count();

        return max(0, $this->stoelen_business - $geboekt);
    }
}
