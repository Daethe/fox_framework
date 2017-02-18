<?php

return [
    'useDatabase' => false,
    'database' => require(__DIR__ . '/Application/database.php'),

    'alias' => require(__DIR__ . '/Application/alias.php'),

    'router' => require(__DIR__ . '/Application/router.php'),
];