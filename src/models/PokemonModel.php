<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class PokemonModel extends Model
{
    protected $table = 'pokemon';

    protected $fillable = [
        'id', 'name', 'hp', 'attack', 'defense', 'special_attack', 'special_defense', 'speed',
        'weight','url_img', 'evolves_from', 'evolution_order', 'color_id', 'habitat_id'
    ];

    public $timestamps = false;

    public function color()
    {
        return $this->belongsTo(ColorsModel::class, 'color_id');
    }

    public function habitat()
    {
        return $this->belongsTo(HabitatModel::class, 'habitat_id');
    }

    public function evolvesFrom()
    {
        return $this->belongsTo(PokemonModel::class, 'evolves_from');
    }

    public function evolvesTo()
    {
        return $this->hasMany(PokemonModel::class, 'evolves_from');
    }

    public function types()
    {
        return $this->belongsToMany(TypeModel::class, 'pokemon_type', 'pokemon_id', 'type_id');
    }

    public function abilities()
    {
        return $this->belongsToMany(AbilityModel::class, 'pokemon_ability', 'pokemon_id', 'ability_id')
                    ->withPivot('is_hidden', 'slot');
    }

    public function moves()
    {
        return $this->belongsToMany(MovesModel::class, 'pokemon_move', 'pokemon_id', 'move_id');
    }
}

