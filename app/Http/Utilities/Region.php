<?php

namespace App\Http\Utilities;

class Region {

  protected static $regions = [
    "USCentral"     => "usc",
    "USEast"        => "use",
    "EUwest"        => "uew",
    "Singapore"     => "sgp",
    "Japan"         => "jap",
    "Brazil"        => "bra",
    "Australia"     => "aus",
  ];

  public static function all() {
    return array_keys(static::$regions);
  }
}

?>
