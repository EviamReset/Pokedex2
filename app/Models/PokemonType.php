<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PokemonType extends Model
{
    protected $table = 'pokemon_types';
    protected $fillable = ['pokemon_id', 'type_id'];
    public $timestamps = false;
}