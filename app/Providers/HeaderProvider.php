<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Cart;

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
        // dd($head);

        $view->with( 'head', $head );
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
