<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LobbyPlayer extends Model
{
    protected $guarded = ['id'];

    protected $lobby_fields = [
      'id',
      'winner_id',
      'loser_id',
      'player_id'

    ];

    public function lobby() {
      return $this->belongsTo('App\Lobby', 'player_id')->select($lobby_fields);
    }
}
