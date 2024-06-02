<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class PokemonMoveModel extends Model
{
    protected $table = 'pokemon_move';

    protected $fillable = ['pokemon_id', 'move_id'];

    public $timestamps = false;
}
