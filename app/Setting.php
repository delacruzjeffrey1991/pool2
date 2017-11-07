<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['id', 'created_at', 'updated_at'];

}
