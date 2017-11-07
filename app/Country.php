<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    protected $guarded = ['id'];

    protected $eula_fields = [
      'id',
      'country_id',
      'version',
      'text',
      'is_default',
      'created_at'
    ];

    public function eulas() {
      return $this->hasMany('App\Eula', 'country_id')->select($eula_fields);
    }

    public function getStatesAttribute($value) {
      return unserialize($value);
    }

}
