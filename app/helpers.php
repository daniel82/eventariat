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