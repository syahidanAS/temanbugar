<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\UserAccount;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MealController extends Controller
{
    public function tokenExtractor($token){
        $extractedToken = explode(' ',$token)[1];
        $decoded = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));
        $user = UserAccount::where('uuid', $decoded->uuid)->first();

        return $user['uuid'];
    }
    public function index(){
        $qryresult = Meal::get();
        
        if($qryresult){
            return response()->json([
                'status'  => 'success',
                'message' => count($qryresult).' Data retrieved',
                'data' => $qryresult
            ], 200);
        }
        return response()->json([
            'status'  => 'failed',
			'message' => 'Something went wrong, please try again!'
		], 500);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'meal_name'     => 'required',
            'calorie'       => 'required',
            'fat'           => 'required',
            'carbohydrate'  => 'required',
            'proteins'      => 'required'
        ],
        [
            'meal_name.required'    => 'Meal name is reuired!',
            'calorie.required'      => 'Calorie is reuired!',
            'fat.required'          => 'Fat is reuired!',
            'carbohydrate.required' => 'Carbohydrate is reuired!',
            'proteins.required'     => 'Proteins is reuired!',
        ]
        );
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->getMessageBag()->toArray()
                    ], 400);
        }


        $payload = [
            "uuid"          => Str::uuid()->toString(),
            "meal_name"     => $request->meal_name,
            "calorie"       => $request->calorie,
            "fat"           => $request->fat,
            "carbohydrate"  => $request->carbohydrate,
            "proteins"      => $request->proteins,
            "created_by"    => $this->tokenExtractor($request->header('Authorization'))
        ];

        $qryresult = Meal::create($payload);

        if($qryresult){
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully created!'
            ], 201);
        }
        return response()->json([
            'status' => 'failed',
            'error' => 'Something went wrong, please try again!'
        ], 500);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'uuid'          => 'required',
            'meal_name'     => 'required',
            'calorie'       => 'required',
            'fat'           => 'required',
            'carbohydrate'  => 'required',
            'proteins'      => 'required'
        ],
        [
            'uuid.required'         => 'There is no item to updated!',
            'meal_name.required'    => 'Meal name is reuired!',
            'calorie.required'      => 'Calorie is reuired!',
            'fat.required'          => 'Fat is reuired!',
            'carbohydrate.required' => 'Carbohydrate is reuired!',
            'proteins.required'     => 'Proteins is reuired!',
        ]
        );
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->getMessageBag()->toArray()
                    ], 400);
        }

        $payload = [
            "meal_name"     => $request->meal_name,
            "calorie"       => $request->calorie,
            "fat"           => $request->fat,
            "carbohydrate"  => $request->carbohydrate,
            "proteins"      => $request->proteins,
        ];

        $qryresult = Meal::where('uuid', $request->uuid)->update($payload);

        if($qryresult){
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully updated!'
            ], 201);
        }
        return response()->json([
            'status' => 'failed',
            'error' => 'Something went wrong, please try again!'
        ], 500);
    }

    public function destroy(Request $request){
        if (!$request->uuid) {
            return response()->json([
                        'error' => 'There is no item to delete!'
                    ], 400);
        }

        $qryresult = Meal::where('uuid', $request->uuid)->delete();

        if($qryresult){
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully deleted'
            ], 200);
        }
        return response()->json([
            'error' => 'Something went wrong, please try again!'
        ], 500);
    }

    public function show($uuid){
        if (!$uuid) {
            return response()->json([
                        'error' => 'There is no item to show!'
                    ], 400);
        }
        
        $qryresult = Meal::where('uuid', $uuid)->first();

        if($qryresult){
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully getting data',
                'data'  => $qryresult
            ], 200);
        }
        return response()->json([
            'error' => 'Something went wrong!'
        ], 500);
}
}