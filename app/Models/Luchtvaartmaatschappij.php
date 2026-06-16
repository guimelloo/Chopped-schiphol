<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Luchtvaartmaatschappij extends Model
{
    protected $table = 'luchtvaartmaatschappijen';

    protected $fillable = ['naam', 'iata_code', 'land', 'logo_url'];

    public function vluchten(): HasMany
    {
        return $this->hasMany(Vlucht::class);
    }

    public function coordinatoren(): HasMany
    {
        return $this->hasMany(Coordinator::class);
    }
}
