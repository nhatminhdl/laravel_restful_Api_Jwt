<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    
    public function login(Request $request){
        $validator = Validator::make($request -> all(),[
            'email' => 'required|email',
            'password' => 'required|string|min:6'
            
        ]);

        if ($validator -> fails()) {
            return response() -> json($validator -> errors(), 400 );
        }

        $token_validity = 24*60;
       Auth::factory() -> setTTL($token_validity);


        if (!$token = $this -> guard()-> attempt($validator -> validated())) {
            return response() -> json(['error' => 'Unauthorized'],401);
        }

        return $this ->respondWithToken($token);
        
    }

    public function register(Request $request){
        $validator = Validator::make($request -> all(),[
           'name' => 'required|string|between:2,100',
           'email' =>'required|email|unique:users',
           'password' => 'required|confirmed|min:6'
            
        ]);

        if($validator -> fails()){
            return response() -> json([
                $validator -> errors()
            ], 422);
            
        }

        $user = User::create(array_merge(
            $validator -> validated(),
            ['password' => bcrypt($request -> password)]
        ));

        return response() -> json(['message' => 'User created successfully','user' => $user]);
        
    }

    public function logout(Request $request){
        $this -> guard() -> logout();
        return response() -> json(['message' => 'User logged out successfully!']);
    }

    public function profile(Request $request){
        return response() -> json($this -> guard() -> user());
    }

    public function refresh(Request $request){
        return $this -> respondWithToken($this -> guard()-> refresh());
        
    }

    protected function guard(){
        return Auth::guard();
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this -> guard() ->factory()->getTTL() * 60
        ]);
    }
}