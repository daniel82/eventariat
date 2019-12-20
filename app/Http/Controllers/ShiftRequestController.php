<?php

namespace App\Http\Controllers;

use App\ShiftRequest;
use App\Repositories\ShiftRequestRepository;
use Illuminate\Http\Request;

class ShiftRequestController extends Controller
{

    public function __construct( ShiftRequestRepository $shiftRequestRepository )
    {
        $this->shiftRequestRepository = $shiftRequestRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $data = $this->shiftRequestRepository->index($request);

        return view("shift-request.index", $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request, ShiftRequest $shiftRequest)
    {
        // $data["object"] = $shiftRequest;
        $data = $this->shiftRequestRepository->getFormData( $shiftRequest );
        return view("shift-request.create", $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ShiftRequest $shiftRequest )
    {
        $shiftRequest = $this->shiftRequestRepository->store($request, $shiftRequest);

        // TODO trigger ShiftRequestStoredEvent
        $message = "Antrag wurde gespeichert";

        return redirect()->action('ShiftRequestController@edit', ['id' => $shiftRequest->id ])->with( "flash_message", $message );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( ShiftRequest $shiftRequest )
    {
        $data = $this->shiftRequestRepository->getFormData( $shiftRequest );
        return view("shift-request.show", $data );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( ShiftRequest $shiftRequest )
    {
        $data = $this->shiftRequestRepository->getFormData( $shiftRequest );
        return view("shift-request.edit", $data );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShiftRequest $shiftRequest )
    {
        $shiftRequest    = $this->shiftRequestRepository->update($request, $shiftRequest);
        $message         = "Antrag wurde gespeichert";

        return redirect()->action('ShiftRequestController@edit', ['id' => $shiftRequest->id ])->with( "flash_message", $message );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, ShiftRequest $shiftRequest )
    {
        $shiftRequest->delete();
        $message         = "Antrag wurde gelöscht";

        return redirect()->action('ShiftRequestController@index')->with( "flash_message", $message );
    }
}
