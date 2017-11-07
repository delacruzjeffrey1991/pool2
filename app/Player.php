<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
      // 'session_ticket',
      'password',
      'api_key',
      'player_meta',
      'player_payment_meta',
      'android_device_id',
      'ios_device_id'
    ];

    public function eula() {
      return $this->belongsTo('App\Eula', 'eula_id');
    }
}
