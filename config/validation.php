<?php

$hex_regex = 'regex:/^[a-f0-9]*$/i';
$base64_regex = 'regex:/^[a-z0-9+=\/]*$/i';

return [
  'user' => [
    'username' => [
      'required',
      'string',
      'regex:/^([a-z][a-z0-9_]{3,30}[a-z0-9]|demo)$/i'
    ],
    'public_key' => [
      'required',
      'between:110,500',
      'string',
      'regex:/^[a-z0-9+-=\/ \n\r]*$/i'
    ],
    'signature' => [
      'required',
      'string',
      $base64_regex
    ],
    'is_admin' => [
      'boolean'
    ],
  ],
  
  'note' => [
    'limit' => [
      'required',
      'integer',
      'between:1,300'
    ],
    'offset' => [
      'required',
      'integer',
      'min:0'
    ],
    'ciphertext' => [
      'required',
      'string',
      'between:1,10000000',
      $base64_regex
    ],
    'iv' => [
      'required',
      'string',
      'size:32',
      $hex_regex
    ],
    'salt' => [
      'required',
      'string',
      'between:16,256',
      $hex_regex
    ]
  ]
];