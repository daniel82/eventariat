<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Location;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["items"] = Location::orderBy("name")->get();

        return view("locations.index", $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Location $location )
    {
        #$data = $this->locationRepository->getFormData($location);
        $data["object"] = $location;

        return view("locations.create", $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Location $location)
    {
        $location = Location::create($request->all());
        $message = "Lokalität wurde gespeichert";

        return redirect()->action('LocationController@edit', ['id' => $location->id ])->with( "flash_message", $message );
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
    public function edit( Request $request, Location $location )
    {
        $data["object"] = $location;

        return view("locations.edit", $data );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location )
    {
        $location->update( $request->all() );

        $message = "Lokalität wurde aktualisiert";

        return redirect()->action('LocationController@edit', ['id' => $location->id ])->with( "flash_message", $message );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Location $location )
    {
        $location->delete();

        $message = "Lokalität wurde gelöscht";
        return redirect()->action('LocationController@index')->with( "flash_message", $message );
    }
}
