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
        ];

        if($sessionCheck == true)
        {
            $msg['action'] = 'viewAccount';
            $msg['value'] = 'View Account';
        }else{
            $msg['action'] = 'login';
            $msg['value'] = 'Login';
        }

        return $msg;
    }
}