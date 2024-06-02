<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class PokemonAbilityModel extends Model
{
    protected $table = 'pokemon_ability';

    protected $fillable = ['pokemon_id', 'ability_id', 'is_hidden', 'slot'];

    public $timestamps = false;
}
