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
      'public_key' => config('validation.user.public_key')
    ]);

    $isPublicKeyValid = AuthController::checkIsPublicKeyValid($request->input('public_key'));
    if (!$isPublicKeyValid) return config('response.publicKeyIsInvalid');

    $username = strtolower($request->input('username'));
    $user = User::where('username', $username)->first();
    if ($user) return config('response.userAlreadyExist');

    $user = new User;
    $user->username = $username;
    $user->public_key = $request->input('public_key');
    $user->save();
    
    return response(null, 201);
  }


  public function requestOneTimeKey(Request $request) {
    $this->validate($request, [
      'username' => config('validation.user.username')
    ]);

    $username = strtolower($request->input('username'));
    $user = User::where('username', $username)->first();
    if (!$user) return config('response.wrongLoginData');

    $ciphertext = AuthController::createOneTimeKey($user);

    return response(['ciphertext' => $ciphertext]);
  }
  

  public function auth(Request $request) {
    $this->validate($request, [
      'username' => config('validation.user.username'),
      'plaintext' => config('validation.user.plaintext')
    ]);

    $user = User::where('username', $request->input('username'))->first();
    if (!$user) return config('response.wrongLoginData');

    $result = AuthController::checkOneTimeKey($user, $request->input('plaintext'));
    if (!$result['status']) return $result['response'];

    $token = AuthController::createToken($user->id);
    return response(['token' => $token]);
  }

  
  public function deleteAccount(Request $request) {
    $user = $request->input('user');
    if ($user->username === 'demo') return config('response.thisIsDemo');

    $notes = $user->notes()->delete();
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
