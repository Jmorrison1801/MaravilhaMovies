<?php

namespace MaravilhaMovies;

class SQLQueries
{
    public function __construct(){}

    public function __destruct(){}


    public static function insertAccountVar()
    {
        $query_string  = "INSERT INTO customertbl ";
        $query_string .= "SET email = :email, ";
        $query_string .= "password = :password ";

        return $query_string;
    }

    public static function selectAccountVar()
    {
        $query_string = "SELECT * FROM customertbl ";
        $query_string .= "WHERE email = :email";

        return $query_string;

        /*
        $query_string = "SELECT * FROM customertbl ";
        $query_string .= "WHERE email = :email AND ";
        $query_string .= "password = :password";

        return $query_string;
         */
    }


}