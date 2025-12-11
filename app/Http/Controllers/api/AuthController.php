<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email'=>'email|required',
            'password'=>'min:8|required|string'
        ]);
        $user=User::where('email',$request->email)->first();
        
        if (!$user||!Hash::check($request->password,$user->password)) {
            return response()->json(["status"=>"failed"]);
        }
        $token=$user->createToken($request->email)->plainTextToken;
        $response=[
            "status"=>"succes","message"=>"user is logged in",
            "data"=>[
                "token"=>$token,"user"=>$user
            ]
        ];
         Auth::login($user);
        return response()->json($response,200);
    }

      public function register(Request $request){
        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|confirmed|min:8',
        ]);
        $user=User::create([
            'name'=>$request->name,"email"=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        $user->assignRole('passenger');
        $token=$user->createToken($request->email)->plainTextToken;
        Auth::login($user);
        $response=[
            "status"=>"succes","message"=>"user is created",
            "data"=>[
                "token"=>$token,"user"=>$user
            ]
        ];
        return response()->json($response,200);
      }

      public function logout(){
        auth()->user()->Tokens()->delete();
        return response()->json(["message"=>"successful"]);
      }
}
