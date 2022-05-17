<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->map(['post','get'],'/registerProcess', function (Request $request, Response $response) use ($app)
{
    $tainted_param = $request->getParsedBody();

    $validate = $app->getContainer()->get('RegisterValidator');
    $validate->validateEmailAddress($tainted_param['Enter_Email_Address']);
    $validate->validatePassword($tainted_param['Enter_Password'], $tainted_param['Confirm_Password']);

    $pwChk = $validate->getEmailchk();
    $emailChk = $validate->getPasswordchk();

    if ($emailChk == true && $pwChk == true){
        $AccountPW = $validate->getPassword();
        $AccountEmail = $validate->getEmail();

        $bcrypt_wrapper = $app->getContainer()->get('BcryptWrapper');
        $AccountPW = $bcrypt_wrapper->createHashedPassword($AccountPW);

        $query = $app->getContainer()->get('SQLQueries');
        $account_manager = $app->getContainer()->get('AccountManager');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];

        $account_manager->setEmail($AccountEmail);
        $account_manager->setPassword($AccountPW);

        $account_manager->setDatabaseWrapper($database_wrapper);
        $account_manager->setSQLQueries($query);
        $account_manager->setDatabaseConnectionSettings($database_connection_settings);

        $account_manager->addAccount();


        return $response->withRedirect('login');

    }else{
        return $response->withRedirect('register');
    }

})->setName('registerProcess');




