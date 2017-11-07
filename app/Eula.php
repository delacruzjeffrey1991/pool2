<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eula extends Model
{
  protected $guarded = ['id'];

  protected $eula_fields = [
    'id',
    'version',
    'text',
    'created_at'
  ];

  public function country() {
    return $this->belongsTo('App/Country')->select($eula_fields);
  }

  public function players() {
    return $this->belongsTo('App/Player', 'eula_id');
  }


}
