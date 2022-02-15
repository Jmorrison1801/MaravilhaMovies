<?php
/**
 * SessionModel.php
 *
 * stores the validated values in the relevant storage location
 *
 * Author: CF Ingrams
 * Email: <clinton@cfing.co.uk>
 * Date: 22/10/2017
 *
 *
 * @author CF Ingrams <clinton@cfing.co.uk>
 * @copyright CFI
 *
 */

/**
 * SessionModel.php
 *
 * stores the validated values in the relevant storage location
 *
 * Author: CF Ingrams
 * Email: <clinton@cfing.co.uk>
 * Date: 22/10/2017
 *
 *
 * @author CF Ingrams <clinton@cfing.co.uk>
 * @copyright CFI
 *
 */

namespace Sessions;

class SessionModel
{
    private $username;
    private $server_type;
    private $password;
    private $storage_result;
    private $session_wrapper_file;
    private $session_wrapper_database;
    private $database_connection_settings;
    private $sql_queries;

    public function __construct()
    {
        $this->username = null;
        $this->server_type = null;
        $this->password = null;
        $this->storage_result = null;
        $this->session_wrapper_file = null;
        $this->session_wrapper_database = null;
        $this->database_connection_settings = null;
        $this->sql_queries = null;
    }

    public function __destruct() { }

    public function setSessionUsername($username)
    {
        $this->username = $username;
    }

    public function setSessionPassword($password)
    {
        $this->password = $password;
    }

    public function setServerType($server_type)
    {
        $this->server_type = $server_type;
    }

    public function setSessionWrapperFile($session_wrapper)
    {
        $this->session_wrapper_file = $session_wrapper;
    }

    public function setSessionWrapperDatabase($session_wrapper)
    {
        $this->session_wrapper_database = $session_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function storeData()
    {
        switch ($this->server_type)
        {
            case 'database':
                $storage_result = $this->storeDataInSessionDatabase();
                break;
            case 'file':
            default:
                $storage_result = $this->storeDataInSessionFile();
        }
        $this->storage_result = $storage_result;
    }

    public function getStorageResult()
    {
        return $this->storage_result;
    }

    private function storeDataInSessionFile()
    {
        $store_result = false;
        $store_result_username = $this->session_wrapper_file->setSessionVar('user_name', $this->username);
        $store_result_password = $this->session_wrapper_file->setSessionVar('password', $this->password);

        if ($store_result_username !== false && $store_result_password !== false)	{
            $store_result = true;
        }
        return $store_result;
    }

    public function storeDataInSessionDatabase()
    {
        $store_result = false;

        $this->session_wrapper_database->setSqlQueries( $this->sql_queries);
        $this->session_wrapper_database->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->session_wrapper_database->makeDatabaseConnection();

        $store_result_username = $this->session_wrapper_database->setSessionVar('user_name', $this->username);
        $store_result_password = $this->session_wrapper_database->setSessionVar('user_password', $this->password);

        if ($store_result_username !== false && $store_result_password !== false)
        {
            $store_result = true;
        }
        return $store_result;
    }
}
