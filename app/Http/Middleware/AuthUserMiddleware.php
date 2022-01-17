<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Models\User;

class AuthUserMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		$token = $request->bearerToken();
		if (!$token)
			return config('response.unauthorized');

		$decodedToken = AuthController::decodeToken($token);
		if (!$decodedToken)
			return config('response.unauthorized');

		$user = User::find($decodedToken->user_id);
		if (!$user)
			return config('response.unauthorized');

		$request->request->add(['user' => $user]);

		return $next($request);
	}
}
