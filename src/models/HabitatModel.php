<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class HabitatModel extends Model
{
    protected $table = 'habitat';

    protected $fillable = ['id','name'];

    public $timestamps = false;
}
