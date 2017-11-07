<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Country;

class SettingController extends Controller
{

    public function index() {
      $settings = Setting::all();
      $setters = [];
      foreach($settings AS $setting) {
        $setters[$setting->name] = $setting->value;
      }
      return ['status'=>TRUE, 'setting'=>$setters];
    }


    public function show($settingId) {
      $setting = Setting::find($settingId);
      return ['status'=>TRUE, 'setting'=>$setting];
    }

    //SETTINGS FOR GAME
    public function game() {
      return ['status'=>TRUE];
    }

    //SETTINGS FOR VC
    public function vc() {
      return ['status'=>TRUE];
    }

    //SETTINGS FOR PLAYER
    public function player() {
      return ['status'=>TRUE];
    }

    public function getSupportedCountries() {
      $countries = Country::all();

      return ['status'=>TRUE, 'countries'=>$countries, 'supported'=>TRUE, 'modes'=>['cash', 'chip']];
    }

    public function update($id, Request $request) {

    }

    public function destroy($id) {

    }
}
