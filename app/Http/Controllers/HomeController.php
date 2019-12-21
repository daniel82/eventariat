<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Appointment;
use App\ShiftRequest;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data["leave_days"]     = Appointment::leaveDays()->orderBy("date_from", "desc")->take(10)->get();


        $types =  config("shift-request.type");


        if ($data["shift_requests"] = ShiftRequest::whereStatus(0)->orderBy("created_at", "asc")->take(10)->get())
        {
            foreach ($data["shift_requests"] as $key => &$item)
            {
              $item->type_hr = ( isset($types[$item->type]) ) ? $types[$item->type]["text"] : null;
            }
        }



        if ( $data["birthday_kids"] = User::birthdate(date("m"), (date("m")+6) )->orderByRaw("MONTH(birthdate)")->orderByRaw("DAY(birthdate)")->get() )
        {
            $date = date("Y")."-12-31";
            foreach ($data["birthday_kids"] as $key => &$item)
            {
                $item->age = Carbon::parse($item->birthdate)->diffInYears( $date );
            }

        }



        return view('home', $data);
    }
}
