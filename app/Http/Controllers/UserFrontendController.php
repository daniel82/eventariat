<?php

namespace App\Http\Controllers;

use \App\Repositories\UserRepository;
use Illuminate\Http\Request;


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
    public function update(Request $request, $id)
    {
        //
    }


}
