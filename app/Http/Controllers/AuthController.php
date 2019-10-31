<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Guest;
use Auth;
use Tymon\JWTAuth\Contracts\Providers\Auth as JWT;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt) {
        $this->jwt = $jwt;
    }
    public function login(Request $request) {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required|min:3'
        ]);
        //we pass these in attempt so JWT can create token
        $credentials = $request->only( 'email','password' );
        try {
            // pokusaj da napravis token,u koliko ne catach exception
            if (!$token = $this->jwt->attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid Credentials'
                ],401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' =>'Could not create token!'
            ],500);
        }
        $user = $this->jwt->user();

        return response()->json([
            'token' => $token,
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => $user,
            'roles' => $this->jwt->user()->getAllPermissions()
        ]);
    }
    public function register(Request $request) 
    {
        $auth_user = $this->jwt->user();
        $auth_user_role = $auth_user->getRoleNames();

        
        $this->validate($request,[
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users',
        ]);
        
        $image = $this->takePhoto($request->image);

        if($auth_user_role != 'Administrator')
            $password = $request->name.$request->surname;
        else {
            $password = $request->input('passoword'); 
        }

        if($auth_user_role != 'Administrator') {
            $typeId = 1;
        } else {
            $typeId = ($request->input('type'));
        }

        $user = new User ([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'personal_id' => $request->input('personal_id'),
            'jmbg' => $request->input('jmbg'),
            'image' => $image[0],
            'image_path' => $image[1],
            'sub_folder' => $image[2],
            'password' => bcrypt($password),
            'type_id' => $typeId
        ]);



        if($auth_user_role != 'Administrator') {
            $user->assignRole('Visitor');
        } else {
            $user->assignRole($request->roleName);
        }

        $user->save();

        $user->type;
        return response()->json([
            'data' => $user,
            'message' => 'User created'
        ],201);
    }
    public function logout()
    {
        \JWTAuth::invalidate();
            return response()->json([
                'statusCode' => 200,
                'statusMessage' => 'success',
                'message' => 'User Logged Out',
                ], 200);
    }

    public function refresh()
    {
        $token      =  \JWTAuth::refresh();
        $user       =  $this->jwt->user();
        return response()->json([
            'status_code'       => 200,
            'token'             => $token,
            'user'              => $user
        ]);
    }

    public function me()
    {
        $user = auth()->user();
        $user->getPermissionsViaRoles();
        return $user;
    }

    public function guardGuard()
    {
        return \Auth::guard('api');
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
    public function guard()
    {
        return Auth::guard('api');
    }
}

