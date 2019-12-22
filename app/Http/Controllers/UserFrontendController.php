<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;



class UserFrontendController extends Controller
{
    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = \Auth::user();
        $data = $this->userRepository->getFormData($user);

        return view("users.account", $data );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( UpdateUserRequest $request )
    {
        $user = \Auth::user();

        $user->first_name        = $request->get("first_name", $user->first_name );
        $user->last_name         = $request->get("last_name", $user->last_name );
        $user->birthdate         = $request->get("birthdate");
        $user->email             = $request->get("email", $user->email );
        $user->mobile            = $request->get("mobile", $user->mobile );
        $user->phone             = $request->get("phone" );
        $user->street            = $request->get("street" );
        $user->zipcode           = $request->get("zipcode" );
        $user->city              = $request->get("city" );

        if ( $pw = $request->get("password" ) )
        {
            $user->password =  bcrypt($pw);
        }

        $user->save();

        $message = "Daten wurde aktualisiert";

        return redirect()->action('UserFrontendController@edit')->with( "flash_message", $message );
    }


}
