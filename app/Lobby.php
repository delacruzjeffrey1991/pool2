<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lobby extends Model
{
    protected $guarded = ['id'];

    // protected $hidden = [
    //   'updated_at',
    //   'created_by',
    //   'has_player_accept',
    //   'player_accepted_at',
    //   'has_opponent_accept',
    //   'opponent_accepted_at',
    // ];

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

    protected $player_fields = [
      'id',
      'player_id',
      'lobby_id',
      'is_winner',
      'created_at'
    ];

    public function creator() {
      return $this->belongsTo('App\Player', 'created_by')->select($this->user_fields);
    }

    public function players() {
      return $this->hasMany('App\LobbyPlayer')->select($this->player_fields);
    }

}
