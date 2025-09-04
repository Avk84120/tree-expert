<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function register(Request $request){
        $v = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required|min:6|confirmed',        
        ]);
        if ($v->fails()) return response()->json(['errors'=>$v->errors()],422);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'password'=>Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token],201);
    }

    public function login(Request $request){
        $v = Validator::make($request->all(), [
            'email' => 'nullable|email',
            'mobile' => 'nullable',
            'password' => 'required_without:otp',
            'otp' => 'nullable'
        ]);
        if ($v->fails()) return response()->json(['errors'=>$v->errors()],422);

        // Password login
        if ($request->password && ($request->email || $request->mobile)){
            $user = User::where('email',$request->email)->orWhere('mobile',$request->mobile)->first();
            if (!$user || !Hash::check($request->password,$user->password)){
                return response()->json(['message'=>'Invalid credentials'],401);
            }
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['user'=>$user,'token'=>$token]);
        }

        // OTP login
        if ($request->otp && $request->mobile){
            $cacheKey = 'otp_'.$request->mobile;
            if (Cache::get($cacheKey) != $request->otp) return response()->json(['message'=>'Invalid OTP'],401);

            $user = User::firstOrCreate(['mobile'=>$request->mobile],['name'=>'User '.$request->mobile]);
            Cache::forget($cacheKey);
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['user'=>$user,'token'=>$token]);
        }

        return response()->json(['message'=>'Bad request'],400);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }

    public function sendOtp(Request $request){
        $request->validate(['mobile'=>'required']);
        $code = rand(100000,999999);
        Cache::put('otp_'.$request->mobile,(string)$code,now()->addMinutes(10));
        // TODO: integrate SMS service
        return response()->json(['message'=>'OTP sent','otp'=>$code]); // remove otp in prod
    }
}
