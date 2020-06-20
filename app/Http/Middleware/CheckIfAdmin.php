<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $user = Auth::user();
      $may = false;

      if ( is_object($user) && $user->isAdmin() ){
        if ( $request->is("admin/*")  )
        {
          $may = true;
        }
      }
      if ( $may ) {
        return $next($request);
      }
      else{
          return abort(404);
          // return redirect('/');
      }
    }
}
