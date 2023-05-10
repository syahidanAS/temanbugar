<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserProfile;
use Faker\Provider\UserAgent;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function __construct()
    {

    }
    public function signup(Request $request){
        $validator = Validator::make($request->all(), [
            'username'  => 'required|max:255|unique:user_account',
            'email'     => 'required|max:255|unique:user_account',
            'phone'     => 'required|max:255',
            'password'  => 'required|max:255',
            'fullname'  => 'required|max:255'
        ],
        [
            'username.required' => 'Username is reuired!',
            'username.max'      => 'Username is too long!',
            'username.unique'   => 'The '.$request->username.' username cannot be used!',
            'email.required'    => 'Email is reuired!',
            'email.max'         => 'Email is too long!',
            'email.unique'      => $request->email.' has been taken!',
            'phone.required'    => 'Phone number is reuired!',
            'phone.max'         => 'Phone number is too long!',
            'password.required' => 'Password is reuired!',
            'password.max'      => 'Password is too long!',
            'fullname.required' => 'Full name is reuired!',
            'password.max'      => 'Full name is too long!',
        ]
        );
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->getMessageBag()->toArray()
                    ], 400);
        }

        $useraccount = [
            'uuid'      => Str::uuid()->toString(),
            'username'  => $request->username,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => bcrypt($request->password),
            'is_active' => true,
            'country'   => "",
            'role'      => "user"
        ];

        $userprofile = [
            'uuid'      =>  Str::uuid()->toString(),
            'user_account_uuid' =>  $useraccount['uuid'],
            'user_profile_name' => $request->fullname,
            'profile_image'     => $request->profile_image
        ];

        DB::beginTransaction();
        try{
            $useraccountresult = UserAccount::create($useraccount);
            $userprofileresult = UserProfile::create($userprofile);
            DB::commit();
            $jwtPayload = [
                'iat' => 1356999524,
                'nbf' => 1357000000,
                'uuid' => $useraccount['uuid'],
                'role' => $useraccount['role']
            ];
            $jwt = JWT::encode($jwtPayload, env('JWT_SECRET'), 'HS256');

            return response()->json([
                'status'  => "success",
                "message" => "User sucessfully created",
                "data"    => [
                    "username" => $useraccount['username'],
                    "email"    => $useraccount['email'],
                    "token"    => $jwt
                ]
            ], 201);
    
        }catch(\Exception $e){
            return response()->json([
                'error' => "Something went wrong, please try again!",
                'message' => $e
            ], 500);
        }

    }

    public function signin(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required',
        ],
        [
            'email.required'    => 'Email is reuired!',
            'password.required' => 'Password is reuired!',
        ]
        );
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->getMessageBag()->toArray()
                    ], 400);
        }

		$payload = [
			'email' => $request->email,
			'password' => $request->password
		];
		$validated = Auth::attempt($payload);

		if($validated){
            $response = UserAccount::where('email', $payload['email'])->first();
			return response()->json([
                'status' => 'success',
				'message' => 'Token successfully generated!',
                'data' => [
                    "user" => $response,
                    'access_token' => $this->tokenGenerator($response['uuid']),
                ]
			], 200)->cookie('access_token', $this->tokenGenerator($response['uuid']), 72);
		}
		return response()->json([
            'status'  => 'failed',
			'message' => 'Account not found!'
		], 401);
    }

    public function me(Request $request){
        $extractedToken = explode(' ',$request->header('Authorization'))[1];
        $decoded = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));
        $response = UserAccount::where('user_account.uuid', $decoded->uuid)
                    ->join('user_profile', 'user_account.uuid','user_profile.user_account_uuid')->first();
        if($response){
            return response()->json([
                'status' => 'success',
                'message' => 'Authenticated',
                'data' => $response
            ], 200);
        }
    }

	public function tokenGenerator($uuid){
        $role = UserAccount::where('uuid', $uuid)->first();
		$payload = [
			'iat' => 1356999524,
			'nbf' => 1357000000,
			'uuid' => $uuid,
            'role' => $role['role']
		];
		$jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
		return $jwt;
	}
}
