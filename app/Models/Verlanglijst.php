<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verlanglijst extends Model
{
    protected $table = 'verlanglijsten';

    protected $fillable = [
        'coordinator_id',
        'maatschappij_naam',
        'bestemming',
        'gewenste_datum',
        'stoelklasse',
        'opmerkingen',
        'prioriteit',
    ];

    protected function casts(): array
    {
        return [
            'gewenste_datum' => 'date:Y-m-d',
        ];
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class);
    }
}
