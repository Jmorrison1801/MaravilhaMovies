<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->map(['post','get'],'/viewInformation', function (Request $request, Response $response) use ($app)
{
    $accInfo = getAccountInfo($app);

    return $this->view->render($response,
        'viewInformation.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'login_button' => 'viewAccount',
            'login_value' => 'View Account',
            'account_overview' => 'viewAccount',
            'homepage' =>  $_SERVER["SCRIPT_NAME"],
            'search_action' => 'searchResults',
            'email' => $accInfo,
            'adv_search' => 'searchResults',
            'all_movies' => 'allMovies',
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Search for movie',
            'info_text' => 'Search for movie',
        ]
    );
})->setName('viewInformation');

function getAccountInfo($app)
{
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $values = $session_wrapper->getSessionVar('email');

    return $values;
}