<?php

return [


    'defaults' => [
        'provider' => 'users',
    ],


    'providers' => [
        'users' => App\Models\User::class,
        'admins' => App\Models\Admin::class,
    ],

];
