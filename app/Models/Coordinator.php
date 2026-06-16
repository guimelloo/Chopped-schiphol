<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Coordinator extends Authenticatable
{
    use Notifiable;

    protected $table = 'coordinatoren';

    protected $fillable = ['naam', 'gebruikersnaam', 'wachtwoord', 'luchtvaartmaatschappij_id'];

    protected $hidden = ['wachtwoord', 'remember_token'];

    protected function casts(): array
    {
        return [
            'wachtwoord' => 'hashed',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'wachtwoord';
    }

    public function luchtvaartmaatschappij(): BelongsTo
    {
        return $this->belongsTo(Luchtvaartmaatschappij::class);
    }
}
