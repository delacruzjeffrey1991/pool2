<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rating;
use App\Player;
use DB;
class RatingsController extends Controller
{


  public function updateRating(Request $request) {
    if ($request->isAuthed) {
      $category = $request->input('category');
      $player1 = $request->input('player1_id');
      $player2 = $request->input('player2_id');
      $winner = $request->input('winner_id');

      if($category === NULL || $player2 === NULL || $player1 === NULL || $winner === NULL ){
       return ['status'=>FALSE, 'message'=>'Missing Required Parameters'];
     }

     $rating1 = Rating::where('player_id' , $player1)->first();

     if($rating1 === NULL){
      $rating1 = Rating::create(['player_id' => $player1,
        'async_real' => 2000, 
        'async_virtual' => 2000,
        'normal_real' => 2000,
        'normal_virtual' => 2000        
        ]);
    }



    $rating2 = Rating::where('player_id' , $player2)->first();

    if($rating2 === NULL ){
      $rating2 = Rating::create(['player_id' => $player2,
        'async_real' => 2000, 
        'async_virtual' => 2000,
        'normal_real' => 2000,
        'normal_virtual' => 2000        
        ]);
    }



    if($winner == $player1){
      if($category == 'async_real'){
        $rating1->update([ 'async_real' => $rating1->async_real + 20]);
        $rating2->update([ 'async_real' => $rating2->async_real - 20]);         
      }else if($category == 'async_virtual'){  
        $rating1->update([ 'async_virtual' => $rating1->async_virtual + 20]);
        $rating2->update([ 'async_virtual' => $rating2->async_virtual - 20]); 
      }else if($category == 'normal_real'){
        $rating1->update([ 'normal_real' => $rating1->normal_real + 20]);
        $rating2->update([ 'normal_real' => $rating2->normal_real - 20]); 
      }else if($category == 'normal_virtual'){
        $rating1->update([ 'normal_virtual' => $rating1->normal_virtual + 20]);
        $rating2->update([ 'normal_virtual' => $rating2->normal_virtual - 20]); 
      }
    }  

    if($winner == $player2){
        if($category == 'async_real'){
          $rating1->update([ 'async_real' => $rating1->$category - 20]);
          $rating2->update([ 'async_real' => $rating2->$category + 20]);         
        }else if($category == 'async_virtual'){  
          $rating1->update([ 'async_virtual' => $rating1->$category - 20]);
          $rating2->update([ 'async_virtual' => $rating2->$category + 20]); 
        }else if($category == 'normal_real'){
          $rating1->update([ 'normal_real' => $rating1->$category - 20]);
          $rating2->update([ 'normal_real' => $rating2->$category + 20]); 
        }else if($category == 'normal_virtual'){
          $rating1->update([ 'normal_virtual' => $rating1->$category - 20]);
          $rating2->update([ 'normal_virtual' => $rating2->$category + 20]); 
        }

     }   
        return ['status'=>true, 'rating1'=>$rating1, 'rating2'=> $rating2];

      } else {
        return ['status'=>FALSE, 'message'=>'Unauthorize Accesss'];
      }
    }

  // public function getResultByMatchId(Request $request, $matchId) {
  //   $match = AsyncMatch::find($matchId);
  //   if($match){
  //     $match->status = 'COMPLETED';
  //     if($match->winners_id == 0 && $match->winners_score == 0){
  //       $match->status = 'PENDING';
  //     }
  //     return ['status'=>TRUE, 'match'=>$match];
  //   }else{
  //     return ['status'=>TRUE, 'message'=>'No Match Found'];
  //   }
    
  // }

    public function getRatingByPlayerId(Request $request, $playerId) {
      $rating = Rating::where('player_id' , $playerId)->get();
      if(!$rating->isEmpty()){
        return ['status'=>TRUE, 'ratings'=>$rating];
      }else{
       return ['status'=>TRUE, 'message'=>'No Rating for Player Found'];
     }


   }


 }