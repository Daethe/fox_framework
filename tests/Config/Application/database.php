<?php

if (APP_DEV) {
    return [
        'type' => 'mysql',
        'host' => 'localhost',
        'name' => 'translate_tetesbrulees',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ];
} else {
    return [
        'type' => 'mysql',
        'host' => 'localhost',
        'name' => '',
        'username' => '',
        'password' => '',
        'charset' => 'utf8'
    ];
}