<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Watch;

class WatchController extends Controller
{

  public function test()
  {
    $w = Watch::firstOrNew(["name"=> "testx"]);
    $w->code= 123;
    $w->save();
  }


}
