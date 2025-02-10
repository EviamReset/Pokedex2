<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function pokemons()
    {
        return $this->belongsToMany(Pokemon::class, 'pokemon_types', 'type_id', 'pokemon_id');
    }
}