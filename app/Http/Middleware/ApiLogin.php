<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
class ApiLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $secret = DB::table('oauth_clients')
            ->where('id',2)
            ->pluck('secret')
            ->first();
        $credentials = array(
            'email' => $request->username,
            'password' => $request->password
        );
        if(Auth::attempt($credentials)){
            $request->merge([
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' =>  $secret,
            ]);
            $next_respoce = $next($request);
            $respocen_content = json_decode($next_respoce->content());
            $respocen_content->user = Auth::user();
            return response()->json($respocen_content,200);
            // dd($respocen_content);
            return $next($request);
        }
        return response()->json(['massage' => 'failled to log in'],401);
    }
}
