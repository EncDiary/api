<?php

return [
  'user' => [
    'username' => [
      'required',
      'regex:/^([a-z][a-z0-9_]{3,30}[a-z0-9]|demo)$/i'
    ],
    'public_key' => [
      'required',
      'between:110,500'
    ],
    'plaintext' => [
      'required',
      'size:64'
    ]
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
      'between:1,10000000'
    ],
    'iv' => [
      'required',
      'string',
      'size:32'
    ],
    'salt' => [
      'required',
      'string',
      'between:128,2048'
    ]
  ]
];