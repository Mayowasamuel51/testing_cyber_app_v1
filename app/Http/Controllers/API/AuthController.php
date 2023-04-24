<?php

namespace App\Http\Controllers\API;

use Auth;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller{
    public function redirectToAuth(): JsonResponse{
        return response()->json([
            'url' => Socialite::driver('github')->stateless()->redirect()->getTargetUrl(),
        ]);
       

    }

    public function handleAuthCallback(): JsonResponse{
      
        
    
        try {
            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = Socialite::driver('github')->stateless()->user();
        } catch (ClientException $e) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        /** @var User $user */
        $user = User::query()
            ->firstOrCreate(
                [
                    'email' => $socialiteUser->getEmail(),
                ],
                [
                    'email_verified_at' => now(),
                    'name' => $socialiteUser->getName(),
                    'github_id' => $socialiteUser->getId(),
                    'avatar' => $socialiteUser->getAvatar(),
                ]
            );

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('github-token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }







    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return  response()->json([
            'status' => 200,
            'message' => 'u have logout '
        ]);
    }

    public function check(){
        return response()->json([
            'yeah'=>'seen too soo'
        ]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_error' => $validator->messages(),
            ]);
        } else {
            $user = User::where('email', $request->email)->first();

            if (!$user ||  !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message_back' => 'invaild email or password'
                ]);
            } else {
                $token_user =  $user->createToken('mytoken')->plainTextToken;
                return response()->json([
                   'status'=>200,
                   'token'=>$token_user
                ]);
              
                
            }
        }
    }
    public function Register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed'
        ]);
    
    if ($validator->fails()) {
        return response()->json([
            'validation_error' => $validator->messages(),
        ]);
    }  else {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);
        $token_user =  $user->createToken($user->name . '_Token',)->plainTextToken;
        return response()->json([
            'status' => 200,
            'token_name' => $user->name,
            'token' => $token_user,
            'message' => 'Registerd '
        ]);
    }
    }
}
