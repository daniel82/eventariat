<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
  protected $fillable = ["date", "temparature", "icon"];


  public static function getAsJsonByDate( $date )
  {
    $json = null;
    if ( $forecast = WeatherForecast::where("date", $date)->first() )
    {
      $json = [
        "icon" => $forecast->icon.".svg",
        "temperature" => $forecast->temperature
      ];
    }

    return $json;
  }
}
