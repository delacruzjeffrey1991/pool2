<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vc;
use App\Player;
use App\Room;

class VirtualCurrencyController extends Controller
{

  private $playfab = '';

  public function __construct(Request $request) {
    $this->playfab = new \App\PlayFab();
    if ($request->isAuthed) {
      $this->playfab->setSession($request->player->session_ticket);
      // $this->addCurrencyType($request);
    }
  }

  public function index() {
    $virtualCurrencies = Vc::all();
    return ['status'=>TRUE, 'virtualCurrencies'=>$virtualCurrencies];
  }


  public function show($vcsId) {
    $virtualCurrency = Vc::find($vcsId);
    return ['status'=>TRUE, 'virtualCurrency'=>$virtualCurrency];
  }


  private function addCurrencyType(Request $request) {
    return $this->playfab->admin('AddVirtualCurrencyTypes', [
      'VirtualCurrencies'=>[
        [
          'CurrencyCode'=>'RC',
          'DisplayName'=>'Real Cash',
          'InitialDeposit'=>0,
          'RechargeRate'=>1000,
          'RechargeMax'=>100000
        ],
        [
          'CurrencyCode'=>'VC',
          'DisplayName'=>'Virtual Chips',
          'InitialDeposit'=>0,
          'RechargeRate'=>1000,
          'RechargeMax'=>100000
        ]
      ],
    ]);
  }

  public function addCash(Request $request) {

    if ($request->isAuthed) {
      $res = $this->playfab->client('AddUserVirtualCurrency', [
        'VirtualCurrency'=>'RC',
        'Amount'=>$request->input('amount', 0)
      ]);

      if ($res->code == 200) {

        //player saving real cash total
        $request->player->real_cash_total = $res->data->Balance;
        $request->player->save();

        //inserts to virtual currency
        $vcs = Vc::insert([
          'player_id'=>$request->player->id,
          'amount'=>$request->input('amount'),
        ]);

        return ['status'=>TRUE, 'data'=>$res->data];
      } else {
        return ['status'=>FALSE, 'data'=>$res];
      }
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Access'];
    }

  }


  public function subtractCash(Request $request) {

    if ($request->isAuthed) {
      $res = $this->playfab->client('SubtractUserVirtualCurrency', [
        'VirtualCurrency'=>'RC',
        'Amount'=>$request->input('amount', 0)
      ]);

      if ($res->code == 200) {
        $request->player->real_cash_total = $res->data->Balance;
        $request->player->save();
        // $vcs = Vc::firstOrCreate([
        //         'player_id'=>$request->player->id,
        //         'amount'=>$request->input('amount')
        //       ]);
        //
        // $vcs->save();

        return ['status'=>TRUE, 'data'=>$res->data];
      } else {
        return ['status'=>FALSE, 'error'=>$res];
      }
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Access'];
    }

  }


  public function resetCash(Request $request) {

    if ($request->isAuthed) {

      $res = $this->playfab->client('SubtractUserVirtualCurrency', [
        'VirtualCurrency'=>'RC',
        'Amount'=>abs($request->player->real_cash_total)
      ]);

      if ($res->code == 200) {
        $request->player->real_cash_total = $res->data->Balance;
        $request->player->save();
        return ['status'=>TRUE, 'data'=>$res->data];
      } else {
        return ['status'=>TRUE, 'message'=>'Your cash has been reset', 'error'=>$res];
      }
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Access'];
    }

  }


  public function addChips(Request $request) {

    if ($request->isAuthed) {
      $res = $this->playfab->client('AddUserVirtualCurrency', [
        'VirtualCurrency'=>'VC',
        'Amount'=>$request->input('amount', 0)
      ]);

      if ($res->code == 200) {
        $request->player->virtual_chips_total = $res->data->Balance;
        $request->player->save();

        $vcs = Vc::insert([
          'player_id'=>$request->player->id,
          'amount'=>$request->input('amount'),
        ]);

        return ['status'=>TRUE, 'data'=>$res->data];
      } else {
        return ['status'=>FALSE, 'error'=>$res];
      }
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Access'];
    }

  }


  public function subtractChips(Request $request) {

    if ($request->isAuthed) {
      $res = $this->playfab->client('SubtractUserVirtualCurrency', [
        'VirtualCurrency'=>'VC',
        'Amount'=>$request->input('amount', 0)
      ]);

      if ($res->code == 200) {
        $request->player->virtual_chips_total = $res->data->Balance;
        $request->player->save();
        return ['status'=>TRUE, 'data'=>$res->data];
      } else {
        return ['status'=>FALSE, 'error'=>$res];
      }
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Access'];
    }

  }


  public function resetChips(Request $request) {

    if ($request->isAuthed) {

      $res = $this->playfab->client('SubtractUserVirtualCurrency', [
        'VirtualCurrency'=>'VC',
        'Amount'=>abs($request->player->virtual_chips_total)
      ]);

      if ($res->code == 200) {
        $request->player->virtual_chips_total = $res->data->Balance;
        $request->player->save();
        return ['status'=>TRUE, 'data'=>$res->data];
      } else {
        return ['status'=>TRUE, 'message'=>'Your chips has been reset'];
      }
    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Access'];
    }

  }

public function enterRoom(Request $request, $roomName) {
    if ($request->isAuthed) {
      $player = $request->player;
      $originalRealCashTotal = $player->virtual_chips_total;

      if($roomName === 'london'){
        if($originalRealCashTotal >= 50){
          $player->update(['virtual_chips_total' => $originalRealCashTotal - 50]);
          $room = Room::firstOrCreate([
                'player_id'=>$player->id
          ]);
          $room->room_name = 'london';
          $room->cash_type = 'virtual';
          $room->save();
          return ['status'=>TRUE, 'player'=>$request->player];
        }else{
          return ['status'=>FALSE, 'message'=>'Insufficient balance , balance is :'.$originalRealCashTotal];
        } 
      }else if($roomName === 'sydney'){
        if($originalRealCashTotal >= 100){
          $player->update(['virtual_chips_total' => $originalRealCashTotal - 100]);
          $room = Room::firstOrCreate([
                'player_id'=>$player->id
          ]);
          $room->room_name = 'sydney';
          $room->cash_type = 'virtual';
          $room->save();
          return ['status'=>TRUE, 'player'=>$request->player];
        }else{
          return ['status'=>FALSE, 'message'=>'Insufficient balance , balance is :'.$originalRealCashTotal];
        } 

      }else if($roomName === 'moscow'){
        if($originalRealCashTotal >= 250){
          $player->update(['virtual_chips_total' => $originalRealCashTotal - 250]);
          $room = Room::firstOrCreate([
                'player_id'=>$player->id
          ]);
          $room->room_name = 'moscow';
          $room->cash_type = 'virtual';
          $room->save();
          return ['status'=>TRUE, 'player'=>$request->player];
        }else{
          return ['status'=>FALSE, 'message'=>'Insufficient balance , balance is :'.$originalRealCashTotal];
        } 
      }else{
        return ['status'=>FALSE, 'message'=>'invalid room name'];
      }

    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }
  }

    public function add(Request $request,$amount) {
    if ($request->isAuthed) {
      
      if(is_numeric($amount)){
        $player = $request->player;
        $originalVirtualChipsTotal = $player->virtual_chips_total;
        $player->update(['virtual_chips_total' => $originalVirtualChipsTotal + $amount]);
        return ['status'=>TRUE, 'player'=>$request->player];

      }else{
        
        return ['status'=>FALSE, 'message'=>'Parameter amount is not a number or missing'];
      }

    } else {
      return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
    }
  }



}
