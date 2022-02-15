<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/login', function (Request $request, Response $response) use ($app)
{
    return $this->view->render($response,
        'login.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'login_action' => 'loginProcess',
            'register_action' => 'register',
            'login_button' => 'login',
            'login_value' => 'Login',
            'homepage' =>  $_SERVER["SCRIPT_NAME"] ,
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Login',
            'info_text' => 'Login to Account',
        ]
    );
})->setName('login');