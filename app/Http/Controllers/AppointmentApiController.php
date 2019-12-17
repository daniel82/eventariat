<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Repositories\AppointmentApiRepository;
use App\Appointment;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class AppointmentApiController extends Controller
{
  public function __construct( AppointmentApiRepository  $appointmentApiRepository )
  {
    setlocale(LC_TIME, 'German');
    $this->appointmentApiRepository = $appointmentApiRepository;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index( Request $request )
  {
    $data = $this->appointmentApiRepository->index($request);

    return response()->json( $data );
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
        Log::debug("AppointmentApiController@store");
        $data = $this->appointmentApiRepository->store($request);
        Log::debug($data);

        return response()->json($data);

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
        Log::debug("AppointmentApiController@update");
        $data = $this->appointmentApiRepository->update( $request, $id );
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::debug("AppointmentApiController@delete");
        $data = $this->appointmentApiRepository->destroy( $id );
        return response()->json($data);
    }
}
