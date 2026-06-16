<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Directeur extends Authenticatable
{
    use Notifiable;

    protected $table = 'directeuren';

    protected $fillable = ['naam', 'gebruikersnaam', 'wachtwoord'];

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
}
