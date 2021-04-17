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

    protected function login(ServerRequestInterface $request){
dd($request->all());
        $credentials = array(
            'email' => $request->username,
            'password' => $request->password
        );
        if(Auth::attempt($credentials)){
            // dd($credentials);
            
            $data = Auth::user();
            $user_id = $data['id'];
            // $request->session()->put('state', $state = Str::random(40));

            $query = http_build_query([
                'client_id' => 'client-id',
                'redirect_uri' => 'http://localhost:3000/admin/dashboard',
                'response_type' => 'code',
                'scope' => '',
                'state' => '',
            ]);
            // $ac = new AccessTokenController;
            $controller = app()->make('Laravel\Passport\Http\Controllers\AccessTokenController');
            dd($controller->issueToken($request));
            // $reponce = Http::asForm()->get('http://localhost:8000/',[
            //     'grant_type' => 'password',
            //     'client_id' => $request->get('client-id'),
            //     'client_secret' => $request->get('client-secret'),
            //     // 'redirect_uri' => 'http://localhost:3000/admin/dashboard',
            // ]);
            // dd($reponce->json());
            return redirect('http://localhost:8000/oauth/authorize?'.$query);
            // $employee = Employee::where('user_id',$user_id)->with('company:id,name')->first();
            return response()->json([ 'status' => 200,'success'=> 1,'message'=>'logged in Sucessfully']);
        }else{
            return response()->json([ 'status' => 401, 'success'=> 0,'message'=>'loggin failed , Invalid Username or Password']);
        }
    }

}