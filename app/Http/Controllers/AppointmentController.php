<?php

namespace App\Http\Controllers;

use App;
use PDF;
use App\Repositories\AppointmentRepository;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct( AppointmentRepository $appointmentRepository )
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $data = $this->appointmentRepository->index( $request );
        return view("appointment.index", $data );
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
     * @param    \Illuminate\Http\Request    $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            //
    }

    /**
     * Display the specified resource.
     *
     * @param    int    $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int    $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request    $request
     * @param    int    $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int    $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            //
    }




    public function pdf( Request $request )
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);

        $request["date_from"] = "2019-12-25";
        $request["date_to"]   = "2020-02-02";

        $object   = new \App\Repositories\AppointmentApiRepository();
        $data     = $object->index( $request );

        if ( $data )
        {
          view()->share(
            [
              'items' => $data["items"]
            ]
          );
        }

        // dd($data["items"]);

        $pdf = PDF::loadView('exports.appointments_pdf')->setPaper('a4');
        $filename = 'dienstplan-'.date("dmY").'.pdf';
        return $pdf->stream($filename);
    }
}
