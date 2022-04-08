<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/register', function (Request $request, Response $response) use ($app)
{
    return $this->view->render($response,
        'register.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'register_action_2' => 'registerProcess',
            'login_action_2' => 'login',
            'login_button' => 'login',
            'login_value' => 'Login',
            'homepage' =>  $_SERVER["SCRIPT_NAME"] ,
            'search_action' => 'searchResults',
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Register',
            'info_text' => 'Create Account',
        ]
    );
})->setName('register');