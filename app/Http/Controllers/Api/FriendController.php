<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Friend;
use App\Player;

class FriendController extends Controller
{

    public function index(Request $request) {
      $friends = Friend::all();
      return ['status'=>TRUE, 'friends'=>$friends];
    }


    public function show($id) {
      $friend = Friend::find($id);
      return ['status'=>TRUE, 'friend'=>$friend];
    }

    // public function index(Request $request) {
    //   if ($request->isAuthed) {
    //     $friends = Friend::with('friend')->where('player_id', $request->player->id)->get();
    //     return ['status'=>TRUE, 'friends'=>$friends];
    //   } else {
    //     return ['status'=>FALSE, 'message'=>'Unathorized Access'];
    //   }
    // }

    public function getPending(Request $request) {
      if ($request->isAuthed) {
        $friends = Friend::with('friend')->where('friend_id', $request->player->id)->where('is_accepted', 0)->get();
        return ['status'=>TRUE, 'pendings'=>$friends];
      } else {
        return ['status'=>FALSE, 'message'=>'Unathorized Access'];
      }
    }

    public function getLeaderboard(Request $request) {
      if ($request->isAuthed) {


        return ['status'=>TRUE];
      } else {
        return ['status'=>FALSE, 'message'=>'Unathorized Access'];
      }
    }

    public function invite(Request $request, $playerId) {
      if ($request->isAuthed) {

        $player = Player::find($playerId);
        if ($player) {

          $invitation = Friend::firstOrCreate([
            'player_id'=>$request->player->id,
            'friend_id'=>$player->id
          ]);

          return ['status'=>TRUE, 'Invitation Sent'];

        } else {
          return ['status'=>FALSE, 'message'=>'Player Not Found'];
        }

      } else {
        return ['status'=>FALSE, 'message'=>'Unathorized Access'];
      }
    }

    public function accept(Request $request, $invitationId) {
      if ($request->isAuthed) {
        $invitation = Friend::find($invitationId);
        if ($invitation) {
          if ($invitation->player_id == $request->player->id || $invitation->friend_id == $request->player->id) {

            if ($invitation->friend_id == $request->player->id) {

              $invitation->is_accepted = TRUE;
              $invitation->save();

              Friend::firstOrCreate([
                'player_id'=>$invitation->friend_id,
                'friend_id'=>$invitation->player_id,
                'is_accepted'=>TRUE
              ]);

              //notify the inviter that invitation has been accepted

            }

            return ['status'=>TRUE];

          } else {
            return ['status'=>FALSE, 'message'=>'Incorrect Invitation'];
          }
        } else {
          return ['status'=>FALSE, 'message'=>'Invitation Not Found'];
        }
      } else {
        return ['status'=>FALSE, 'message'=>'Unathorized Access'];
      }
    }

    public function destroy(Request $request, $invitationId) {
      if ($request->isAuthed) {
        Friend::destroy($invitationId);
        return ['status'=>TRUE];
      } else {
        return ['status'=>FALSE, 'message'=>'Unathorized Access'];
      }
    }

}
