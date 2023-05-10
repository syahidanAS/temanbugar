<?php

namespace App\Http\Middleware;

use App\Models\UserAccount;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperadminInterceptor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $extractedToken = explode(' ',$request->header('Authorization'))[1];
        $decoded = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));
        $response = UserAccount::where('uuid', $decoded->uuid)->first();
        
        if($response['role'] == "superadmin"){
            return $next($request);
        }
        
        return response()->json([
            'status'  => 'failed',
			'message' => 'sorry you do not have access to this content!'
		], 401);
    }
}
