<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->map(['post','get'],'/viewAccount', function (Request $request, Response $response) use ($app)
{
    return $this->view->render($response,
        'account.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'login_button' => 'viewAccount',
            'login_value' => 'View Account',
            'view_information' => 'viewInformation',
            'acc_preferences' => 'accPreferences',
            'logout_action' => 'logout',
            'homepage' =>  $_SERVER["SCRIPT_NAME"] ,
            'search_action' => 'searchResults',
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Search for movie',
            'info_text' => 'Search for movie',
        ]
    );
})->setName('viewAccount');

