<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/movieView', function (Request $request, Response $response) use ($app)
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

    $tainted_param = $request->getParsedBody();

    $movie = getDetails($app, $tainted_param);

    getDistinctLocations($app, $tainted_param);

    if (array_key_exists("location",$tainted_param))
    {
        getShowtimes($app, $tainted_param);
    }
    else
    {
        addMovieToSession($app,$tainted_param['film_id']);
        storeSessionInDatabase($app);
    }


    return $this->view->render($response,
        'movieView.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'login_button' => $values['action'],
            'login_value' => $values['value'],
            'homepage' => $_SERVER["SCRIPT_NAME"],
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

            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Search for movie',
            'info_text' => 'Search for movie',
        ]
    );
})->setName('movieView');

function getDetails($app, $tainted_param)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $movie = $movieManager->searchId($app, $tainted_param['film_id']);
    return $movie;
}


function getDistinctLocations($app, $tainted_param)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $location = $movieManager->getDistinctLocations($app);
    $location_html = "
<fieldset id='results_locations'>
<form id='' action='movieView' method='post'>
<label for='location'>Change Location: </label>
<select name='location' id='location'> ";
    foreach ($location as $value){
        $location_html .= "
<option value={$value['location']}>{$value['location']}</option>
        ";
    }
    $location_html .= "
</select>
<input type='text' name='film_id' id='film_id' readonly value={$tainted_param['film_id']}>
<input type='submit' value='View Showtimes' id='search_btn'>
</form>
</fieldset>
";
    print($location_html);
}

function getShowtimes($app, $tainted_param)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $showtimes = $movieManager->getShowtimes($app, $tainted_param['location'], $tainted_param['film_id']);
    $result = "";

    foreach ($showtimes as $value)
    {
        $showtimes_value = explode(",",$value['showtimes']);

        $result .= "
<fieldset>
<section id='showtimes'>
<h1>Showtimes</h1>
<br>
<h2>Cinema</h2>
<br>
<p>{$value['cinema']}</p>
<br>
<h2>Location</h2>
<br>
<p>{$value['location']}</p>
<br>
<h2>Showtimes</h2>
<br>
        ";

        foreach ($showtimes_value as $time)
        {
            $result .= "
            <input name='time' id='time' type='button' value='{$time}'>
            ";
        }
        $result .= "
</section>
</fieldset>";
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