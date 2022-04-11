<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

$app_url = dirname($_SERVER['SCRIPT_NAME']);
$css_path = $app_url . '/style/style.css';
define('CSS_PATH', $css_path);

$settings = [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'auto_reload' => true
            ],
        ],

        'pdo_settings' => [
            'rdbms' => 'mysql',
            'host' => 'localhost',
            'db_name' => 'maravilhamoviesdb',
            'user_name' => 'p2520453',
            'user_password' => 'jmorrison1801',
            'port' => '3306',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
                ],
            ],
    ]
];

return $settings;