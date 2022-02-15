<?php

/**
 * SQLQueries.php
 *
 * hosts all SQL queries to be used by the Model
 *
 * Author: CF Ingrams
 * Email: <clinton@cfing.co.uk>
 * Date: 22/10/2017
 *
 * @author CF Ingrams <clinton@cfing.co.uk>
 * @copyright CFI
 */

namespace Sessions;

class SQLQueries
{
    public function __construct() {}

    public function __destruct() {}

    public static function checkSessionVar()
    {
        $query_string  = "SELECT session_var_name ";
        $query_string .= "FROM session ";
        $query_string .= "WHERE session_id = :local_session_id ";
        $query_string .= "AND session_var_name = :session_var_name ";
        $query_string .= "LIMIT 1";
        return $query_string;
    }

    public static function createSessionVar()
    {
        $query_string  = "INSERT INTO session ";
        $query_string .= "SET session_id = :local_session_id, ";
        $query_string .= "session_var_name = :session_var_name, ";
        $query_string .= "session_value = :session_var_value ";
        return $query_string;
    }

    public static function setSessionVar()
    {
        $query_string  = "UPDATE session ";
        $query_string .= "SET session_value = :session_var_value ";
        $query_string .= "WHERE session_id = :local_session_id ";
        $query_string .= "AND session_var_name = :session_var_name";
        return $query_string;
    }

    public static function getSessionVar()
    {
        $query_string  = "SELECT session_value ";
        $query_string .= "FROM session ";
        $query_string .= "WHERE session_id = :local_session_id ";
        $query_string .= "AND session_var_name = :session_var_name";
        return $query_string;
    }
}
