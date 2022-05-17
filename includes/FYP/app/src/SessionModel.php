<?php

namespace MaravilhaMovies;

class SessionModel
{
    private $email;
    private $accountID;
    private $password;
    private $movie;
    private $session_wrapper;
    private $database_connection_settings;
    private $sql_queries;
    private $session_wrapper_file;
    private $DatabaseWrapper;


    public function __construct()
    {
        $this->email = null;
        $this->password = null;
        $this->session_wrapper = null;
        $this->database_connection_settings = null;
        $this->sql_queries = null;
        $this->session_wrapper_file = null;
    }

    public function __destruct(){ }

    public function setSessionAccountID($accountID)
    {
        $this->accountID = $accountID;
    }

    public function setSessionEmail($email)
    {
        $this->email = $email;
    }

    public function setSessionPassword($password)
    {
        $this->password = $password;
    }

    public function setSessionMovie($movie)
    {
        $this->movie = $movie;
    }


    public function setSessionWrapper($session_wrapper)
    {
        $this->session_wrapper = $session_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function setDatabaseWrapper($DatabaseWrapper)
    {
        $this->DatabaseWrapper = $DatabaseWrapper;
    }

    public function setSessionWrapperFile($session_wrapper)
    {
        $this->session_wrapper_file = $session_wrapper;
    }

    public function storeDataInSessionFile()
    {
        $store_result = false;
        $store_result_accountID = $this->session_wrapper_file->setSessionVar('accountID', $this->accountID);
        $store_result_email = $this->session_wrapper_file->setSessionVar('email', $this->email);
        $store_result_password = $this->session_wrapper_file->setSessionVar('password', $this->password);


        if ($store_result_email !== false && $store_result_password !== false && $store_result_accountID !== false)	{
            $store_result = true;
        }
        return $store_result;
    }

    public function storeMovieInSessionFile()
    {
        $store_result = false;
        $store_result_movie = $this->session_wrapper_file->setSessionVar('movie', $this->movie);


        if ($store_result_movie !== false)	{
            $store_result = true;
        }
        return $store_result;
    }

    public function storeMovieInDatabase($app, $email, $films)
    {
        $this->setSessionEmail($email);
        $this->setSessionMovie($films);
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];

        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->updateRecentlyViewed();
    }

    public function selectMovieInDatabase($app, $email)
    {
        $this->setSessionEmail($email);
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];

        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectRecentlyViewed();

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function updateRecentlyViewed()
    {
        $email = $this->email;
        $films = $this->movie;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->updateRecentlyViewed($films, $email);
    }

    public function selectRecentlyViewed()
    {
        $email = $this->email;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectRecentlyViewed($email);
    }

}