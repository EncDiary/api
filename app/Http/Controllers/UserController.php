<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends BaseController
{
  public function register(Request $request) {
    $this->validate($request, [
      'username' => config('validation.user.username'),
      'public_key' => config('validation.user.public_key'),
      'salt' => config('validation.note.salt')
    ]);

    $isPublicKeyValid = AuthController::checkIsPubKeyValid(
      $request->input('public_key')
    );
    if (!$isPublicKeyValid)
      return config('response.publicKeyIsInvalid');

    $username = strtolower($request->input('username'));
    $user = User::where('username', $username)->first();
    if ($user)
      return config('response.userAlreadyExist');

    $user = new User;
    $user->username = $username;
    $user->public_key = $request->input('public_key');
    $user->salt = $request->input('salt');
    $user->save();
    
    return response(null, 201);
  }


  public function requestMessage(Request $request) {
    $this->validate($request, [
      'username' => config('validation.user.username'),
      'is_admin' => config('validation.user.is_admin')
    ]);

    $username = strtolower($request->input('username'));
    $user = User::where('username', $username)->first();
    if (!$user)
      return config('response.wrongLoginData');
    if (!$user->is_admin && $request->input('is_admin'))
      return config('response.forbidden');

    $ciphertext = AuthController::createMessage($user);

    return response(['message' => $ciphertext]);
  }
  

  public function auth(Request $request) {
    $this->validate($request, [
      'username' => config('validation.user.username'),
      'signature' => config('validation.user.signature'),
      'is_admin' => config('validation.user.is_admin')
    ]);

    $user = User::where('username', $request->input('username'))->first();
    if (!$user)
      return config('response.wrongLoginData');
    if (!$user->is_admin && (bool)$request->input('is_admin'))
      return config('response.forbidden');

    $authResult = AuthController::auth(
      $user,
      $request->input('signature'),
      (bool)$request->input('is_admin')
    );
    if (!$authResult['status'])
      return $authResult['response'];

    return response([
      'token' => $authResult['token'],
      'salt' => $user->salt
    ]);
  }

  
  public function deleteAccount(Request $request) {
    $user = $request->input('user');
    if ($user->username === 'demo')
      return config('response.thisIsDemo');

    $user->delete();
    return response(null, 204);
  }


  public function backup(Request $request) {
    $user = $request->input('user');

    $notes = $user->notes()
      ->select('ciphertext', 'datetime', 'iv', 'salt')
      ->orderByDesc('datetime')
      ->get();

    return response([
      'username' => $user->username,
      'notes' => $notes
    ]);
  }
}
