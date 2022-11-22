<?php

return [
    // token openssl algo
    'algo' => "AES-128-ECB",

    // algo password
    'passphrase' => env('JXT_SECRET'),

    // unit: second
    'expiration_period' => 2592000,

    // default field for login key per guard. example: email, username
    'login_field' => 'email',
];
