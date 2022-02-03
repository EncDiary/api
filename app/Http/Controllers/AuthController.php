<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Laravel\Lumen\Routing\Controller as BaseController;
use Firebase\JWT\ExpiredException;


class AuthController extends BaseController
{
  private static function createToken($lifetime, $is_admin, $payload = []) {
    $payload['exp'] = time() + $lifetime;
    $payload['is_admin'] = $is_admin;

    $token = JWT::encode($payload, env('JWT_PRIVATE_KEY'), 'RS256');
    return $token;
  }


  public static function decodeToken($token) {
    try {
      $decodedToken = JWT::decode($token, env('JWT_PUBLIC_KEY'), array('RS256'));
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

  
  public static function checkIsPubKeyValid($public_key_text) {
    $public_key = openssl_get_publickey($public_key_text);
    return (bool) $public_key;
  }

  
  private static function clearMessage($user) {
    $user->message = null;
    $user->message_exp = null;
    $user->save();
  }


  public static function auth($user, $signature, $is_admin) {
    if (is_null($user->message_exp) || $user->message_exp < time()) {
      self::clearMessage($user);
      return [
        'status' => false,
        'response' => config('response.messageExpired')
      ];
    }
    $verification_result = openssl_verify(
      $user->message,
      base64_decode($signature),
      openssl_get_publickey($user->public_key),
      OPENSSL_ALGO_SHA512
    );

    if ($verification_result !== 1) {
      return [
        'status' => false,
        'response' => config('response.wrongLoginData')
      ];
    }

    self::clearMessage($user);

    $token = self::createToken(15 * 60, $is_admin, ['user_id' => $user->id]);
    return ['status' => true, 'token' => $token];
  }
}