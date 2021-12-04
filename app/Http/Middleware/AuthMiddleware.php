<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Models\User;

class AuthMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		$bearerToken = $request->bearerToken();
		if (!$bearerToken) return config('response.unauthorized');

		$token = AuthController::checkToken($request->bearerToken());
		if (!$token) return config('response.unauthorized');

		$user = User::find($token->user_id);
		if (!$user) return config('response.unauthorized');

		$request->request->add(['user' => $user]);

		return $next($request);
	}
}
