<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class AuthAdminMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		if (!env('ADMIN_IS_ENABLED'))
			return config('response.adminPanelDisable');
		
		$token = $request->bearerToken();
		if (!$token)
			return config('response.unauthorized');

		$decodedToken = AuthController::decodeToken($request->bearerToken());
		if (!$decodedToken || !$decodedToken->is_admin)
			return config('response.unauthorized');

		return $next($request);
	}
}
