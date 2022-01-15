<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Laravel\Lumen\Routing\Controller as BaseController;
use Firebase\JWT\ExpiredException;


class AuthController extends BaseController
{
  private static function createToken($lifetime, $role, $payload = []) {
    $payload['exp'] = time() + $lifetime;
    $payload['role'] = $role;

    $token = JWT::encode($payload, env('JWT_PRIVATE_KEY'), 'RS256');
    return $token;
  }


  public static function decodeToken($token, $role) {
    try {
      $decodedToken = JWT::decode($token, env('JWT_PUBLIC_KEY'), array('RS256'));
      if ($decodedToken->role !== $role)
        return false;
      return $decodedToken;
    } catch (UnexpectedValueException | ExpiredException  $e) {
      return false;
    }
  }


  public static function createMessage($user) {
    $message = base64_encode(openssl_random_pseudo_bytes(128));

    $user->message = $message;
    $user->message_exp = time() + 60;
    $user->save();

    return $message;
  }

  
  public static function checkIsPublicKeyValid($public_key_text) {
    $public_key = openssl_get_publickey($public_key_text);
    return (bool) $public_key;
  }


  public static function authUser($user, $signature) {
    if ($user->message_exp < time()) {
      return [
        'status' => false,
        'response' => config('response.messageExpired')
      ];
    }
    $verification_result = openssl_verify(
      $user->message,
      base64_decode($signature),
      openssl_get_publickey($user->public_key),
      OPENSSL_ALGO_SHA512);

    if ($verification_result !== 1) {
      return [
        'status' => false,
        'response' => config('response.wrongLoginData')
      ];
    }

    $user->message = null;
    $user->message_exp = null;
    $user->save();

    $token = self::createToken(15 * 60, 'user', ['user_id' => $user->id]);
    return ['status' => true, 'token' => $token];
  }


  public static function authAdmin($username, $password) {
    if (strcasecmp($username, env('ADMIN_USERNAME')) !== 0
        || !password_verify($password, env('ADMIN_PASSWORD_HASH'))) {
      return [
        'status' => false,
        'response' => config('response.wrongLoginData')
      ];
    }
    $token = self::createToken(15 * 60, 'admin');
    return ['status' => true, 'token' => $token];
  }
}