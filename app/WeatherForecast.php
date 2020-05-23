<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
  protected $fillable = ["date", "temparature", "icon"];


  public static function getByPeriod($from, $to)
  {
    $where =
    [
      ["date", ">=", $from ],
      ["date", "<=", $to ]
    ];

    $weather = [];

    if ( $weather_forecasts = WeatherForecast::where($where)->get() )
    {
      foreach ($weather_forecasts as $key => $forecast)
      {
        $weather[$forecast->date] = [
                                      "icon" => $forecast->icon.".svg",
                                      "temperature" => $forecast->temperature
                                    ];
      }
    }

    return $weather;
  }



}