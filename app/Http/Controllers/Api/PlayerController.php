<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Player;
use App\PlayFab;
use App\Activity;

class PlayerController extends Controller {

  private $playfab = '';

  public function __construct(Request $request) {
    $this->playfab = new \App\PlayFab();
    if ($request->isAuthed) {
      $this->playfab->setSession($request->player->session_ticket);
    }
  }




  public function index() {
    $players = Player::all();
    return ['status'=>TRUE, 'players'=>$players];
  }


  public function show(Request $request, $playerId) {
    $player = Player::find($playerId);
    return ['status'=>TRUE, 'player'=>$player];
  }

  public function auth(Request $request, $type='email') {

    switch($type) {
      case 'facebook':
        // client/LoginWithEmailAddress
      break;
      case 'gamecenter':
        // client/LoginWithGameCenter
      break;
      case 'google':
        //  client/GameWithGoogle
      break;
      default:
      $res = $this->playfab->client('LoginWithEmailAddress', [
        'TitleId'=>$this->playfab->getId(),
        'Email'=>$request->input('email'),
        'Password'=>trim($request->input('password'))
        ]);
    }


    if ($res->code == 200) {

      $player = Player::where('playfab_id', $res->data->PlayFabId)->first();
      if ($player) {

        $player->session_ticket = $res->data->SessionTicket;
        $player->api_key = sha1($res->data->SessionTicket);
        $player->vc = $request->input('vc', $player->vc);
        $player->last_login = time();
        $player->save();

      } else {

        $player = Player::firstOrCreate([
          // 'username'=>$res->data->Username,
          'playfab_id'=>$res->data->PlayFabId,
          'game_id'=>$this->playfab->getId(),
          'email'=>$request->input('email'),
          ]);

        $player->username = $res->data->PlayFabId;
        $player->firstname = $request->input('firstname', '');
        $player->lastname = $request->input('lastname', '');
        $player->password = sha1($request->input('password', '$password123!'));
        $player->vc = $request->input('vc', 'chip');
        $player->session_ticket = $res->data->SessionTicket;
        $player->api_key = sha1($res->data->SessionTicket);
        $player->last_login = time();
        $player->save();

      }
      return ['status'=>TRUE, 'player'=>$player, 'token'=>$player->api_key, 'type'=>$type];

    } else {

      return ['status'=>FALSE, 'message'=>$res->errorMessage];

    }

  }


  public function register(Request $request) {


    $res = $this->playfab->client('RegisterPlayFabUser', [
      'TitleId' => $this->playfab->getId(),
      'Username' => $request->input('username'),
      'Email' => $request->input('email'),
      'Password' => $request->input('password')
      ]);

    if ($res->code == 200) {

      $player = Player::firstOrCreate([
        'username'=>$res->data->Username,
        'playfab_id'=>$res->data->PlayFabId,
        'game_id'=>$this->playfab->getId(),
        'email'=>$request->input('email')
        ]);


      $player->firstname = $request->input('firstname', 'New');
      $player->lastname = $request->input('lastname', 'User');
      $player->password = sha1($request->input('password', '$password123!'));
      $player->vc = $request->input('vc', 'chip');
      $player->session_ticket = $res->data->SessionTicket;
      $player->api_key = sha1($res->data->SessionTicket);
      $player->last_login = time();
      $player->save();

      return ['status'=>TRUE, 'player'=>$player, 'token'=>$player->api_key];

    } else {

      return ['status'=>FALSE, 'message'=>$res->errorMessage];

    }

  }


