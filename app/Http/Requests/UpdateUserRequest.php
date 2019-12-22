<?php


namespace App\Http\Requests;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules( Request $request )
    {
      $user = \Auth::user();

      $user_id = ($uid = $request->get("user_id") ) ? $uid : $user->id;

      $user = User::findOrFail($user_id);

      // $email_rule = 'unique:users,id,'.$user_id;
      $email_rule = 'unique:users,id,'.$user_id;

      if ( !$user->email or $user->email != $request->get("email") )
      {
        $email_rule = 'sometimes|nullable|unique:users';
      }

      // dd($email_rule);

      // $email_rule = 'unique:users,'.$user->id;

      return
      [
        // 'email'                =>  'email|unique:users,id,'.$user->id,
        // 'email'                 => 'email|unique:users,,',
        // 'email'                 => ,
        'email'                 => $email_rule,
        'first_name'            => 'required',
        'last_name'             => 'required',
        'password'              => 'sometimes|nullable|min:6|required_with:password_confirmation|same:password_confirmation',
        'password_confirmation' => 'sometimes|nullable|min:6'
      ];
    }




}
