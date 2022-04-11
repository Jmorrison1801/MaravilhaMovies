<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/', function (Request $request, Response $response) use ($app)
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

    $movies = $session_wrapper->getSessionVar('movie');

    if($movies != null)
    {
       getMovies($app);
    }

    getRecentMovies($app);

   return $this->view->render($response,
       'homepage.html.twig',
       [
           'css_path' => CSS_PATH,
           'landing_page' => $_SERVER["SCRIPT_NAME"],
           'login_button' => $values['action'],
           'login_value' => $values['value'],
           'search_action' => 'searchResults',
           'advance_action' => 'advanceSearch',
           'page_title' => 'Maravilha Movies',
           'page_heading_1' => 'Maravilha Movies',
           'page_heading_2' => 'Search for movie',
           'info_text' => 'Search for movie',
       ]
   );
})->setName('homepage');

function getMovies($app)
{
    $movie_manager = $app->getContainer()->get('MovieManager');
    $results = $movie_manager->sessionMovies($app);
    print($results);
}

function getRecentMovies($app)
{
    $movie_manager = $app->getContainer()->get('MovieManager');
    $results = $movie_manager->recentReleases($app);
    print($results);
}