  public function registerWithFbOrGuest(Request $request) {

    $username = $request->input('username');
    $session_ticket = $request->input('session_ticket');
    $playfab_id = $request->input('playfab_id');
    $fb_id = $request->input('fb_id');
    $game_id = $this->playfab->getId();

    if( $username === NULL || $session_ticket === NULL || $playfab_id === NULL){
      return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
    }



    $player = Player::firstOrCreate([
      'playfab_id'=>$playfab_id,
      'fb_id'=>$fb_id
      ]);

    if(!$player->wasRecentlyCreated){
        return ['status'=>TRUE, 'player'=>$player, 'token'=>$player->api_key];
    }else{
        $player->username = $username;
        $player->game_id = $game_id;
        $player->email = $username.'@dummy.com';
        $player->firstname = $request->input('firstname', 'New');
        $player->lastname = $request->input('lastname', 'User');
        $player->password = sha1($request->input('password', '$password123!'));
        $player->vc = $request->input('vc', 'chip');
        $player->session_ticket = $session_ticket;
        $player->api_key = sha1($session_ticket);
        $player->last_login = time();
        $player->virtual_chips_total = 0;
        $player->real_cash_total = 0;
        $player->total_won = 0;
        $player->total_lose = 0;
        $player->vc = 'chip';
        $player->avatar = NULL;
        $player->eula_accepted = 0;
        $player->eula_id = NULL;
        $player->remember_token = NULL;
        $player->fb_id = $fb_id;
        $player->async_real_win = 0;
        $player->async_real_loss = 0;
        $player->async_real_percentage = 0;
        $player->async_virtual_win = 0;
        $player->async_virtual_loss = 0;
        $player->async_virtual_percentage = 0;
        $player->save();

        return ['status'=>TRUE, 'player'=>$player, 'token'=>$player->api_key];

    }

  }

  public function authGuest(Request $request) {

    $username = $request->input('username');
    $session_ticket = $request->input('session_ticket');
    $playfab_id = $request->input('playfab_id');
    $game_id = $this->playfab->getId();

    if( $username === NULL || $session_ticket === NULL || $playfab_id === NULL){
      return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
    }



    $player = Player::firstOrCreate([
      'playfab_id'=>$playfab_id
      ]);

    if(!$player->wasRecentlyCreated){
        return ['status'=>TRUE, 'player'=>$player, 'token'=>$player->api_key];
    }else{
        $player->username = $username;
        $player->game_id = $game_id;
        $player->email = $username.rand(1,10000).'@dummy.com';
        $player->firstname = $request->input('firstname', 'New');
        $player->lastname = $request->input('lastname', 'User');
        $player->password = sha1($request->input('password', '$password123!'));
        $player->vc = $request->input('vc', 'chip');
        $player->session_ticket = $session_ticket;
        $player->api_key = sha1($session_ticket);
        $player->last_login = time();
        $player->virtual_chips_total = 0;
        $player->real_cash_total = 0;
        $player->total_won = 0;
        $player->total_lose = 0;
        $player->vc = 'chip';
        $player->avatar = NULL;
        $player->eula_accepted = 0;
        $player->eula_id = NULL;
        $player->fb_id = "";
        $player->remember_token = NULL;
         $player->async_real_win = 0;
        $player->async_real_loss = 0;
        $player->async_real_percentage = 0;
        $player->async_virtual_win = 0;
        $player->async_virtual_loss = 0;
        $player->async_virtual_percentage = 0;
        $player->save();

        return ['status'=>TRUE, 'player'=>$player, 'token'=>$player->api_key];
    }



  }

  public function authEmail(Request $request) {

    $username = $request->input('username');
    $session_ticket = $request->input('session_ticket');
    $playfab_id = $request->input('playfab_id');
    $email = $request->input('email');
    $password = $request->input('password');
    $game_id = $this->playfab->getId();

    if( $username === NULL || $session_ticket === NULL || $playfab_id === NULL || $email === NULL || $password ===NULL){
      return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
    }



    $player = Player::firstOrCreate([
      'playfab_id'=>$playfab_id,
      'email'=>$email
      ]);

    if(!$player->wasRecentlyCreated){
        return ['status'=>TRUE, 'player'=>$player, 'token'=>$player->api_key];
    }else{
        $player->username = $username;
        $player->game_id = $game_id;
        $player->email = $email;
        $player->firstname = $request->input('firstname', 'New');
        $player->lastname = $request->input('lastname', 'User');
        $player->password = sha1($request->input('password', '$password123!'));
        $player->session_ticket = $session_ticket;
        $player->api_key = sha1($session_ticket);
        $player->last_login = time();
        $player->virtual_chips_total = 0;
        $player->real_cash_total = 0;
        $player->total_won = 0;
        $player->total_lose = 0;
        $player->vc = 'chip';
        $player->avatar = NULL;
        $player->eula_accepted = 0;
        $player->eula_id = NULL;
        $player->fb_id = "";
        $player->remember_token = NULL;
        $player->async_real_win = 0;
        $player->async_real_loss = 0;
        $player->async_real_percentage = 0;
        $player->async_virtual_win = 0;
        $player->async_virtual_loss = 0;
        $player->async_virtual_percentage = 0;
        $player->save();

        return ['status'=>TRUE, 'player'=>$player, 'token'=>$player->api_key];
    }   





  }


