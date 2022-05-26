<?php


namespace MaravilhaMovies;


class AccountManager
{
    private $database_connection_settings;
    private $sql_queries;
    private $DatabaseWrapper;
    private $email;
    private $password;


    public function addAccount()
    {
        $email = $this->email;
        $password = $this->password;
        $this->DatabaseWrapper->setSqlQueries( $this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->insertAccount($email, $password);
    }

    public function selectAccount()
    {
        $email = $this->email;
        $password = $this->password;
        $this->DatabaseWrapper->setSqlQueries( $this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectAccount($email);

    }

    public function setDatabaseWrapper($DatabaseWrapper)
    {
        $this->DatabaseWrapper = $DatabaseWrapper;
    }


    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function  setPassword($password){
        $this->password = $password;
    }

    public function AccountCheck($sessionCheck)
    {
        $msg = [
            'action' => 'login',
            'value' => 'Login',
            'favourites_value' => 'Add To favourites',
            'favourites_action' => 'addToFavourites',
            'fav_action' => '',
        ];

        if($sessionCheck == true)
        {
            $msg['action'] = 'viewAccount';
            $msg['value'] = 'View Account';
            $msg['favourites_value'] = 'Add To favourites';
            $msg['favourites_action'] = 'movieView';
        }else{
            $msg['action'] = 'login';
            $msg['value'] = 'Login';
            $msg['favourites_value'] = 'Login to add movie to favourites';
            $msg['favourites_action'] = 'login';
        }
        return $msg;
    }

    public function favouriteCheck($film_id ,$app, $email)
    {
        $msg = [
            'action' => '',
            'fav_action' => '',
            'value' => '',
        ];

        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];

        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->DatabaseWrapper->setSqlQueries( $this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectAccount($email);

        $account = $database_wrapper->getResult();

            $favourites = explode(",",$account['favourites']);
            if(in_array($film_id, $favourites) == true)
            {
                $msg = [
                    'action' => 'movieView',
                    'fav_action' => 'remove',
                    'value' => 'Remove film from favourites',
                ];
            } else {
                $msg = [
                    'action' => 'movieView',
                    'fav_action' => 'add',
                    'value' => 'Add film To favourites',
                ];
            }

        return $msg;

    }

    public function deleteAccount($app)
    {
        $session_wrapper = $app->getContainer()->get('SessionWrapper');
        $value = $session_wrapper->getSessionVar('accountID');

        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->DatabaseWrapper->setSqlQueries( $this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->deleteAccount($value);

        $results = $database_wrapper->getResult();

        return $results;
    }

    public function addToFavourites($app, $email, $film_id)
    {

        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];

        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->DatabaseWrapper->setSqlQueries( $this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectAccount($email);

        $account = $database_wrapper->getResult();

        $favourites = array();

        if($account['favourites'] == null)
        {
            array_push($favourites, $film_id);
            $favourites = implode(",",$favourites);
            $this->DatabaseWrapper->updateFavouritesViewed($favourites, $email);
        } else {
            $favourites = explode(",", $account['favourites']);
            if(in_array($film_id, $favourites) == false)
            {
                array_push($favourites, $film_id);
                $favourites = implode(",",$favourites);
                $this->DatabaseWrapper->updateFavouritesViewed($favourites, $email);
            }
        }
    }

    public function removeFavourites($app, $email, $film_id)
    {
        $session_wrapper = $app->getContainer()->get('SessionWrapper');
        $value = $session_wrapper->getSessionVar('accountID');

        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];

        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->DatabaseWrapper->setSqlQueries( $this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectAccount($email);

        $account = $database_wrapper->getResult();

        $favourites = explode(",", $account['favourites']);
        if(in_array($film_id, $favourites) == true)
        {
            $key = array_search($film_id, $favourites);
            unset($favourites[$key]);
            $favourites = implode(",",$favourites);
            $this->DatabaseWrapper->updateFavouritesViewed($favourites, $email);
        }
    }
}