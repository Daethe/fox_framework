<?php

return [
    'assets' => [ '/:file', 'assets.dump', 'GET', '\Core\Controller\\' ], // Don't remove this one. Working for all assets in the application'
    'index' => [
        '/',
        'some.index',
        'GET',
        '\CoreTests\Fixtures\\'
    ],
	'users' => [
		'/users',
		'some.users',
		'POST',
		'\CoreTests\Fixtures\\'
	]
];