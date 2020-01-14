<?php

$version = 'v1';

return [
    '/' => $version . '/default/index',
    '/version' => $version . '/default/version',
    '/cep-search' => $version . 'default/cep-search',

    'POST user/facebook-auth' => $version . '/user/facebook-auth',
    'POST user/create-auth' => $version . '/user/create-auth',
    'POST user/email-auth' => $version . '/user/email-auth',
    'POST user/auth' => 'oauth2/rest/token',
    'POST user/update' => $version . '/user/update',
    'POST user/update-token-android' => $version . '/user/update-token-android',
    'user/<id:\d+>' => $version . '/user/view',


];