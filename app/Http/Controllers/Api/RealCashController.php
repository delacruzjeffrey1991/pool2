<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Player;
use App\Room;
use App\Helper;
use App\PlayFab;
use App\LiveMatch;
use DB;

class RealCashController extends Controller
{
	private $playfab;
	public function __construct(Request $request) {
		$this->playfab = new \App\PlayFab();
		if ($request->isAuthed) {
			$this->playfab->setSession($request->player->session_ticket);
		}
	}

	public function deposit(Request $request,$amount) {
		if ($request->isAuthed) {
			
			if(is_numeric($amount)){
				$player = $request->player;
				$originalRealCashTotal = $player->real_cash_total;
				$player->update(['real_cash_total' => $originalRealCashTotal + $amount]);
				return ['status'=>TRUE, 'player'=>$request->player];

			}else{
				
				return ['status'=>FALSE, 'message'=>'Parameter amount is not a number or missing'];
			}

		} else {
			return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
		}
	}

	public function withraw(Request $request,$amount) {
		if ($request->isAuthed) {
			
			if(is_numeric($amount)){
				$player = $request->player;
				$originalRealCashTotal = $player->real_cash_total;
				if($amount <= $originalRealCashTotal){
					$player->update(['real_cash_total' => $originalRealCashTotal - $amount]);
					return ['status'=>TRUE, 'player'=>$request->player];
				}else{
					return ['status'=>TRUE, 'message'=>'Insufficient funds!'];
				}	

			}else{
				
				return ['status'=>FALSE, 'message'=>'Parameter amount is not a number or missing'];
			}

		} else {
			return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
		}
	}

	public function enterRoom(Request $request, $roomName) {
		if ($request->isAuthed) {
			$player = $request->player;
			$originalRealCashTotal = $player->real_cash_total;

			if($roomName === 'london'){
				if($originalRealCashTotal >= 1){
					$player->update(['real_cash_total' => $originalRealCashTotal - 1]);
					$room = Room::firstOrCreate([
			          'player_id'=>$player->id
			          ]);
					$room->room_name = 'london';
					$room->cash_type = 'real';
					$room->save();

					return ['status'=>TRUE, 'player'=>$request->player];
				}else{
					return ['status'=>FALSE, 'message'=>'Insufficient balance , balance is :'.$originalRealCashTotal];
				}	
			}else if($roomName === 'sydney'){
				if($originalRealCashTotal >= 3){
					$player->update(['real_cash_total' => $originalRealCashTotal - 3]);

					$room = Room::firstOrCreate([
			          'player_id'=>$player->id
			         ]);
					$room->room_name = 'sydney';
					$room->cash_type = 'real';
					$room->save();


					return ['status'=>TRUE, 'player'=>$request->player];
				}else{
					return ['status'=>FALSE, 'message'=>'Insufficient balance , balance is :'.$originalRealCashTotal];
				}	

			}else if($roomName === 'moscow'){
				if($originalRealCashTotal >= 5){
					$player->update(['real_cash_total' => $originalRealCashTotal - 5]);
					$room = Room::firstOrCreate([
			          'player_id'=>$player->id
			         ]);
					$room->room_name = 'moscow';
					$room->cash_type = 'real';
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

	public function leaveRoom(Request $request, $roomName) {
		if ($request->isAuthed) {
			$player = $request->player;
			$originalRealCashTotal = $player->real_cash_total;

			if($roomName === 'london'){
				$player->update(['real_cash_total' => $originalRealCashTotal + 1]);
				return ['status'=>TRUE, 'player'=>$request->player];

			}else if($roomName === 'sydney'){

				$player->update(['real_cash_total' => $originalRealCashTotal + 3]);
				return ['status'=>TRUE, 'player'=>$request->player];

			}else if($roomName === 'moscow'){

				$player->update(['real_cash_total' => $originalRealCashTotal + 5]);
				return ['status'=>TRUE, 'player'=>$request->player];	
			}else{
				return ['status'=>FALSE, 'message'=>'invalid room name'];
			}

		} else {
			return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
		}
	}

	// public function matchResult(Request $request) {
	// 	if ($request->isAuthed) {
	// 		$roomName = $request->input('room_name');
	// 		$player1Id = $request->input('player1_id');
	// 		$player2Id  = $request->input('player2_id');
	// 		$winnerId  = $request->input('winner_id');

	// 		if($roomName === NULL || $player1Id === NULL || $player2Id === NULL || $winnerId === NULL){
	// 			return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
	// 		}

	// 		//subtract two minutes
	// 		$date = date("Y-m-d H:i:s");
	// 		$time = strtotime($date);
	// 		$time = $time - (2 * 60);
	// 		$date = date("Y-m-d H:i:s", $time);


	// 		//check if there is an existing record
	// 		$playerLiveMatch = LiveMatch::where([['player1_id', $player1Id],['player2_id', $player2Id],['created_at','>',$date ]])->first();
	// 		$playerLiveMatch2 = LiveMatch::where([['player2_id', $player1Id],['player1_id', $player2Id],['created_at','>',$date ]])->first();

	// 		if($playerLiveMatch || $playerLiveMatch2){
	// 			if($playerLiveMatch){
	// 				return ['status'=>FALSE, 'liveMatch'=>$playerLiveMatch];
	// 			}else if($playerLiveMatch2){
	// 				return ['status'=>FALSE, 'liveMatch'=>$playerLiveMatch2];
	// 			}
	// 		}else{
	// 			//save liveMatch
	// 			$liveMatch = new LiveMatch;
	// 			$liveMatch->player1_id = $player1Id;
	// 			$liveMatch->player2_id = $player2Id;
	// 			$liveMatch->room_name = $roomName;
	// 			$liveMatch->winner_id = $winnerId;
	// 			$liveMatch->save();

	// 			$winnerPlayer = Player::where([['id', $winnerId]])->first();

	// 			if($roomName === 'london'){
	// 				$winnerPlayer->update(['real_cash_total' => $winnerPlayer->real_cash_total + 2]);

	// 			}else if($roomName === 'sydney'){

	// 				$winnerPlayer->update(['real_cash_total' => $winnerPlayer->real_cash_total + 6]);

	// 			}else if($roomName === 'moscow'){

	// 				$winnerPlayer->update(['real_cash_total' => $winnerPlayer->real_cash_total + 10]);	
	// 			}

	// 			return ['status'=>TRUE, 'liveMatch'=>$liveMatch , 'winnner'=>$winnerPlayer];

	// 		}



	// 	} else {
	// 		return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
	// 	}
	// }

	public function matchResult(Request $request) {
		if ($request->isAuthed) {
			$roomName = $request->input('room_name');
			$winnerPlayer = $request->player;

			if($roomName === NULL ){
				return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
			}



				

				if($roomName === 'london'){
					$winnerPlayer->update(['real_cash_total' => $winnerPlayer->real_cash_total + 2]);

				}else if($roomName === 'sydney'){

					$winnerPlayer->update(['real_cash_total' => $winnerPlayer->real_cash_total + 6]);

				}else if($roomName === 'moscow'){

					$winnerPlayer->update(['real_cash_total' => $winnerPlayer->real_cash_total + 10]);	
				}else{
					return ['status'=>TRUE, 'message'=>'Invalid room name.' ];
				}

				return ['status'=>TRUE,  'player'=>$winnerPlayer];

			



		} else {
			return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
		}
	}

	public function storeMatch(Request $request) {
		if ($request->isAuthed) {


			return ['status'=>TRUE, 'player'=>$request->player];


		} else {
			return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
		}
	}		



}