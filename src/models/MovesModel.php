<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class MovesModel extends Model
{
    protected $table = 'move';

    protected $fillable = ['id','name'];

    public $timestamps = false;
}
