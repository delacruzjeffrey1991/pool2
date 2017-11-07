<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lobby;
use App\LobbyPlayer;
use App\Helper;
use DB;

class LobbyController extends Controller {


    public function __construct(Request $request) {
      $this->playfab = new \App\PlayFab();
      if ($request->isAuthed) {
        $this->playfab->setSession($request->player->session_ticket);
      }
    }

    public function index() {
      $lobbies = Lobby::with(['creator', 'players'])->get();
      return ['status'=>TRUE, 'lobbies'=>$lobbies];
    }


    public function show(Request $request, $lobbyId) {
      $lobby = Lobby::find($lobbyId);
      return ['status'=>TRUE, 'lobby'=>$lobby];
    }


    public function match(Request $request) {
      if ($request->isAuthed) {
        $match = Lobby::where('created_by', '<>', $request->player->id)->inRandomOrder()->first();
        return ['status'=>TRUE, 'match'=>$match];
      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
      }
    }

    //player joins the lobby
    public function join(Request $request) {

      if ($request->isAuthed) {
        $amount = Helper::getSetting('game.amount', 10);
        $region = Helper::getSetting('game.region', 'GB');

        $lobby = Lobby::where(['created_by'=>$request->player->id])->orderBy('created_at', 'DESC')->first();
        $createLobby = FALSE;

        if ($lobby) {
          switch($lobby->status) {
            case 'created':
              return ['status'=>FALSE, 'message'=>'Waiting for an opponent', 'lobby'=>$lobby];
            case 'started':
              return ['status'=>FALSE, 'message'=>'You are in a game'];
              break;
            case 'completed':
            case 'archived':
              $createLobby = TRUE;
              break;
          }
        } else {
          $createLobby = TRUE;
        }

        if ($createLobby) {

          //get a random match on join
          $match = Lobby::inRandomOrder()->first();

          $lobby = Lobby::firstOrCreate([
            'name'=>$request->input('name', 'New Game'),
            'region'=>$request->input('region', $region),
            'amount'=>$request->input('amount', $amount),
            'vc'=>$request->input('vc', 'chip'),
            'currency'=>$request->input('currency', 'USD'),
            'status'=>'created',
            'created_by'=>$request->player->id,
          ]);

          $lobbyPlayer = LobbyPlayer::firstOrCreate([
            'lobby_id'=>$lobby->id,
            'player_id'=>$request->player->id,
          ]);

        }

        return ['status'=>TRUE, 'lobby'=>$lobby, 'player'=>$lobbyPlayer, 'newly_created'=>$createLobby, 'match'=>$match];
      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
      }

    }


    public function invite(Request $request, $lobbyId, $playerId) {

      if ($request->isAuthed) {
        $lobby = Lobby::find($lobbyId);

        if ($lobby) {

          if ($lobby->created_by == $request->player->id) {
            $lobbyPlayer = LobbyPlayer::firstOrCreate(['lobby_id'=>$lobby->id, 'player_id'=>$playerId]);
            return [
              'status'=>TRUE,
              'lobby'=>$lobby,
              'players'=>LobbyPlayer::where('lobby_id', $lobby->id)->get()
            ];

          } else {
            return ['status'=>FALSE, 'message'=>'Lobby Is Created By Other'];
          }

        } else {
          return ['status'=>FALSE, 'message'=>'Lobby Not Found'];
        }

      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
      }

    }


    public function invitations(Request $request) {

      if ($request->isAuthed) {
        $lobbies = DB::select('SELECT lobbies.*, lobby_players.player_id FROM lobby_players
          LEFT JOIN lobbies ON lobbies.id = lobby_players.lobby_id
          WHERE lobby_players.player_id = ?
          AND lobbies.created_by <> ?',
          [$request->player->id, $request->player->id]
        );
        return ['status'=>TRUE, 'lobbies'=>$lobbies];
      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
      }

    }


