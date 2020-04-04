<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\ShiftRequest;
use App\User;

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

        if( isset($_COOKIE["return_to_admin"]) && is_numeric($_COOKIE["return_to_admin"]) )
        {
          if ( $user = User::find($_COOKIE["return_to_admin"]) )
          {
            $head["return_name"] = $user->getCalendarName();
            $head["return_link"] = action("UserController@returnAs");
          }

        }

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
