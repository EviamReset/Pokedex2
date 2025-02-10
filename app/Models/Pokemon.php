<?php

namespace App\Models;

use App\Models\Type;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemons';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function types()
    {
        return $this->belongsToMany(Type::class, 'pokemon_types', 'pokemon_id', 'type_id')
                    ->withPivot('id');
    }
}