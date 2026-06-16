<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gate extends Model
{
    protected $fillable = ['nummer', 'terminal', 'type'];

    public function vluchten(): HasMany
    {
        return $this->hasMany(Vlucht::class);
    }
}
