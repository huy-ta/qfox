<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(),
            ['username' => 'unique:users|required|max:15|min:6',
                'name' => 'required|max:30|min:4',
                'password' => 'required'
            ],
            [
                'unique' => 'Username already exists.'
            ]
        );

        if ($validator->fails()) {
            $details = [];
            foreach ($validator->errors()->toArray() as $field => $value) {
                $details[$field] = $value[0];
            }

            return response()->json([
                'error' => [
                    "code" => Response::HTTP_BAD_REQUEST,
                    "message" => 'Registration failed. Please check your registration information.',
                    "details" => (object)$details
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->profile_picture_url = "";
        $user->language = "en";
        $user->save();

        return response()->noContent(Response::HTTP_CREATED);
    }
}
