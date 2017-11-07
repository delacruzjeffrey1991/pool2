<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Eula;
use App\Player;

class EulaController extends Controller
{

    public function __construct(Request $request) {
      $this->playfab = new \App\PlayFab();
      if ($request->isAuthed) {
        $this->playfab->setSession($request->player->session_ticket);
      }
    }


    public function index() {
      $eulas = Eula::all();
      return ['status'=>TRUE, 'eulas'=>$eulas];
    }


    public function show($eulaId) {
      $eula = Eula::find($eulaId);
      return ['status'=>TRUE, 'eula'=>$eula];
    }


    public function agree(Request $request, $eulaId) {
      if ($request->isAuthed) {

          if ($request->input('accepted', 0) == 1) {
            $request->player->eula_accepted = 1;
            $request->player->eula_id = $request->input('eula');
            $request->player->save();

          }

        return ['status'=>TRUE];
      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorize Access'];
      }
    }


}
