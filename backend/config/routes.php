<?php

return [
    '/' => 'site/index',
    'login' => 'site/login',
    'logout' => 'site/logout',

    'admin' => 'admin/index',
    'admin/create' => 'admin/create',
    'admin/<id:\d+>/update' => 'admin/update',
    'admin/<id:\d+>/view' => 'admin/view',

    'users' => 'user/index',
    'user/create' => 'user/create',
    'user/<id:\d+>/update' => 'user/update',
    'user/<id:\d+>/view' => 'user/view',

];