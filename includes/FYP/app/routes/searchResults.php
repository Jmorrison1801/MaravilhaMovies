<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/searchResults', function (Request $request, Response $response) use ($app)
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

    $clean_title = '';
    $clean_cast = '';
    $clean_director = '';

    if (sizeof($tainted_param) > 1)
    {
       $searchResults = advanceSearch($app, $tainted_param);
    } else {
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
            'movie_view' => 'movieView',
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
    print ($result);
}

function advanceSearch($app, $tainted_param)
{
    $movieManager = $app->getContainer()->get('MovieManager');
    $results = $movieManager->advanceSearch($app, $tainted_param);

    $result = "";

    foreach ($results as $value)
    {

        $result .= "
<fieldset id='search_results'>
<form action='movieView' method='POST'>
<h1>{$value['title']}</h1>
<input type='text' name='film_id' id='film_id' readonly value={$value['film_id']}>
<br>
<img id='search_image' src={$value['imageURL']} width='500' height='600'>
<br>
<input id='view_btn' type='submit' value='View'>
</form>
</fieldset>";

    }
    print($result);
    return $results;

}
