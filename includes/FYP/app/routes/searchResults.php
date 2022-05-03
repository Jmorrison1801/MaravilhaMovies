<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/searchResults', function (Request $request, Response $response) use ($app)
{
    $account_manager = $app->getContainer()->get('AccountManager');
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $values = $session_wrapper->getSessionVar('email');
    $movie_manager = $app->getContainer()->get('MovieManager');

    if($values == null)
    {
        $values = $account_manager->AccountCheck(false);
    }else{
        $values = $account_manager->AccountCheck(true);
    }

    $tainted_param = $request->getParsedBody();

    $clean_title = '';
    $clean_cast = '';
    $clean_director = '';


    if(isset($_GET['genre']))
    {
        $tag = $_GET['genre'];
        genreTag($app, $tag);
    }
    else if(isset($_GET['cast']))
    {
        $tag = $_GET['cast'];
        castTag($app, $tag);
    }
    else if(isset($_GET['director']))
    {
        $tag = $_GET['director'];
        directorTag($app, $tag);
    }
    else if(isset($_GET['ageRating']))
    {
        $tag = $_GET['ageRating'];
        ageRatingTag($app, $tag);
    }
    else if (array_key_exists("genre",$tainted_param))
    {
       $searchResults = advanceSearch($app, $tainted_param);
    }
    else if (array_key_exists("search-title",$tainted_param)){
        $searchResults = searchTitle($app, $tainted_param);
    }


    return $this->view->render($response,
        'searchResults.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'login_button' => $values['action'],
            'login_value' => $values['value'],
            'homepage' => $_SERVER["SCRIPT_NAME"],
            'search_action' => 'searchResults',
            'adv_search' => 'searchResults',
            'movie_view' => 'movieView',
            'all_movies' => 'allMovies',
            'advance_action' => 'advanceSearch',
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Search for movie',
            'info_text' => 'Search for movie',
        ]
    );
})->setName('searchResults');

function searchTitle($app,$clean_title)
{
    $movie_manager = $app->getContainer()->get('MovieManager');
    $result = $movie_manager->searchTitle($app, $clean_title);
    if(strlen($result) < 55)
    {
        $result = "
        <div class='null-search'>
            <h1>Sorry no films were found matching your search...</h1>
        </div>
        ";
    }
    print ($result);
}

function advanceSearch($app, $tainted_param)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $results = $movieManager->advanceSearch($app, $tainted_param);
    $result = $movieManager->displaySearchResults($results);
    if(strlen($result) < 55)
    {
        $result = "
        <div class='null-search'>
            <h1>Sorry no films were found matching your search...</h1>
        </div>
        ";
    }
    print($result);
}

function genreTag($app, $tag)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $results = $movieManager->searchGenre($app, $tag);
    $result = $movieManager->displaySearchResults($results);
    print($result);
}

function castTag($app, $tag)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $results = $movieManager->searchCast($app, $tag);
    $result = $movieManager->displaySearchResults($results);
    print($result);
}

function directorTag($app, $tag)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $results = $movieManager->searchDirector($app, $tag);
    $result = $movieManager->displaySearchResults($results);
    print($result);
}

function ageRatingTag($app, $tag)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $results = $movieManager->searchAgeRating($app, $tag);
    $result = $movieManager->displaySearchResults($results);
    print($result);
}
