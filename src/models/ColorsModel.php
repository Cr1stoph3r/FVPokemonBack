<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class ColorsModel extends Model
{
    protected $table = 'color';

    protected $fillable = ['id','name'];

    public $timestamps = false;
}
