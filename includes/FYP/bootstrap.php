<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

session_start();

require 'vendor/autoload.php';

$app_path = __DIR__ . '/app/';

$settings = require $app_path . 'settings.php';

$container = new \Slim\Container($settings);

require $app_path . 'dependencies.php';

$app = new \Slim\App($container);

require $app_path . 'routes.php';

$app->run();

session_regenerate_id(true);