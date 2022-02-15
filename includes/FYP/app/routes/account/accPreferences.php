<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/accPreferences', function (Request $request, Response $response) use ($app)
{
    return $this->view->render($response,
        'accPreferences.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'login_button' => 'viewAccount',
            'login_value' => 'View Account',
            'account_overview' => 'viewAccount',
            'homepage' =>  $_SERVER["SCRIPT_NAME"] ,
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Search for movie',
            'info_text' => 'Search for movie',
        ]
    );
})->setName('accPreferences');