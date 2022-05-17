<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/favourites', function (Request $request, Response $response) use ($app)
{

    if(isset($_GET['favourite']))
    {
        $film_id = $_GET['favourite'];
        removeFavourite($app, $film_id);
    }

    $account_manager = $app->getContainer()->get('AccountManager');
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $values = $session_wrapper->getSessionVar('email');

    if($values == null)
    {
        $values = $account_manager->AccountCheck(false);
    }else{
        $values = $account_manager->AccountCheck(true);

    }

    favourites($app);

    return $this->view->render($response,
        'favourites.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'login_button' => $values['action'],
            'login_value' => $values['value'],
            'homepage' => $_SERVER["SCRIPT_NAME"],
            'all_movies' => 'allMovies',
            'search_action' => 'searchResults',
            'movie_view' => 'movieView',
            'advance_action' => 'advanceSearch',
            'favourites_action' => $values['favourites_action'],
            'favourites_value' => $values['favourites_value'],
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Search for movie',
            'info_text' => 'Search for movie',
        ]
    );
})->setName('favourites');

function favourites($app)
{
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $email = $session_wrapper->getSessionVar('email');
    $movieManager = $app->getContainer()->get('MovieManager');
    $movie = $movieManager->getFavourites($app, $email);
    print($movie);
}

