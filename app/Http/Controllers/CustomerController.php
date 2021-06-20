<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;

class CustomerController extends Controller
{
    public function genrateOtp(Request $request){
        $otp = '1234';
        $mobile = $request->mobile;
        if(isset($mobile)){
            $user = User::where('email',$mobile)->first();
            if(isset($user)){
                $user->password = bcrypt($otp);
            }else{
                User::create([
                    'name' => 'NA',
                    'email' => $mobile,
                    'password' =>  bcrypt($otp),
                    'type' => 'GUEST'
                ]);
            }
            return response()->json(['status' => 'success', 'message' => 'OTP genrated'],200);
        }
        return response()->json(['status' => 'failed', 'message' => 'Something went wrong'],500);
    }

    public function verifyOtp(Request $request){
        $mobile = $request->mobile;
        $otp = $request->otp;
        $credentials = array(
            'email' => $request->mobile . '@civilshopee.customer',
            'password' =>  $request->otp,
            'type' => 'GUEST'
        );
        if(Auth::attempt($credentials)){
            return response()->json(['status' => 'success', 'message' => 'OTP verified','access_token' => Auth::user()->createToken('Auth Token')->accessToken ,'user' => Auth::user() ],200);
        }
        if(isset($mobile) && isset($otp)){
            $otp = bcrypt($otp);
            $user = User::where([
                'email' => $mobile ,
                'password' =>  $otp,
            ])->first();
            if($user){
            }
            return response()->json(['status' => 'failed', 'message' => 'OTP Not verified'],403);
        }

    }
}