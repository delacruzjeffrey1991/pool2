<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lobby;
use App\LobbyPlayer;
use App\Helper;
use App\PlayFab;
use App\Activity;
use App\AsyncMatch;
use App\Room;
use App\Player;
use DB;
class AsyncController extends Controller
{
	private $playfab;
	public function __construct(Request $request) {
		$this->playfab = new \App\PlayFab();
		if ($request->isAuthed) {
			$this->playfab->setSession($request->player->session_ticket);
		}
	}

	public function match(Request $request) {
		if ($request->isAuthed) {

			$duration = $request->input('duration');
			$missed_balls = $request->input('missed_balls');
			$score = $request->input('score');

			if( $duration === NULL || $missed_balls === NULL || $score === NULL){
      			return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
  			}
			//insert player activity
  			$playerId = $request->player->id;
			$room =  Room::where('player_id' , $playerId)->first();


			$activity = Activity::create([
				'player_id'=> $playerId,
			]);

			$activity->duration = $duration;
			$activity->missed_balls = $missed_balls;
			$activity->score = $score;
			$activity->cash_type = $room->cash_type;
			$activity->room_name = $room->room_name;
			$activity->async_virtual_percentage  = $request->player->async_virtual_percentage;
			$activity->async_real_percentage  = $request->player->async_real_percentage;
			$activity->save();

			//make that 2 activity matched
			$currentPlayerActivity = Activity::where([['player_id' , $request->player->id],['is_matched',false]])->latest()->first();

			//for levels
			if($room->cash_type == 'real'){

				$percentage = $request->player->async_real_percentage;
				if($percentage >= 0 &&  $percentage < 0.33){


					$level = 'Level 1';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_real_percentage','>=',0],['async_real_percentage','<',0.33]])->inRandomOrder()->first();

				}else if ($percentage >= 0.33 &&  $percentage < 0.66){



					$level = 'Level 2';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_real_percentage','>=',0.33],['async_real_percentage','<',0.66]])->inRandomOrder()->first();

				}else{



					$level = 'Level 3';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_real_percentage','>=',0.66],['async_real_percentage','<=',1]])->inRandomOrder()->first();

				}

				

			}else if($room->cash_type == 'virtual'){

				$percentage = $request->player->async_virtual_percentage;

				if($percentage >= 0 &&  $percentage < 0.33){



					$level = 'Level 1';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_virtual_percentage','>=',0],['async_virtual_percentage','<',0.33]])->inRandomOrder()->first();

				}else if ($percentage >= 0.33 &&  $percentage < 0.66){



					$level = 'Level 2';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_virtual_percentage','>=',0.33],['async_virtual_percentage','<',0.66]])->inRandomOrder()->first();

				}else{


					$level = 'Level 3';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_virtual_percentage','>=',0.66],['async_virtual_percentage','<=',1]])->inRandomOrder()->first();

				}


			}
			 
			if($player2Activity){
        
				$player2Activity->update(['is_matched' => true]);
				$currentPlayerActivity->update(['is_matched' => true]);
				
				$match = AsyncMatch::where('activity1_id' , $player2Activity->id)->first();

				$winnersName = $request->player->username;
				$winnersScore = $currentPlayerActivity->score;
				$winnersId = $request->player->id;
				$matchResult = 'Win';

				//for draw
				if($player2Activity->score == 0 && $currentPlayerActivity->score == 0 && !$player2Activity->is_aborted ){

					$roomName = $player2Activity->room_name;
					$cashType = $player2Activity->cash_type;
					$player1 = Player::where('id' , $match->player1_id)->first();
					$currentPlayer = $request->player;
					$originalRealCashTotal = $player1->real_cash_total;
					$originalVirtualCashTotal = $player1->virtual_chips_total;
					$curoriginalRealCashTotal = $currentPlayer->real_cash_total;
					$curoriginalVirtualCashTotal = $currentPlayer->virtual_chips_total;

					if($cashType == 'real'){

						if($roomName == 'london'){

							$player1->update(['real_cash_total' => $originalRealCashTotal + 1]);
							$currentPlayer->update(['real_cash_total' => $curoriginalRealCashTotal + 1]);

						}else if($roomName == 'sydney'){

							$player1->update(['real_cash_total' => $originalRealCashTotal + 3]);
							$currentPlayer->update(['real_cash_total' => $curoriginalRealCashTotal + 3]);

						}else if($roomName == 'moscow'){
							
							$player1->update(['real_cash_total' => $originalRealCashTotal + 5]);
							$currentPlayer->update(['real_cash_total' => $curoriginalRealCashTotal + 5]);
						}


					}else if($cashType == 'virtual'){


						if($roomName == 'london'){

							$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 50]);
							$currentPlayer->update(['virtual_chips_total' => $curoriginalVirtualCashTotal + 50]);

						}else if($roomName == 'sydney'){

							$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 100]);
							$currentPlayer->update(['virtual_chips_total' => $curoriginalVirtualCashTotal + 100]);

						}else if($roomName == 'moscow'){
							
							$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 250]);
							$currentPlayer->update(['virtual_chips_total' => $curoriginalVirtualCashTotal + 250]);
						}
						
					}


					$match->update(['player2_id' => $currentPlayerActivity->player_id ,
					'player2_duration'=>$duration,
					'activity2_id' => $currentPlayerActivity->id,
					'score2' => $currentPlayerActivity->score,
					'player2_name' => $request->player->username,
					'winners_name' => 'draw',
					'room_name' => $room->room_name, 
					'cash_type' => $room->cash_type]);

					$currentPlayerActivity->name = $request->player->username;
					$player2Activity->name = $match->player1_name;

					return ['status'=>TRUE,'currentPlayer'=>$currentPlayerActivity,'opponent'=>$player2Activity,'match_result'=>'draw'];

				}

				//logic for current player loss
				if(  ($player2Activity->score > $currentPlayerActivity->score && !$player2Activity->is_aborted)
					|| ($player2Activity->score == $currentPlayerActivity->score && $player2Activity->duration < $currentPlayerActivity->duration  && !$player2Activity->is_aborted)

				 ){
					$winnersName = $match->player1_name;
					$winnersScore = $match->score1;
					$winnersId = $match->player1_id;
					$matchResult ='Lose';

					//when current player lose we need to update the other player winnings
					$roomName = $player2Activity->room_name;
					$cashType = $player2Activity->cash_type;
					$player1 = Player::where('id' , $match->player1_id)->first();
					$originalRealCashTotal = $player1->real_cash_total;
					$originalVirtualCashTotal = $player1->virtual_chips_total;
					$OriginalRealWin = $player1->async_real_win;
					$OriginalRealLoss = $player1->async_real_loss;
					$OriginalVirtualWin = $player1->async_virtual_win;
					$OriginalVirtualLoss = $player1->async_virtual_loss;
					$originalVitualPercentage = $player1->async_virtual_percentage;

					if($cashType == 'real'){

						//update level for other player 
						if($OriginalRealLoss == 0){
							$newPercentage = 1;
						}else{
							$newPercentage = ($OriginalRealWin + 1)/($OriginalRealLoss + $OriginalRealWin + 1);
						}


						//update level for current player which is loss
						$currentPlayer = $request->player;
						if($currentPlayer->async_real_win == 0){
							$curPlayerWinPercentage = 0;
						}else{
							$curPlayerWinPercentage = ($currentPlayer->async_real_win )/($currentPlayer->async_real_loss + $currentPlayer->async_real_win + 1);
						}

						$currentPlayer->update([
								'async_real_loss' => $currentPlayer->async_real_loss  + 1,
								'async_real_percentage' => $curPlayerWinPercentage]);



						if($roomName == 'london'){

							$player1->update(['real_cash_total' => $originalRealCashTotal + 2,
								'async_real_win' => $OriginalRealWin + 1,
								'async_real_percentage' => $newPercentage
							]);

						}else if($roomName == 'sydney'){

							$player1->update(['real_cash_total' => $originalRealCashTotal + 6,
								'async_real_win' => $OriginalRealWin + 1,
								'async_real_percentage' => $newPercentage]);

						}else if($roomName == 'moscow'){
							
							$player1->update(['real_cash_total' => $originalRealCashTotal + 10,
								'async_real_win' => $OriginalRealWin + 1,
								'async_real_percentage' => $newPercentage]);
						}


					}else if($cashType == 'virtual'){

						//update level for player2
						if($OriginalVirtualLoss == 0){
							$newPercentage = 1;
						}else{
							$newPercentage = ($OriginalVirtualWin + 1)/($OriginalVirtualLoss + $OriginalVirtualWin + 1);
						}

						//update level for current player which is loss
						$currentPlayer = $request->player;
						if($currentPlayer->async_virtual_win == 0){
							$curPlayerWinPercentage = 0;
						}else{
							$curPlayerWinPercentage = ($currentPlayer->async_virtual_win )/($currentPlayer->async_virtual_loss + $currentPlayer->async_virtual_win + 1);
						}
						$currentPlayer->update([
								'async_virtual_loss' => $currentPlayer->async_virtual_loss  + 1,
								'async_virtual_percentage' => $curPlayerWinPercentage]);

						if($roomName == 'london'){

							$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 100,
								'async_virtual_win' => $OriginalVirtualWin + 1,
								'async_virtual_percentage' => $newPercentage]);

						}else if($roomName == 'sydney'){

							$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 200,
								'async_virtual_win' => $OriginalVirtualWin + 1,
								'async_virtual_percentage' => $newPercentage]);

						}else if($roomName == 'moscow'){
							
							$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 500,
								'async_virtual_win' => $OriginalVirtualWin + 1,
								'async_virtual_percentage' => $newPercentage]);
						}
						
					}

				}else{//current player win update of cash is in the client side
					$otherPlayer = Player::where('id' , $match->player1_id)->first();
					$currentPlayer = $request->player;

					if($room->cash_type == 'real'){

						//update for current player which is win
						if($currentPlayer->async_real_loss == 0){
							$curPlayerWinPercentage = 1;
						}else{
							$curPlayerWinPercentage = ($currentPlayer->async_real_win + 1)/($currentPlayer->async_real_win + $currentPlayer->async_real_loss + 1);
						}
						
						$currentPlayer->update([
							'async_real_win' => $currentPlayer->async_real_win  + 1,
							'async_real_percentage' => $curPlayerWinPercentage]);

						//update for other player whis is loss
						if($otherPlayer->async_real_win == 0){
							$otherPlayerWinPercentage = 0;
						}else{
							$otherPlayerWinPercentage = ($otherPlayer->async_real_win )/($otherPlayer->async_real_win + $otherPlayer->async_real_loss + 1);
						}

						$otherPlayer->update([
							'async_real_loss' => $otherPlayer->async_real_loss  + 1,
							'async_real_percentage' => $otherPlayerWinPercentage]);


					}else if($room->cash_type == 'virtual'){

						//update for current player which is win
						if($currentPlayer->async_virtual_loss == 0){
							$curPlayerWinPercentage = 1;
						}else{
							$curPlayerWinPercentage = ($currentPlayer->async_virtual_win + 1)/($currentPlayer->async_virtual_win + $currentPlayer->async_virtual_loss + 1);
						}
						
						$currentPlayer->update([
							'async_virtual_win' => $currentPlayer->async_virtual_win  + 1,
							'async_virtual_percentage' => $curPlayerWinPercentage]);

						//update for other player whis is loss
						if($otherPlayer->async_virtual_win == 0){
							$otherPlayerWinPercentage = 0;
						}else{
							$otherPlayerWinPercentage = ($otherPlayer->async_virtual_win )/($otherPlayer->async_virtual_win + $otherPlayer->async_virtual_loss + 1);
						}

						$otherPlayer->update([
							'async_virtual_loss' => $otherPlayer->async_virtual_loss  + 1,
							'async_virtual_percentage' => $otherPlayerWinPercentage]);

					}



				}

				//if 1st player is_aborted automatic win
				if($player2Activity->is_aborted){
					$winnersName = $request->player->username;
					$winnersScore = $currentPlayerActivity->score;
					$winnersId = $request->player->id;
					$matchResult = 'Win';

					$player2Activity->duration = 'Aborted';
					$player2Activity->missed_balls = 'Aborted';
					$player2Activity->score = 'Aborted';
					$player2Activity->pocketed_balls = 'Aborted';
					$player2Activity->wrong_balls = 'Aborted';

					$roomName = $player2Activity->room_name;
					$cashType = $player2Activity->cash_type;
					$player1 = Player::where('id' , $match->player1_id)->first();
					$OriginalRealWin = $player1->async_real_win;
					$OriginalRealLoss = $player1->async_real_loss;
					$OriginalVirtualWin = $player1->async_virtual_win;
					$OriginalVirtualLoss = $player1->async_virtual_loss;
					$originalVitualPercentage = $player1->async_virtual_percentage;

					if($cashType == 'real'){

						//update level for current player which is win
						$currentPlayer = $request->player;
						if($currentPlayer->async_real_loss == 0){
							$curPlayerWinPercentage = 1;
						}else{
							$curPlayerWinPercentage = ($currentPlayer->async_real_win + 1 )/($currentPlayer->async_real_loss + $currentPlayer->async_real_win + 1);
						}

						$currentPlayer->update([
								'async_real_win' => $currentPlayer->async_real_win  + 1,
								'async_real_percentage' => $curPlayerWinPercentage]);


						//update level for other player 
						if($OriginalRealWin == 0){
							$newPercentage = 0;
						}else{
							$newPercentage = ($OriginalRealWin )/($OriginalRealLoss + $OriginalRealWin + 1);
						}


						$player1->update(['async_real_loss' => $OriginalRealLoss + 1,
								'async_real_percentage' => $newPercentage]);

					

					}else if($cashType == 'virtual'){

						//update level for current player which is win
						$currentPlayer = $request->player;
						if($currentPlayer->async_virtual_loss == 0){
							$curPlayerWinPercentage = 1;
						}else{
							$curPlayerWinPercentage = ($currentPlayer->async_virtual_win + 1 )/($currentPlayer->async_virtual_loss + $currentPlayer->async_virtual_win + 1);
						}

						$currentPlayer->update([
								'async_virtual_win' => $currentPlayer->async_virtual_win  + 1,
								'async_virtual_percentage' => $curPlayerWinPercentage]);


						//update level for other player 
						if($OriginalVirtualWin == 0){
							$newPercentage = 0;
						}else{
							$newPercentage = ($OriginalVirtualWin )/($OriginalVirtualLoss + $OriginalVirtualWin + 1);
						}


						$player1->update(['async_virtual_loss' => $OriginalVirtualLoss + 1,
								'async_virtual_percentage' => $newPercentage]);
					}
				}


				$match->update(['player2_id' => $currentPlayerActivity->player_id ,
					'player2_duration'=>$duration,
					'activity2_id' => $currentPlayerActivity->id,
					'score2' => $currentPlayerActivity->score,
					'player2_name' => $request->player->username,
					'winners_id' => $winnersId,
					'winners_name' => $winnersName,
					'winners_score' => $winnersScore,
					'room_name' => $room->room_name, 
					'cash_type' => $room->cash_type]);

				$currentPlayerActivity->name = $request->player->username;
				$player2Activity->name = $match->player1_name;
				$player2Activity->level = $level;
				$currentPlayerActivity->level = $level;

				return ['status'=>TRUE,'currentPlayer'=>$currentPlayerActivity,'opponent'=>$player2Activity,'match_result'=>$matchResult];
			}else{//logic for no players is waiting
				//will create match
				$match = AsyncMatch::create([
					'player1_id'=>$request->player->id,
					'score1'=>$currentPlayerActivity->score,
					'activity1_id'=>$currentPlayerActivity->id,
					'player1_name'=>$request->player->username,
					'player1_duration'=>$duration

					]);
				$match->save();

        //return ['status'=>TRUE,'message'=>'No Match Is Available'];
				return ['status'=>TRUE,'match_id'=>$match->id,'score'=>$currentPlayerActivity->score,'message'=>'No Match Is Available'];
			}


		} else {
			return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
		}
	}

	public function getResultByMatchId(Request $request, $matchId) {
		$match = AsyncMatch::find($matchId);
		if($match){
			$match->status = 'COMPLETED';
			if($match->winners_id == 0 && $match->winners_score == 0){
				$match->status = 'PENDING';
			}
			return ['status'=>TRUE, 'match'=>$match];
		}else{
			return ['status'=>TRUE, 'message'=>'No Match Found'];
		}
		
	}

	public function getResultByPlayerId(Request $request, $playerId) {
		$matches = AsyncMatch::where('player1_id' , $playerId)->orwhere('player2_id' , $playerId)->get();
		if($matches){
			foreach ($matches as $match) {
				$match->status = 'COMPLETED';
				if($match->winners_id == 0 && $match->winners_score == 0){
					$match->status = 'PENDING';
				}

				//Duration format
				$player1Duration = $match->player1_duration;
				$player1mins = floor($player1Duration / 60 % 60);
				$player1secs = floor($player1Duration % 60);
				$match->player1_duration = sprintf('%02d:%02d', $player1mins, $player1secs);

				$player2Duration = $match->player2_duration;
				$player2mins = floor($player2Duration / 60 % 60);
				$player2secs = floor($player2Duration % 60);
				$match->player2_duration = sprintf('%02d:%02d', $player2mins, $player2secs);

				//if aborted make response format aborted
				if($match->is_aborted){
					$match->player1_duration = 'Aborted';
					$match->player1_score = 'Aborted';
				}

				if($match->is_aborted2){
					$match->player2_duration = 'Aborted';
					$match->player2_score = 'Aborted';
				}


			}

			return ['status'=>TRUE, 'matches'=>$matches];
		}else{
			return ['status'=>TRUE, 'message'=>'No Match Found'];
		}

		
	}

	public function getMatches(Request $request) {
	    $matches = AsyncMatch::all();
	    return ['status'=>TRUE, 'matches'=>$matches];

		
	}

	public function abortedMatch(Request $request) {
		if ($request->isAuthed) {



			//insert player activity
  			$playerId = $request->player->id;
			$room =  Room::where('player_id' , $playerId)->first();


			$activity = Activity::create([
				'player_id'=> $playerId,
			]);

			$activity->duration = 0;
			$activity->missed_balls = 0;
			$activity->score = 0;
			$activity->pocketed_balls = 0;
			$activity->wrong_balls = 0;
			$activity->is_aborted = TRUE;
			$activity->cash_type = $room->cash_type;
			$activity->room_name = $room->room_name;
			$activity->async_virtual_percentage  = $request->player->async_virtual_percentage;
			$activity->async_real_percentage  = $request->player->async_real_percentage;

			$activity->save();



			//make that 2 activity matched
			$currentPlayerActivity = Activity::where([['player_id' , $request->player->id],['is_matched',false]])->latest()->first();
			if($room->cash_type == 'real'){

				$percentage = $request->player->async_real_percentage;
				if($percentage >= 0 &&  $percentage < 0.33){

					$level = 'Level 1';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_real_percentage','>=',0],['async_real_percentage','<',0.33],['is_aborted',false]])->inRandomOrder()->first();

				}else if ($percentage >= 0.33 &&  $percentage < 0.66){



					$level = 'Level 2';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_real_percentage','>=',0.33],['async_real_percentage','<',0.66],['is_aborted',false]])->inRandomOrder()->first();

				}else{


					$level = 'Level 3';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_real_percentage','>=',0.66],['async_real_percentage','<=',1],['is_aborted',false]])->inRandomOrder()->first();

				}

				

			}else if($room->cash_type == 'virtual'){

				$percentage = $request->player->async_virtual_percentage;

				if($percentage >= 0 &&  $percentage < 0.33){


					$level = 'Level 1';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_virtual_percentage','>=',0],['async_virtual_percentage','<',0.33],['is_aborted',false]])->inRandomOrder()->first();

				}else if ($percentage >= 0.33 &&  $percentage < 0.66){


					$level = 'Level 2';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_virtual_percentage','>=',0.33],['async_virtual_percentage','<',0.66],['is_aborted',false]])->inRandomOrder()->first();

				}else{

					$level = 'Level 3';

					$player2Activity = Activity::where([['player_id', '<>', $request->player->id],['is_matched',false],['room_name',$currentPlayerActivity->room_name],['cash_type',$currentPlayerActivity->cash_type],['async_virtual_percentage','>=',0.66],['async_virtual_percentage','<=',1],['is_aborted',false]])->inRandomOrder()->first();

				}


			}
			 
			if($player2Activity){
        
				$player2Activity->update(['is_matched' => true]);
				$currentPlayerActivity->update(['is_matched' => true]);
				
				$match = AsyncMatch::where('activity1_id' , $player2Activity->id)->first();


				$winnersName = $match->player1_name;
				$winnersScore = $match->score1;
				$winnersId = $match->player1_id;
				$matchResult ='Lose';

				//when current player lose we need to update the other player winnings
				$roomName = $player2Activity->room_name;
				$cashType = $player2Activity->cash_type;
				$player1 = Player::where('id' , $match->player1_id)->first();
				$originalRealCashTotal = $player1->real_cash_total;
				$originalVirtualCashTotal = $player1->virtual_chips_total;
				$OriginalRealWin = $player1->async_real_win;
				$OriginalRealLoss = $player1->async_real_loss;
				$OriginalVirtualWin = $player1->async_virtual_win;
				$OriginalVirtualLoss = $player1->async_virtual_loss;
				$originalVitualPercentage = $player1->async_virtual_percentage;

				if($cashType == 'real'){

					//update level for other player 
					if($OriginalRealLoss == 0){
						$newPercentage = 1;
					}else{
						$newPercentage = ($OriginalRealWin + 1)/($OriginalRealLoss + $OriginalRealWin + 1);
					}


					//update level for current player which is loss
					$currentPlayer = $request->player;
					if($currentPlayer->async_real_win == 0){
						$curPlayerWinPercentage = 0;
					}else{
						$curPlayerWinPercentage = ($currentPlayer->async_real_win )/($currentPlayer->async_real_loss + $currentPlayer->async_real_win + 1);
					}

					$currentPlayer->update([
							'async_real_loss' => $currentPlayer->async_real_loss  + 1,
							'async_real_percentage' => $curPlayerWinPercentage]);



					if($roomName == 'london'){

						$player1->update(['real_cash_total' => $originalRealCashTotal + 2,
							'async_real_win' => $OriginalRealWin + 1,
							'async_real_percentage' => $newPercentage
						]);

					}else if($roomName == 'sydney'){

						$player1->update(['real_cash_total' => $originalRealCashTotal + 6,
							'async_real_win' => $OriginalRealWin + 1,
							'async_real_percentage' => $newPercentage]);

					}else if($roomName == 'moscow'){
						
						$player1->update(['real_cash_total' => $originalRealCashTotal + 10,
							'async_real_win' => $OriginalRealWin + 1,
							'async_real_percentage' => $newPercentage]);
					}


				}else if($cashType == 'virtual'){

					//update level for player2
					if($OriginalVirtualLoss == 0){
						$newPercentage = 1;
					}else{
						$newPercentage = ($OriginalVirtualWin + 1)/($OriginalVirtualLoss + $OriginalVirtualWin + 1);
					}

					//update level for current player which is loss
					$currentPlayer = $request->player;
					if($currentPlayer->async_virtual_win == 0){
						$curPlayerWinPercentage = 0;
					}else{
						$curPlayerWinPercentage = ($currentPlayer->async_virtual_win )/($currentPlayer->async_virtual_loss + $currentPlayer->async_virtual_win + 1);
					}
					$currentPlayer->update([
							'async_virtual_loss' => $currentPlayer->async_virtual_loss  + 1,
							'async_virtual_percentage' => $curPlayerWinPercentage]);

					if($roomName == 'london'){

						$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 100,
							'async_virtual_win' => $OriginalVirtualWin + 1,
							'async_virtual_percentage' => $newPercentage]);

					}else if($roomName == 'sydney'){

						$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 200,
							'async_virtual_win' => $OriginalVirtualWin + 1,
							'async_virtual_percentage' => $newPercentage]);

					}else if($roomName == 'moscow'){
						
						$player1->update(['virtual_chips_total' => $originalVirtualCashTotal + 500,
							'async_virtual_win' => $OriginalVirtualWin + 1,
							'async_virtual_percentage' => $newPercentage]);
					}
					
				}


				$match->update(['player2_id' => $currentPlayerActivity->player_id ,
					'player2_duration'=>0,
					'activity2_id' => $currentPlayerActivity->id,
					'score2' => 0,
					'is_aborted2'=> TRUE,
					'player2_name' => $request->player->username,
					'winners_id' => $winnersId,
					'winners_name' => $winnersName,
					'winners_score' => $winnersScore,
					'room_name' => $room->room_name, 
					'cash_type' => $room->cash_type]);

				$currentPlayerActivity->name = $request->player->username;
				$player2Activity->name = $match->player1_name;
				$player2Activity->level = $level;
				$currentPlayerActivity->level = $level;

				$currentPlayerActivity->duration = 'Aborted';
				$currentPlayerActivity->missed_balls = 'Aborted';
				$currentPlayerActivity->score = 'Aborted';
				$currentPlayerActivity->pocketed_balls = 'Aborted';
				$currentPlayerActivity->wrong_balls = 'Aborted';

				return ['status'=>TRUE,'currentPlayer'=>$currentPlayerActivity,'opponent'=>$player2Activity,'match_result'=>$matchResult];
			}else{//logic for not players waiting
				//will create match
				$match = AsyncMatch::create([
					'player1_id'=>$request->player->id,
					'score1'=>0,
					'activity1_id'=>$currentPlayerActivity->id,
					'player1_name'=>$request->player->username,
					'is_aborted'=> TRUE,
					'player1_duration'=>0

					]);
				$match->save();

				return ['status'=>TRUE,'match_id'=>$match->id,'message'=>'Aborted'];
			}


		} else {
			return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
		}
	}

	public function createMatch(Request $request) {

		$match = AsyncMatch::create([
			'player1_id'=>'1',
			'score1'=>'20'

			]);
		$match->save();
		return ['status'=>TRUE];

	}


  //determines the winner
	public function status(Request $request, $lobbyId) {
		$lobby = Lobby::find($lobbyId);
		$lobbyPlayers = LobbyPlayer::where(['lobby_id'=>$lobbyId])->get();
		if (count($lobbyPlayers) == 2) {
			$player1 = $lobbyPlayers[0];
			$player2 = $lobbyPlayers[1];
      // if ($player1->is_gameover)
			return ['status'=>True];
		} else {
			return ['status'=>True, 'message'=>'No challenger yet'];
		}
	}
  //completed async event
	public function completed(Request $request, $lobbyPlayerId) {
		$lobbyPlayer = LobbyPlayer::find($lobbyPlayerId);
		$lobbyPlayer->pocketed_balls = $request->input('pocketed_balls');
		$lobbyPlayer->missed_balls = $request->input('missed_balls');
		$lobbyPlayer->wronged_balls = $request->input('wronged_balls');
		$lobbyPlayer->is_gameover = $request->input('is_gameover');
		$lobbyPlayer->is_scratches = $request->input('is_scratches');
		$lobbyPlayer->duration = $request->input('duration');
		$lobbyPlayer->is_completed = TRUE;
		$lobbyPlayer->save();
		$lobbyPlayers = LobbyPlayer::where(['lobby_id'=>$lobbyPlayer->lobby_id])->get();
		if (count($lobbyPlayers) == 2) {
        //check winner and loser
			$player1 = $lobbyPlayers[0];
			$player2 = $lobbyPlayers[1];
			$player1Points = 0;
			$player2Points = 0;
			if ($player1->is_gameover > $player2->is_gameover) {
				$player2Points = 1;
			}
			if ($player1->is_gameover < $player2->is_gameover) {
				$player1Points = 1;
			}
			if ($player1->pocketed_balls > $player2->pocketed_balls) {
				$player1Points = 1;
			} else {
				$player2Points = 1;
			}
			if ($player1->duration < $player2->duration) {
				$player1Points = 1;
			} else {
				$player2Points = 1;
			}
			if ($player1->missed_balls > $player2->missed_balls) {
				$player2Points = 1;
			} else {
				$player1Points = 1;
			}
			if ($player1->wronged_balls > $player2->wronged_balls) {
				$player2Points = 1;
			} else {
				$player1Points = 1;
			}
			if ($player1->is_scratches > $player2->is_scratches) {
				$player2Points = 1;
			} else if ($player1->is_scratches < $player2->is_scratches){
				$player1Points = 1;
			}
			if ($player1Points > $player2Points) {
          //winner player 1
          //loser player 2
          // send playfab notification
          // return ['status'=>TRUE, 'player1Points'=>$player1Points, 'player2Points'=>$player2Points, 'result'=>''];
			} else {
          //winner player2
          //loser player1
          //send playfab notification
			}
        //update lobby winner_id and loser_id
        //set lobby to completed
		} else {
        //notify other player that you complete the game
		}
		return ['status'=>TRUE];
	}
  //get match
  // public function match(Request $request) {
  //   //should be in async mode lobby
  //   if ($request->isAuthed) {
  //     $match = Lobby::where(['is_async'=>TRUE, 'status'=>'created'])->inRandomOrder()->first();
  //     if ($match) {
  //       $lobbyPlayer = LobbyPlayer::firstOrCreate([
  //         'lobby_id'=>$match->id,
  //         'player_id'=>$request->player->id,
  //       ]);
  //     }
  //     return ['status'=>TRUE, 'completion_id'=>$lobbyPlayer->id, 'lobby'=>$match];
  //   } else {
  //     return ['status'=>FALSE, 'message'=>'Unauthorized Access'];
  //   }
  // }
  //create if no match found
	public function create(Request $request) {
		if ($request->isAuthed) {
			$amount = Helper::getSetting('game.amount', 10);
			$region = Helper::getSetting('game.region', 'GB');
			$lobby = Lobby::where(['created_by'=>$request->player->id])->orderBy('created_at', 'DESC')->first();
      // $lobby->is_async = TRUE;
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
					'is_async'=>TRUE,
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

	public function deleteAll(Request $request){
		AsyncMatch::truncate();
		Activity::truncate();
	}
}