<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		$headers = [
			'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
			'Access-Control-Allow-Credentials' => 'true',
			'Access-Control-Max-Age'           => '86400',
			'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
		];

		if (isset($_SERVER['HTTP_ORIGIN'])) {
			$http_origin = $_SERVER['HTTP_ORIGIN'];
			$allowed_origins = explode(', ', env('FRONTEND_APP_URL'));
			if (in_array($http_origin, $allowed_origins)) {
				$headers['Access-Control-Allow-Origin'] = $http_origin;
			}
		}

		if ($request->isMethod('OPTIONS')) {
			return response()->json('OK', 200, $headers);
		}

		$response = $next($request);
		foreach($headers as $key => $value)
		{
			$response->header($key, $value);
		}

		return $response;
	}
}
