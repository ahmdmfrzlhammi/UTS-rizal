<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Auth;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function Register(RegisterRequest $request){
        $response = $this->default_response;

        try {
            $data = $request->validated();

            DB::beginTransaction();

            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            DB::commit();

            $response['Success'] = true;
            $response['data'] = $user;
            $response['message'] = 'Register Success';
        }catch(\Exception $e){
            DB::rollBack();

            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    public function login(LoginRequest $request){
        $response = $this->default_response;

        try {
                $data = $request->validated();

                if(!Auth::attempt($data)){
                    throw new \Exception('email and does not match');

                }

                $user=Auth::user();
                $token= $user->createToken('auth_token')->plainTextToken;

                $response['succes'] = true;
                $response['message'] = "Login Success";
                $response["data"] = [
                    'user'=> $user,
                    'access_token'=> $token,
                    'token_type'=>'Bearer',
                ];
        }catch(\Exception $e){
                $response['message']= $e->getMessage();
        }
        return response()->json($response);

    }

    public function logout(){
        $response = $this->default_response;
        try {
            $user=Auth::user();
            $user->tokens()->delete();
            $response['success'] = true;
            $response['message'] = "logout Success";
    }catch(\Exception $e){
        $response["message"]= $e->getMessage();
    }
    return response()->json($response);
}
}
