<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Cookie;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Exception;

class AuthController extends Controller
{
    public function register(Request $request){
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);
            return $user;
        }catch (Exception $e){
            return $e;
        }
    }

    public function login(Request $request){
       if(!Auth::attempt($request->only('email', 'password'))){
           return response(['message' => 'Invalid emil or password'], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
       }

       $user = Auth::user();

       $token = $user->createToken('token')->plainTextToken;

       cookie('jwt', $token, 60 * 24);

       return response(['message' => $token]);
    }

    public function users(){
        return User::get();
    }
}
