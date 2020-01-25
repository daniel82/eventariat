<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\ShiftRequest;

class HeaderProvider extends ServiceProvider
{
  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot( Request $request  )
  {
    view()->composer('layouts/header',
      function($view) use( $request ){
        $head["user"] = \Auth::user();
        $view->with( 'head', $head );
        $view->with( 'count_shift_requests', ShiftRequest::whereStatus(0)->count() );
      }
    );
  }

  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
      //
  }
}
