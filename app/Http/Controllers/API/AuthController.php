<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {

        if(User::where('email', $request->email)->exists()){
            return response()->json([
                'success' => false,
                'message' => "The email you provided, already exists in our system, use a different email or Login in with {$request->email}"
            ]);
        }

        //validator
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];

            return response()->json($response, 400);

        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('MyAPP')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'success' => true,
            'data' =>  $success,
            'message' => 'User register successfully 🎉'
        ];

        return response()->json($response, 200);

    }

    public function login(Request $request){

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = $request->user();

            $success['token'] = $user->createToken('MyAPP')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'Login successfully 🎉'
            ];

            return response()->json($response, 200);

        }

    }

}
