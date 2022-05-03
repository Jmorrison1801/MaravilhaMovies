<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/allMovies', function (Request $request, Response $response) use ($app)
{
    $account_manager = $app->getContainer()->get('AccountManager');
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $values = $session_wrapper->getSessionVar('email');

    if($values == null)
    {
        $values = $account_manager->AccountCheck(false);
    }else{
        $values = $account_manager->AccountCheck(true);
    }

    getAllMovies($app);

    return $this->view->render($response,
        'allMovies.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'login_button' => $values['action'],
            'login_value' => $values['value'],
            'homepage' => $_SERVER["SCRIPT_NAME"],
            'search_action' => 'searchResults',
            'adv_search' => 'searchResults',
            'movie_view' => 'movieView',
            'advance_action' => 'advanceSearch',
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Search for movie',
            'info_text' => 'Search for movie',
        ]
    );
})->setName('allMovies');

function getAllMovies($app)
{
    $movie_manager = $app->getContainer()->get('MovieManager');
    $result = $movie_manager->getAllMovies($app);
    print ($result);
}