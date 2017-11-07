<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    protected $guarded = ['id'];

    protected $user_fields = [
      'id',
      'playfab_id',
      'firstname',
      'lastname',
      'username',
      'total_won',
      'total_lose',
      'percentage_won',
      'percentage_lose',
      'avatar',
      'virtual_chips_total',
      'real_cash_total',
      'last_login'
    ];

    public function friend() {
      return $this->belongsTo('App\Player', 'friend_id')->select($this->user_fields);
    }

}
