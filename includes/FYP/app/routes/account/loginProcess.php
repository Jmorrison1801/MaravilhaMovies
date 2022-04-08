<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->map(['post','get'],'/loginProcess', function (Request $request, Response $response) use ($app)
{
    $tainted_param = $request->getParsedBody();
    $loginError = checkLogin($tainted_param['Enter_Email_Address'], $tainted_param['Enter_Password'], $app);

    if ($loginError == true)
    {
        return $response->withRedirect('login');
    }
    else {
        return $response->withRedirect($_SERVER["SCRIPT_NAME"]);
    }
})->setName('loginProcess');

function checkLogin($email, $password, $app)
{
    $error = false;

    $query = $app->getContainer()->get('SQLQueries');
    $account_manager = $app->getContainer()->get('AccountManager');
    $database_wrapper = $app->getContainer()->get('DatabaseWrapper');
    $bcrypt_wrapper = $app->getContainer()->get('BcryptWrapper');

    $db_conf = $app->getContainer()->get('settings');
    $database_connection_settings = $db_conf['pdo_settings'];

    $account_manager->setEmail($email);
    $account_manager->setPassword($password);

    $account_manager->setDatabaseWrapper($database_wrapper);
    $account_manager->setSQLQueries($query);
    $account_manager->setDatabaseConnectionSettings($database_connection_settings);

    $account_manager->selectAccount();

    $result = $database_wrapper->getResult();

    if ($result == true)
    {
        $login_check = $bcrypt_wrapper->authenticatePassword($password, $result['password']);
        if($login_check == true)
        {
            $session_wrapper = $app->getContainer()->get('SessionWrapper');
            $session_model = $app->getContainer()->get('SessionModel');

            $results = $session_model->selectMovieInDatabase($app, $email);
            $session_value = explode(",",$results);

            $session_model->setSessionMovie($session_value);
            $session_model->setSessionEmail($email);
            $session_model->setSessionPassword($password);
            $session_model->setSessionWrapperFile($session_wrapper);

            $session_model->storeDataInSessionFile();
            $error = false;
        }
        if ($login_check == false)
        {
            $error = true;
        }
    }else{
        $error = true;
    }
    return $error;
}

