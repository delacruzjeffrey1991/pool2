<?php

namespace App;
use App\Setting;

class Helper
{
  public static function getSetting($name='', $default='') {
    $setting = Setting::where('name', $name)->first();
    if ($setting) {
      return $setting->value;
    } else {
      return $default;
    }
  }

  public static function setSetting($name='', $value='') {
    Setting::where('name', $name)->update(['value'=>$value]);
    return TRUE;
  }

}
