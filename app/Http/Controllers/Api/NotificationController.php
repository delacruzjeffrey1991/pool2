<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notification;

class NotificationController extends Controller
{

    public function index() {
      $notifications = Notification::all();
      return ['status'=>TRUE, 'notifications'=>$notifications];
    }


    public function show($notificationId) {
      $notification = Notification::find($notificationId);
      return ['status'=>TRUE, 'notification'=>$notification];
    }


}
