<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reiziger extends Model
{
    protected $table = 'reizigers';

    protected $fillable = ['naam', 'email', 'telefoon', 'paspoort_nummer'];

    public function boekingen(): HasMany
    {
        return $this->hasMany(Boeking::class);
    }
}
