<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class AppointmentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
      // $month
      $date_from      = $request->get("date_from", date("Y-m-")."01" );
      $date_to        = $request->get("date_to", date("Y-m-")."31" );

      $from_month      = Carbon::create($date_from)->month;
      $to_month        = Carbon::create($date_to)->month;

      $period          = new CarbonPeriod($date_from, '1 day', $date_to);


      $data = [];

      // birthdays
      $users          = User::birthdate($from_month, $to_month)->get();

      // events without location
      $events         = Appointment::events()->noLocation()->dateFromBetween($date_from, $date_to)->orderBy("date_from")->get();

      // the users leave days
      $leaveDays      = Appointment::leaveDays()->dateFromBetween($date_from, $date_to)->orderBy("date_from", "ASC")->orderBy("user_id", "ASC")->get();

      // dd($period);
      $work=[];
      foreach ($period as $key => $date)
      {
        $the_date = $date->format("Y-m-d");

        $work[$the_date] = Appointment::work()->dateFrom($the_date)->orderBy("date_from", "ASC")->orderBy("location_id", "ASC")->get();
        # code...
      }
      // work

      // dd($work);
      return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
