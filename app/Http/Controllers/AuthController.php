<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       $formFields = $request->validate([
           'name' => 'required | string',
           'email' => 'required | string | unique:users,email',
           'password' => 'required | string | confirmed',
       ]);

       $user = User::create([
           'name' => $formFields['name'],
           'email' => $formFields['email'],
           'password' => bcrypt($formFields['password']),
       ]);

       $token = $user->createToken('myapptoken')->plainTextToken;

       $response = [
           'user' => $user,
           'token' => $token
       ];

       return $response;

    }
    public function login(Request $request)
    {
       $formFields = $request->validate([
           'email' => 'required | string ',
           'password' => 'required | string ',
       ]);

        // check for email
       $user = User::where('email', $formFields['email'])->first();

        // check password 
        if(!$user || !Hash::check($formFields['password'], $user->password)){
            return response(['message' => 'Incorrect credentials'], 401);
        }


        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $response;

        
       
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return ['message'=>'logged out'];
    }




}
