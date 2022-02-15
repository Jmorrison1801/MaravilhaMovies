<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->map(['post','get'],'/logout', function (Request $request, Response $response) use ($app)
{
    $_SESSION = array();
    return $response->withRedirect($_SERVER["SCRIPT_NAME"]);

})->setName('logout');