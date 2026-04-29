<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator=Validator::make($request->all()
        ,
        [
            "name"=>"required|min:3|max:256|string",
            "email"=>"required|unique:users|string|max:256",
            "password"=>"required|string|min:6|confirmed",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $user=User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request->password),
        ]);

        $token=auth('api')->login($user);

        return response()->json([
        'message' => 'Usuário registrado com sucesso!',
        'user' => $user,
        'access_token' => $token,
    ], 201);

    }
    public function login(Request $request){
        $cred=$request->only(['email','password']);
        if(! $token=auth('api')->attempt($cred)){
            return response()->json(['error'=>'Não autorizado'],401);
        }
        return $this->respondWithToken($token);
    }

    public function me(){
        return response()->json(auth('api')->user());
    }
    public function logout(){
        auth('api')->logout();
        return response()->json(['message'=>'logout feito com sucesso!']);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
