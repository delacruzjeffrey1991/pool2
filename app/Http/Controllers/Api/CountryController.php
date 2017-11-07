<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;

class CountryController extends Controller
{

    public function index() {
      $countries = Country::all();
      return ['status'=>TRUE, 'countries'=>$countries];
    }

    public function show($countryId) {
      $country = Country::find($countryId);
      return ['status'=>TRUE, 'country'=>$country];
    }

}
