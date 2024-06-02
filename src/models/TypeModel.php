<?php

namespace Fv\Back\Models;

use Illuminate\Database\Eloquent\Model;

class TypeModel extends Model
{
    protected $table = 'type';

    protected $fillable = ['id','name'];

    public $timestamps = false;
}
