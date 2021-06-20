<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorAuth
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
        $user = Auth::user();
        if($user->type == "ADMIN" || $user->type == "VENDOR"){
            return $next($request);
        }
        return response()->json([
            'status' => '405',
            'type' => $user->type,
            'error' => 'Unauthorized'
        ],403);
    }
}
