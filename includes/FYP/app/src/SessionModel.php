<?php

namespace MaravilhaMovies;

class SessionModel
{
    private $email;
    private $password;
    private $session_wrapper;
    private $database_connection_settings;
    private $sql_queries;
    private $session_wrapper_file;


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

    public function setSessionEmail($email)
    {
        $this->email = $email;
    }

    public function setSessionPassword($password)
    {
        $this->password = $password;
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

    public function setSessionWrapperFile($session_wrapper)
    {
        $this->session_wrapper_file = $session_wrapper;
    }

    public function storeDataInSessionFile()
    {
        $store_result = false;
        $store_result_email = $this->session_wrapper_file->setSessionVar('email', $this->email);
        $store_result_password = $this->session_wrapper_file->setSessionVar('password', $this->password);

        if ($store_result_email !== false && $store_result_password !== false)	{
            $store_result = true;
        }
        return $store_result;
    }

}