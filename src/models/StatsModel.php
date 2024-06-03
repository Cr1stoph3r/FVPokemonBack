<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class StatsModel extends Model
{
    // Especifica la tabla asociada a este modelo
    protected $table = 'stat';

    // Indica los campos que se pueden asignar masivamente
    protected $fillable = ['id', 'name', 'max_value'];

    // Desactiva los timestamps si no los necesitas
    public $timestamps = false;
}
