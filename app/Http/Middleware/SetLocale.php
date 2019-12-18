<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;


class SetLocale
{
  protected $languages = ['en','de'];

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    App::setLocale( "de" );
    setlocale(LC_TIME, 'German');



    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    // header("Content-Security-Policy: default-src 'self' ".env("APP_URL")." https://www.googletagmanager.com https://www.google-analytics.com  https://ds-aksb-a.akamaihd.net data: 'unsafe-inline' 'unsafe-eval'; ");
    // header("Referrer-Policy: none-when-downgrade");
    // // header("Feature-Policy: vibrate 'self'");
    header("X-Content-Type-Options: nosniff");


    return $next($request);
  }
}
