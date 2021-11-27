<?php
namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Laravel\Lumen\Routing\Controller as BaseController;


class AuthController extends BaseController
{
  public static function createToken($user_id)
  {
    $payload = array(
      'exp' => time() + 180 * 60 * 365,
      'user_id' => $user_id,
    );

    $token = JWT::encode($payload, env('JWT_PRIVATE_KEY'), 'RS256');
    return $token;
  }

  public static function checkToken($token)
  {
    try {
      $decoded = JWT::decode($token, env('JWT_PUBLIC_KEY'), array('RS256'));
      return $decoded;
    } catch (UnexpectedValueException $e) {
      return false;
    }
  }

  public static function createOneTimeKey($user) {
    $one_time_key = bin2hex(openssl_random_pseudo_bytes(32));

    $user->one_time_key = $one_time_key;
    $user->one_time_key_expiration_datetime = time() + 60 * 3 * 20;
    $user->save();

    $public_key = openssl_get_publickey($user->public_key);
    openssl_public_encrypt($one_time_key, $ciphertext, $public_key);

    return base64_encode($ciphertext);
  }

  public static function checkOneTimeKey($user, $plaintext) {
    if ($user->one_time_key_expiration_datetime < time()) {
      return [
        'status' => false,
        'response' => config('response.oneTimeKeyExpired')
      ];
    }

    if ($user->one_time_key !== $plaintext) {
      return [
        'status' => false,
        'response' => config('response.wrongLoginData')
      ];
    }

    $user->one_time_key = null;
    $user->one_time_key_expiration_datetime = null;
    $user->save();

    return ['status' => true];
  }

  public static function checkIsPublicKeyValid($public_key_text) {
    $public_key = openssl_get_publickey($public_key_text);
    return (bool) $public_key;
  }
}