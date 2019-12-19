<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Requests; // use the namespace of the request

use App\Http\Requests\CreateUser; // use the namespace of the request
use App\Repositories\UserRepository;
use App\User;

use Illuminate\Contracts\Auth\Authenticatable;




class UserController extends Controller
{

    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["items"] = User::orderBy("first_name")->get();

        return view("users.index", $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( User $user )
    {
        $data = $this->userRepository->getFormData($user);
        // $data["object"] = $user;

        return view("users.create", $data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateUser $request, User $user)
    {
        $request = $this->userRepository->sanitizeRequest($request);
        $user = User::create( $request->all() );
        // dd( $request->all() );
        $message = "Mitarbeiter wurde gespeichert";

        return redirect()->action('UserController@edit', ['id' => $user->id ])->with( "flash_message", $message );
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
    public function edit( Request $request, User $user )
    {
        $data = $this->userRepository->getFormData($user);

        return view("users.edit", $data );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user )
    {
        $request = $this->userRepository->sanitizeRequest($request);
        $user->update( $request->all() );

        $message = "Mitarbeiter wurde aktualisiert";

        return redirect()->action('UserController@edit', ['id' => $user->id ])->with( "flash_message", $message );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( User $user )
    {
        $user->delete();

        $message = "Mitarbeiter wurde gelöscht";
        return redirect()->action('UserController@index')->with( "flash_message", $message );
    }
}
