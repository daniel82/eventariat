<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Requests; // use the namespace of the request

use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUserRequest;
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

        $request = $this->userRepository->sanitizeRequest($request, $method="store");
        $user = User::create( $request->all() );
        $this->userRepository->syncTags($request, $user);

        event( new \App\Events\UserCreatedEvent($user) );

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
    public function update( UpdateUserRequest $request, User $user )
    {
        $request = $this->userRepository->sanitizeRequest($request);

        $user->update( $request->all() );
        $this->userRepository->syncTags($request, $user);

        $message = "Mitarbeiter wurde aktualisiert";

        if ( $request->get("new_password") )
        {
            event( new \App\Events\UserCreatedEvent($user) );
            $message .= " und neues Passwort versendet (".$user->email.")";
        }


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


    public function returnAs( Request $request )
    {
        if( isset($_COOKIE["return_to_admin"]) && is_numeric($_COOKIE["return_to_admin"]) )
        {
            $user_id = (int)$_COOKIE["return_to_admin"];
            if ( $user = User::find($user_id) )
            {
                setcookie("return_to_admin", $user->id, time()-(1*60*60*24*30), "/");
                \Auth::loginUsingId($user->id, true);
            }
        }

        return redirect( "/dienstplan" );
    }


    public function loginAs( Request $request )
    {
        $current_user = \Auth::user();
        $user_id = $request->get("user_id");

        $login_user = false;
        if ( is_numeric($user_id) && $current_user->isAdmin() )
        {
          $user = User::find($user_id);
          $login_user = true;
        }

        if ( $login_user )
        {
            setcookie("return_to_admin", $current_user->id, time()+(1*60*60), "/");
            \Auth::loginUsingId($user_id, true);
            return redirect( "/dienstplan" );
        }
        else
        {
            $message = sprintf("Benutzer nicht vorhanden oder keine Adminrechte" );
            return redirect()->action('UserController@index')->with( "flash_message", $message );
        }
      }

}
