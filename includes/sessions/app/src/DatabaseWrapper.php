<?php

/**
 * DatabaseWrapper.php
 *
 * Access the sessions database
 *
 * Author: CF Ingrams
 * Email: <clinton@cfing.co.uk>
 * Date: 22/10/2017
 *
 * @author CF Ingrams <clinton@cfing.co.uk>
 * @copyright CFI
 */

namespace Sessions;

class DatabaseWrapper implements SessionInterface
{
    private $database_connection_settings;
    private $db_handle;
    private $sql_queries;
    private $prepared_statement;
    private $errors;

    public function __construct()
    {
        $this->database_connection_settings = null;
        $this->db_handle = null;
        $this->sql_queries = null;
        $this->prepared_statement = null;
        $this->errors = [];
    }

    public function __destruct() { }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    /**
     * '\' character in front of the PDO class name signifies that it is a globally available class
     * and is not part of the Sessins namespavce
     *
     * @return string
     */
    public function makeDatabaseConnection()
    {
        $pdo = false;
        $pdo_error = '';

        $database_settings = $this->database_connection_settings;
        $host_name = $database_settings['rdbms'] . ':host=' . $database_settings['host'];
        $port_number = ';port=' . '3306';
        $user_database = ';dbname=' . $database_settings['db_name'];
        $host_details = $host_name . $port_number . $user_database;
        $user_name = $database_settings['user_name'];
        $user_password = $database_settings['user_password'];
        $pdo_attributes = $database_settings['options'];

        try
        {
            $pdo_handle = new \PDO($host_details, $user_name, $user_password, $pdo_attributes);
            $this->db_handle = $pdo_handle;
        }
        catch (\PDOException $exception_object)
        {
            trigger_error('error connecting to database');
            $pdo_error = 'error connecting to database';
        }

        return $pdo_error;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    public function setLogger(){}

    public function unsetSessionVar($session_key){}

    public function setSessionVar($session_key, $session_value)
    {
        if ($this->getSessionVar($session_key) === true)
        {
            $this->storeSessionVar($session_key, $session_value);
        }
        else
        {
            $this->createSessionVar($session_key, $session_value);
        }

        return($this->errors);
    }

    public function getSessionVar($session_key)
    {
        $session_var_exists = false;
        $query_string = $this->sql_queries->checkSessionVar();

        $query_parameters = [
            ':local_session_id' => session_id(),
            ':session_var_name' => $session_key
        ];

        $this->safeQuery($query_string, $query_parameters);

        if ($this->countRows() > 0)
        {
            $session_var_exists = true;
        }
        return $session_var_exists;
    }

    private function createSessionVar($session_key, $session_value)
    {
        $query_string = $this->sql_queries->createSessionVar();

        $query_parameters = [
            ':local_session_id' => session_id(),
            ':session_var_name' => $session_key,
            ':session_var_value' => $session_value
        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    private function storeSessionVar($session_key, $session_value)
    {
        $query_string = $this->sql_queries->setSessionVar();

        $query_parameters = [
            ':local_session_id' => session_id(),
            ':session_var_name' => $session_key,
            ':session_var_value' => $session_value
        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    /**
     * @param $query_string
     * @param null $params
     *
     * For transparency, each parameter value is bound separately to its placeholder
     * This is not always strictly necessary.
     *
     * @return mixed
     */
    private function safeQuery($query_string, $params = null)
    {
        $this->errors['db_error'] = false;
        $query_parameters = $params;

        try
        {
            $this->prepared_statement = $this->db_handle->prepare($query_string);
            $execute_result = $this->prepared_statement->execute($query_parameters);
            $this->errors['execute-OK'] = $execute_result;
        }
        catch (PDOException $exception_object)
        {
            $error_message  = 'PDO Exception caught. ';
            $error_message .= 'Error with the database access.' . "\n";
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(), true) . "\n";
            // NB would usually log to file for sysadmin attention
            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
        }
        return $this->errors['db_error'];
    }

    public function countRows()
    {
        $num_rows = $this->prepared_statement->rowCount();
        return $num_rows;
    }

    public function safeFetchRow()
    {
        $record_set = $this->prepared_statement->fetch(PDO::FETCH_NUM);
        return $record_set;
    }

    public function safeFetchArray()
    {
        $row = $this->prepared_statement->fetch(PDO::FETCH_ASSOC);
        $this->prepared_statement->closeCursor();
        return $row;
    }

    public function lastInsertedID()
    {
        $sql_query = 'SELECT LAST_INSERT_ID()';

        $this->safeQuery($sql_query);
        $last_inserted_id = $this->safeFetchArray();
        $last_inserted_id = $last_inserted_id['LAST_INSERT_ID()'];
        return $last_inserted_id;
    }
}
