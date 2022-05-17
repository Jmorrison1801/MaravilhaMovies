<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/deleteAccount', function (Request $request, Response $response) use ($app)
{

    $validator = $app->getContainer()->get('Validator');
    $tainted_param = $request->getParsedBody();

    if(array_key_exists('delete',$tainted_param))
    {
        $cleaned_param = $validator->validateString($tainted_param['delete']);
        deleteAccount($app,$cleaned_param);
        $_SESSION = array();
        return $response->withRedirect($_SERVER["SCRIPT_NAME"]);
    }

    return $this->view->render($response,
        'deleteAccount.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'homepage' =>  $_SERVER["SCRIPT_NAME"] ,
            'login_button' => 'viewAccount',
            'login_value' => 'View Account',
            'search_action' => 'searchResults',
            'all_movies' => 'allMovies',
            'advance_action' => 'advanceSearch',
            'delete_account' => 'deleteAccount',
            'page_title' => 'Maravilha Movies',
            'page_heading_1' => 'Maravilha Movies',
            'page_heading_2' => 'Login',
            'info_text' => 'Login to Account',
        ]
    );
})->setName('deleteAccount');

function deleteAccount($app,$cleaned_param)
{
    if ($cleaned_param == 'DELETE')
    {
        $account_manager = $app->getContainer()->get('AccountManager');
        $account_manager->deleteAccount($app);
    } else {
        print("<h2>Try Again...</h2>");
    }
}