<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminEnabledMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		if (!env('ADMIN_IS_ENABLED'))
			return config('response.adminPanelDisable');
		return $next($request);
	}
}
