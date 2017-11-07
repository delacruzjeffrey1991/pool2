<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Activity;

class ActivityController extends Controller
{

    public function index() {
      $activities = Activity::all();
      if($activities){
			foreach ($activities as $activity) {

				$duration = $activity->duration;
				$min = floor($duration / 60 % 60);
				$secs = floor($duration % 60);
				$activity->duration = sprintf('%02d:%02d', $min, $secs);

			}
		}	
      return ['status'=>TRUE, 'activities'=>$activities];
    }

    public function show($activityId) {
      $activity = Activity::find($activityId);
      return ['status'=>TRUE, 'activity'=>$activity];
    }

}
