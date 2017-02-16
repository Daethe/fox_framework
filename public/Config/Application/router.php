<?php

return [
    'assets' => [ '/:file', 'assets.dump', 'GET', '\Core\Controller\\' ], // Don't remove this one. Working for all assets in the application'
    'index' => [
        '/',
        'site.index',
        'GET',
        '\App\Controller\\'
    ],
];