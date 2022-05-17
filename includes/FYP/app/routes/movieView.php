<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/movieView', function (Request $request, Response $response) use ($app)
{
    $account_manager = $app->getContainer()->get('AccountManager');
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $account = $session_wrapper->getSessionVar('email');

    $cleaned_param = $request->getParsedBody();

    if(isset($_GET['favourite']))
    {
        $cleaned_param['film_id'] = $_GET['favourite'];
    }

    if($account == null)
    {
        $values = $account_manager->AccountCheck(false);
    }else{
        $values = $account_manager->AccountCheck(true);
        $msg = $account_manager-> favouriteCheck($cleaned_param['film_id'] ,$app, $account);
        $values['fav_action'] = $msg['fav_action'];
        $values['favourites_action'] = $msg['action'];
        $values['favourites_value'] = $msg['value'];
    }

    if(isset($_GET['fav_action']))
    {
        if($_GET['fav_action'] == 'remove'){
            $film_id = $_GET['favourite'];
            removeFavourite($app, $film_id);
            $msg = $account_manager-> favouriteCheck($cleaned_param['film_id'] ,$app, $account);
            $values['fav_action'] = $msg['fav_action'];
            $values['favourites_action'] = $msg['action'];
            $values['favourites_value'] = $msg['value'];
            getShowDates($app, $film_id);
            $movie = getDetails($app, $film_id);
        }
        if(isset($_GET['fav_action']))
        {
            if($_GET['fav_action'] == 'add'){
                $film_id = $_GET['favourite'];
                addTofavourites($app, $film_id);
                $msg = $account_manager-> favouriteCheck($cleaned_param['film_id'] ,$app, $account);
                $values['fav_action'] = $msg['fav_action'];
                $values['favourites_action'] = $msg['action'];
                $values['favourites_value'] = $msg['value'];
                getShowDates($app, $film_id);
                $movie = getDetails($app, $film_id);
            }
        }
    } else {
        addMovieToSession($app,$cleaned_param['film_id']);
        $movie = getDetails($app, $cleaned_param['film_id']);
        getShowDates($app, $cleaned_param['film_id']);
        if (array_key_exists("location",$cleaned_param))
        {
            getShowtimes($app, $cleaned_param);
        }
    }


    return $this->view->render($response,
        'movieView.html.twig',
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

            'title' => $movie['title'],
            'genre' => $movie['genre'],
            'cast' => $movie['cast'],
            'director' => $movie['director'],
            'release_date' => $movie['releaseDate'],
            'age_rating' => $movie['ageRating'],
            'movie_image' => $movie['imageURL'],
            'film_id' => $movie['film_id'],
            'favourites_action' => $values['favourites_action'] ,
            'favourites_value' => $values['favourites_value'],
            'fav_action' => $values['fav_action'],

            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Search for movie',
            'info_text' => 'Search for movie',
        ]
    );
})->setName('movieView');

function getDetails($app, $film_id)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $movie = $movieManager->searchId($app, $film_id);
    return $movie;
}

function getShowDates($app, $film_id)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $location = $movieManager->getDistinctLocations($app);
    $location_html = "
<div class='location'>
<form class='location' action='movieView' method='post'>
<label for='location'>Change Location: </label>
<select name='location' id='location'> ";
    foreach ($location as $value){
        $location_html .= "
<option value={$value['location']}>{$value['location']}</option>
        ";
    }
    $location_html .= "
</select>
<label for='showdate'>Choose a viewing date:</label>
<input class='showdate'name='showdate' type='date' value='sreening date' id='date' placeholder='search term' aria-label='search'>
<input type='text' name='film_id' id='film_id' readonly value={$film_id}>
<input type='submit' value='View Showtimes' class='input-btn'>
</form>
</div>
";
    print($location_html);

}

function getShowtimes($app, $cleaned_param)
{
        $selected_location = $cleaned_param['location'];
        $film_id = $cleaned_param['film_id'];
        $showdate = $cleaned_param['showdate'];
        $movieManager = $app->getContainer()->get('MovieManager');
        $collection = $movieManager->getShowtimes($app, $selected_location,$film_id, $showdate);
        $result = "";
        foreach ($collection as $movie)
        {
        $showtimes = explode(",",$movie['showtimes']);

        $result .= "
    <section class='showtimes'>
    <h1>{$movie['cinema']}, {$movie['location']}</h1>
    <h1>Screening Date</h1>
    <p>{$movie['showdate']}</p>
    <h2>Showtimes</h2>
    <div class='right_container'>
            ";

        $result .= "
            <div class='showtimes'>
            ";
        foreach ($showtimes as $time)
        {
            $result .= "
               <input name='time' id='time' type='button' value='{$time}' class='input-btn-2'>
                ";
        }
        $result .= "
    </div>
    </div>
    </section>";
    }
    print($result);
}

function addMovieToSession($app,$film_id)
{
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $session_model = $app->getContainer()->get('SessionModel');

    $films = [];
    array_push($films, $film_id);

    if ($session_wrapper->getSessionVar('movie') == null)
    {
        $session_model->setSessionMovie($films);
        $session_model->setSessionWrapperFile($session_wrapper);
        $session_model->storeMovieInSessionFile();
    }

    elseif (sizeof($session_wrapper->getSessionVar('movie')) == 6)
    {
        $values = $session_wrapper->getSessionVar('movie');
        array_pop($values);
        $values_to_store = array_merge($films, $values);

        $session_model->setSessionMovie($values_to_store);
        $session_model->setSessionWrapperFile($session_wrapper);

        $session_model->storeMovieInSessionFile();
        $values = $session_wrapper->getSessionVar('movie');
        $error = false;
    }

    elseif ($session_wrapper->getSessionVar('movie') != null)
    {
        $values = $session_wrapper->getSessionVar('movie');
        $values_to_store = array_merge($films, $values);

        $session_model->setSessionMovie($values_to_store);
        $session_model->setSessionWrapperFile($session_wrapper);

        $session_model->storeMovieInSessionFile();
        $values = $session_wrapper->getSessionVar('movie');
        $error = false;
    }
}

function storeSessionInDatabase($app)
{
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $session_model = $app->getContainer()->get('SessionModel');
    $email = $session_wrapper->getSessionVar('email');
    $films = $session_wrapper->getSessionVar('movie');
    $films = implode(",",$films);

    $session_model->storeMovieInDatabase($app, $email, $films);
}

function addTofavourites($app, $film_id)
{
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $email = $session_wrapper->getSessionVar('email');
    $acount_manager = $app->getContainer()->get("AccountManager");
    $acount_manager->addToFavourites($app, $email, $film_id);
}

function removeFavourite($app, $film_id)
{
    $session_wrapper = $app->getContainer()->get('SessionWrapper');
    $email = $session_wrapper->getSessionVar('email');
    $acount_manager = $app->getContainer()->get("AccountManager");
    $acount_manager->removeFavourites($app, $email, $film_id);
}