  public function getPhoton(Request $request) {
    // client/GetPhotonAuthenticationToken
    if ($request->isAuthed) {
      $res = $this->playfab->client('GetPhotonAuthenticationToken', [
        'PhotonApplicationId'=>$this->playfab->photon_id
        ]);

      if ($res->code == 200) {
        return ['status'=>TRUE, 'photon'=>$res->data->PhotonCustomAuthenticationToken];
      } else {
        return ['status'=>FALSE, 'message'=>$res->errorMessage];
      }
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  }


  public function getLobbies(Request $request) {

    if ($request->isAuthed) {
      $lobbyPlayers = \App\LobbyPlayer::with('Lobby')->where('player_id', $request->player->id)->get();
      return ['status'=>TRUE, 'lobbies'=>$lobbyPlayers];
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  }


  public function getStatus(Request $request) {

    return ['status'=>TRUE];
  }


  public function getInfo(Request $request) {

    // client/GetAccountInfo
    // client/GetPlayerCombinedInfo
    // client/GetUserData
    // client/GetUserPublisherData
    if ($request->isAuthed) {
      // $accountInfo = $this->playfab->client('GetAccountInfo');
      // $playerInfo = $this->playfab->client('GetPlayerCombinedInfo', $this->playfab->player_combined_info);
      // $userInfo = $this->playfab->client('GetUserData');
      // $publisherInfo = $this->playfab->client('GetUserPublisherData');

      return ['status'=>TRUE,'player'=>$request->player];
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  }


  public function postInfo(Request $request, $type='data') {
    if ($request->isAuthed) {

      $fields = $request->all();

      switch($type) {
        default:
         // client/UpdateUserData
        $res = $this->playfab->client('UpdateUserData', [
         'Data'=>$fields,
         'KeysToRemove'=>[],
         'Permission'=>'Public'
         ]);
      }
      return ['status'=>TRUE, 'res'=>$res];

    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }
    return ['status'=>TRUE];
  }


  public function postAvatar(Request $request) {
    //
    if ($request->isAuthed) {
      $request->player->avatar = $request->input('avatar', $request->player->avatar);
      $request->player->save();

      $res = $this->playfab->client('UpdateAvatarUrl', [
        'ImageUrl' => $request->player->avatar,
        ]);

      return ['status'=>TRUE, 'player'=>$request->player];
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }
  }

  public function postReport(Request $request, $playerId) {
    // client/ReportPlayer
    if ($request->isAuthed) {
      $player = Player::find($playerId);
      $res = $this->playfab->client('ReportPlayer', [
        'ReporteeId'=>$request->player->playfab_id,
        'Comment'=>$request->input('comment', 'Reported')
        ]);
      return ['status'=>TRUE];
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  }


  public function postRecover(Request $request) {
    // client/SendAccountRecoveryEmail
    if ($request->isAuthed) {
      $res = $this->playfab->client('SendAccountRecoveryEmail', [
        'Email'=>$request->player->email,
        'TitleId'=>$request->player->game_id
        ]);
      return ['status'=>TRUE];
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }
  }


  public function sendNotification(Request $request, $playerId='', $type='welcome') {
    // server/SendPushNotification
    if ($request->isAuthed) {

      $playerId = $request->player->id;
      $player = Player::find($playerId);

      switch($type) {
        case 'welcome':
        $message = 'Thank you for joining Pool 4 Cash game!';
        break;
      }

      if ($player) {

        $this->playfab->server('SendPushNotification', [
          'Recipient'=>$player->playfab_id,
          'Message'=>$request->input('message', $message)
          ]);
        return ['status'=>TRUE];

      } else {
        return ['status'=>FALSE, 'message'=>'Player Not Found'];
      }
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  }


  public function setNotification(Request $request, $type = '') {
    // client/RegisterForIOSPushNotification
    // client/AndroidDevicePushNotificationRegistration
    $type = $request->input('type');
    if ($request->isAuthed) {
      switch($type) {
        case 'ios':
        $this->playfab->client('RegisterForIOSPushNotification', [
          'DeviceToken'=>$request->input('device_token'),
          'SendPushNotificationConfirmation'=>TRUE,
          'ConfirmationMessage'=>'Welcome to Pool 4 Cash'
          ]);
        break;
        case 'android':
        $this->playfab->client('AndroidDevicePushNotificationRegistration', [
          'DeviceToken'=>$request->input('device_token'),
          'SendPushNotificationConfirmation'=>TRUE,
          'ConfirmationMessage'=>'Welcome to Pool 4 Cash'
          ]);
        break;
      }
      return ['status'=>TRUE];

    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  }

  public function activity(Request $request) {
    if ($request->isAuthed) {

      $activity = Activity::create([
        'player_id'=>$request->player->id,
        'lobby_id'=>$request->input('lobby'),
        ]);

      $activity->pocketed_balls = $request->input('pocketed_balls');
      $activity->duration = $request->input('duration');
      $activity->missed_balls = $request->input('missed_balls');
      $activity->wrong_balls = $request->input('wrong_balls');
      $activity->score = (1000/$request->input('duration'))*( 100 + ($request->input('pocketed_balls')*100) - ($request->input('missed_balls')*5) - ($request->input('wrong_balls')*10));
      if(  $activity->score < 0){
        $activity->score = 5;
      }    
      
      $activity->save();

      return ['status'=>TRUE];

    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }
  }

  public function linkGuestToEmail(Request $request) {
    if ($request->isAuthed) {
      $username = $request->input('username');
      $playfab_id = $request->input('playfab_id');
      $email = $request->input('email');
      $password = $request->input('password');
      $game_id = $this->playfab->getId();

      if( $username === NULL || $playfab_id === NULL || $email === NULL || $password ===NULL){
        return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
      }

      $player= Player::where([['playfab_id', $playfab_id]])->first();



      $player->username = $username;
      $player->email = $email;
      $player->password = sha1($request->input('password', '$password123!'));
      $player->last_login = time();
      $player->save();


      return ['status'=>TRUE, 'player'=>$player];
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  }

  public function linkGuestToFb(Request $request) {
    if ($request->isAuthed) {
      $fb_id = $request->input('facebook_id');
      $playfab_id = $request->input('playfab_id');
      $game_id = $this->playfab->getId();

      if(  $playfab_id === NULL || $fb_id === NULL ){
        return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
      }

      $player= Player::where([['playfab_id', $playfab_id]])->first();



      $player->fb_id = $fb_id;
      $player->last_login = time();
      $player->save();


      return ['status'=>TRUE, 'player'=>$player];
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  }

  public function linkEmailToFb(Request $request) {
    if ($request->isAuthed) {
      $fb_id = $request->input('facebook_id');
      $playfab_id = $request->input('playfab_id');
      $game_id = $this->playfab->getId();

      if(  $playfab_id === NULL || $fb_id === NULL ){
        return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
      }

      $player= Player::where([['playfab_id', $playfab_id]])->first();



      $player->fb_id = $fb_id;
      $player->last_login = time();
      $player->save();


      return ['status'=>TRUE, 'player'=>$player];
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }

  } 

    public function deletePlayer($id){
        $Player  = Player::find($id);
        $Player->delete();
 
       return ['status'=>TRUE, 'message'=>'Deleted'];
    }
  
    public function updatePlayer(Request $request,$id){
        $Player  = Player::find($id);
        $Player->real_cash_total = $request->input('real_cash_total');
        $Player->virtual_chips_total = $request->input('virtual_chips_total');
        $Player->save();
  
        return ['status'=>TRUE, 'Player'=>$Player];
    } 





}
