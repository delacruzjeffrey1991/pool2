<?php

namespace App\Http\Utilities;

class Status {

  protected static $statuses = [
    "Created"     => "cre",
    "Started"     => "str",
    "Completed"   => "cmd",
    "Archived"    => "arc",
  ];

  public static function all() {
    return array_keys(static::$statuses);
  }
}

?>
