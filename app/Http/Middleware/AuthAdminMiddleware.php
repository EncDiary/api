<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class AuthAdminMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		$token = $request->bearerToken();
		if (!$token)
			return config('response.unauthorized');

		$decodedToken = AuthController::decodeToken($request->bearerToken(), 'admin');
		if (!$decodedToken)
			return config('response.unauthorized');

		return $next($request);
	}
}
