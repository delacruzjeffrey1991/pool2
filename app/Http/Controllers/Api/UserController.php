<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{

    public function index() {
      $users = User::all();
      return ['status'=>TRUE, 'users'=>$users];
    }

    public function show($userId) {
      $user = User::find($userId);
      return ['status'=>TRUE, 'user'=>$user];
    }

    public function login(Request $request){
    $email = $request->input('email');
    $password = $request->input('password');

    $user = User::where([['email' , $email],['password' , $password]])->get();
		if($user){

			return ['status'=>TRUE, '$user'=>$user];
		}else{
			return ['status'=>FALSE, 'message'=>'No Match Found'];
		}
    }

}
