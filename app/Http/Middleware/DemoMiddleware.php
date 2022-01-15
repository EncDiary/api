<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Models\User;

class DemoMiddleware
{
	public function handle(Request $request, Closure $next)
	{
    $user = User::where('username', 'demo')->first();
    if (!$user)
			return config('demoUserNotFound');

		$request->request->add(['user' => $user]);

		return $next($request);
	}
}
