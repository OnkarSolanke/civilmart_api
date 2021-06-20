<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        $ip = $request->getClientIp();
        if(isset($user)){
            // $loggedIndetails = UserLogs::where('user_id',$user->id)->where('ip_address',$ip)->latest()->first();
            
            if(isset($loggedIndetails) && $loggedIndetails->count() > 0){
                $loggedIndetails->update(['logout_time' => date('Y-m-d H:i:s') , 'ip_address' => isset($ip) ? $ip : null ]);
            } 
        }
        // $this->performLogout($request);
        // return redirect()->route('login');
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('login');
    }

    protected function login(Request $request){
        $credentials = array(
            'email' => $request->username,
            'password' => $request->password
        );
        if(Auth::attempt($credentials)){
            // dd($credentials);
            
            $data = Auth::user();
            $user_id = $data['id'];

            $query = http_build_query([
                'client_id' => 'client-id',
                'redirect_uri' => 'http://localhost:3000/admin/dashboard',
                'response_type' => 'code',
                'scope' => '',
                'state' => '',
            ]);
            $controller = app()->make('Laravel\Passport\Http\Controllers\AccessTokenController');
            return response()->json([ 'status' => 200,'success'=> 1,'message'=>'logged in Sucessfully','access_token' => Auth::user()->createToken('Auth Token')->accessToken ,'user' => Auth::user() ]);
        }else{
            return response()->json([ 'status' => 401, 'success'=> 0,'message'=>'loggin failed , Invalid Username or Password']);
        }
    }
}