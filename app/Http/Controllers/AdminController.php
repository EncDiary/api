<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
  public function auth(Request $request) {
    $this->validate($request, [
      'username' => config('validation.user.username'),
      'password' => config('validation.user.password')
    ]);

    $authResult = AuthController::authAdmin(
      $request->input('username'), $request->input('password')
    );

    if (!$authResult['status'])
      return $authResult['response'];

    return response(['token' => $authResult['token']]);
  }
}