    public function accept(Request $request, $lobbyId) {

      if ($request->isAuthed) {

        //check if lobby exists
        $lobby = $lobby = Lobby::find($lobbyId);
        if ($lobby) {

          //check if lobby player joined
          $lobbyPlayer = LobbyPlayer::where(['lobby_id'=>$lobbyId, 'player_id'=>$request->player->id])->first();
          if ($lobbyPlayer) {

            if ($lobbyPlayer->player_id == $lobby->created_by) {
              //accept if player is lobby owner
              $lobby->has_player_accept = 1;
              $lobby->player_accepted_at = time();
            } else {
              //accept by opponent
              $lobby->has_opponent_accept = 1;
              $lobby->opponent_accepted_at = time();
            }

            if ($lobby->has_player_accept == 1 && $lobby->has_opponent_accept == 1) {
              //send notification to start
              $lobby->status = 'started';
              $lobby->started_at = time();
            }

            $lobby->save();

            return ['status'=>TRUE, 'lobby'=>$lobby];

          } else {
            return ['status'=>FALSE, 'message'=>'Lobby Player Not Found'];
          }

        } else {
          return ['status'=>FALSE, 'message'=>'Lobby Not Found'];
        }

      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
      }

    }


    public function start(Request $request, $lobbyId) {

      if ($request->isAuthed) {

        $lobby = $lobby = Lobby::find($lobbyId);
        if ($lobby) {

          if ($lobby->has_player_accept == 1 && $lobby->has_opponent_accept == 1) {
            //updated status to started if on created mode
            if ($lobby->status == 'created') {
              $lobby->status = 'started';
              $lobby->started_at = time();
            }
            return ['status'=>TRUE, 'lobby'=>$lobby];
          } else {
            return ['status'=>FALSE, 'message'=>'Confirmation Required'];
          }

        } else {
          return ['status'=>FALSE, 'message'=>'Lobby Not Found'];
        }

      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
      }

    }


    public function complete(Request $request, $lobbyId) {

      if ($request->isAuthed) {

        $lobby = $lobby = Lobby::find($lobbyId);
        if ($lobby) {

          $lobbyPlayer = LobbyPlayer::where(['lobby_id'=>$lobbyId, 'player_id'=>$request->player->id])->first();
          if ($lobbyPlayer) {

            //set first as the winner
            if (empty($lobby->winner_id)) {
              $lobbyPlayer->is_winner = TRUE;
              $lobbyPlayer->save();

              //send winner notification
              $lobby->winner_id = $lobbyPlayer->player_id;
              $lobby->status = 'completed';
              $lobby->completed_at = time();
              $lobby->save();
            }

            if ($lobby->winner_id == $request->player->id) {
              return ['status'=>TRUE, 'winner'=>TRUE, 'message'=>'You won this game', 'lobby'=>$lobby];
            } else {
              $lobby->loser_id = $lobbyPlayer->player_id;
              $lobby->save();
              return ['status'=>TRUE, 'winner'=>FALSE, 'message'=>'You lose this game', 'lobby'=>$lobby];
            }

          } else {
            return ['status'=>FALSE, 'message'=>'Lobby Player Not Found'];
          }

        } else {
          return ['status'=>FALSE, 'message'=>'Lobby Not Found'];
        }

      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
      }

    }


    public function leave(Request $request, $lobbyId) {

      if ($request->isAuthed) {
        // $lobbyPlayers = LobbyPlayer::with('lobby')->where_in('lobbies.status', ['created', 'started'])->where('player_id', $request->player->id)->get();
        $lobbies =  DB::select("SELECT l.*, lb.id AS lobbyplayer_id FROM lobbies l LEFT JOIN lobby_players lb ON l.id = lb.lobby_id WHERE lb.player_id = ? AND l.status IN ('created', 'started')", [$request->player->id]);

        foreach($lobbies AS $lobby) {
          switch($lobby->status) {
            case 'created':
              //if creator is leave
              if ($lobby->created_by == $request->player->id) {
                Lobby::destroy($lobby->id);
              }
              //fire notification that he has leaves the lobby
              LobbyPlayer::destroy($lobby->lobbyplayer_id);
              break;

            case 'started':
              //fire notification to tell other player that game is completed
              $winner = LobbyPlayers::where('id', $lobby->lobbyplayer_id)->where('player_id', '<>', $request->player->id)->update([
                'is_winner'=>TRUE
              ]);

              //fire notification to player that he was defeated
              Lobby::where('id', $lobby->id)->update([
                'winner_id'=>$winner->player_id,
                'loser_id'=>$request->player->id,
                'status'=>'completed',
                'completed_at'=>time()
              ]);
              break;

          }
        }
        return ['status'=>TRUE];
      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
      }

    }

}
