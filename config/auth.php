<?php

return [


    'default' => [
        'guard' => 'users',
    ],


    'guards' => [
        'users' => App\Models\User::class,
        'admins' => App\Models\Admin::class,
    ],

];
