<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class PokemonTypeModel extends Model
{
    protected $table = 'pokemon_type';

    protected $fillable = ['pokemon_id', 'type_id'];

    public $timestamps = false;
}
