<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class AbilityModel extends Model
{
    protected $table = 'ability';

    protected $fillable = ['id','name'];

    public $timestamps = false;
}
