<?php

if ( !function_exists("formatDate") )
{
  function formatDate( $date, $format = "d.m.Y h:i:s" )
  {
    return ( $date ) ? date( $format, strtotime($date) ) : null;
  }

}

if ( !function_exists("activeHeaderItem") )
{
  function activeHeaderItem( $route )
  {
    return ( (\Request::is($route)) ) ? "active" : null;
  }
}

if ( !function_exists("isSelected") )
{
  function isSelected( $is, $required )
  {

    return ( $is === $required) ? ' selected="selected" ' : null;
  }
}

if ( !function_exists("getHours") )
{
  function getHours( $type="from")
  {
    $hours = collect();
    $hours->put( "" , "---" );
    $minutes = ["00", "15", "30", "45"];
    foreach ( range(0,23) as $key => $hour)
    {
      $hour = ($hour<10) ? "0".$hour : $hour;

      foreach ($minutes as $quarter)
      {
        $hours->put( $hour.":".$quarter , $hour.":".$quarter );
      }
    }

    if ( $type === "to")
    {
      $hours->put( "23:59" , "23:59" );
      $hours->put( "24:00" , "24:00" );
    }

    return $hours;
  }
}