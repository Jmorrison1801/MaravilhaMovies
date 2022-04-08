<?php


namespace MaravilhaMovies;

use PDO;

class DatabaseWrapper
{
    private $database_connection_settings;
    private $db_handle;
    private $sql_queries;
    private $prepared_statement;
    private $errors;
    private $execute_result;
    private $result;

    public function __construct()
    {
        $this->database_connection_settings = null;
        $this->db_handle = null;
        $this->sql_queries = null;
        $this->prepared_statement = null;
        $this->errors = null;
    }

    public function __destruct(){ }


    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

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


    /***************************
    Account Database Management
     ***************************/

    public function insertAccount($email, $password){

        $query_string = $this->sql_queries->insertAccountVar();

        $query_parameters = [
            ':email' => $email,
            ':password' => $password,

        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    public function selectAccount($email)
    {
        $query_string = $this->sql_queries->selectAccountVar();

        $query_parameters = [
            ':email' => $email
        ];


        $this->safeSingleSelect($query_string, $query_parameters);
    }

    public function updateRecentlyViewed($films, $email)
    {
        $query_string = $this->sql_queries->updateViewedMoviesVar();

        $query_parameters = [
            ':email' => $email,
            ':recentlyViewed' => $films
        ];

        $this->safeQuery($query_string, $query_parameters);
    }

    public function selectRecentlyViewed($email)
    {
        $query_string = $this->sql_queries->selectViewedMoviesVar();

        $query_parameters = [
            ':email' => $email,
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }


    /***************************
    Movie Database Management
     ***************************/

    public function selectMovie($title)
    {
        $query_string = $this->sql_queries->selectMovieVar();

        $query_parameters = [
            ':title' => $title
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieId($film_id)
    {
        $query_string = $this->sql_queries->selectMovieIdVar();

        $query_parameters = [
            ':film_id' => $film_id
        ];


        $this->safeSingleSelect($query_string, $query_parameters);
    }

    /***************************
    G
     ***************************/
    public function selectMovieGenre($genre)
    {
        $query_string = $this->sql_queries->selectMovieGenreVar();

        $query_parameters = [
            ':genre' => $genre
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    /***************************
    C
     ***************************/
    public function selectMovieCast($cast)
    {
        $query_string = $this->sql_queries->selectMovieCastVar();

        $query_parameters = [
            ':cast' => $cast
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    /***************************
    D
     ***************************/
    public function selectMovieDirector($director)
    {
        $query_string = $this->sql_queries->selectMovieDirectorVar();

        $query_parameters = [
            ':director' => $director
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    /***************************
    R
     ***************************/
    public function selectMovieReleaseDate($min, $max)
    {
        $query_string = $this->sql_queries->selectMovieDateVar();

        $query_parameters = [
            ':min' => $min,
            ':max' => $max
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    /***************************
    A
     ***************************/
    public function selectMovieAgeRating($ageRating)
    {
        $query_string = $this->sql_queries->selectMovieAgeVar();

        $query_parameters = [
            ':ageRating' => $ageRating
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGA($genre,$ageRating)
    {
        $query_string = $this->sql_queries->selectGAVar();

        $query_parameters = [
            ':genre' => $genre,
            ':ageRating' => $ageRating
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGR($genre,$min, $max)
    {
        $query_string = $this->sql_queries->selectGRVar();

        $query_parameters = [
            ':genre' => $genre,
            ':min' => $min,
            ':max' => $max
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGD($genre,$director)
    {
        $query_string = $this->sql_queries->selectGDVar();

        $query_parameters = [
            ':genre' => $genre,
            ':director' => $director
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGC($genre,$cast)
    {
        $query_string = $this->sql_queries->selectGCVar();

        $query_parameters = [
            ':genre' => $genre,
            ':cast' => $cast
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieAR($ageRating,$min,$max)
    {
        $query_string = $this->sql_queries->selectARVar();

        $query_parameters = [
            ':ageRating' => $ageRating,
            ':min' => $min,
            ':max' => $max
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieAD($ageRating,$director)
    {
        $query_string = $this->sql_queries->selectADVar();

        $query_parameters = [
            ':ageRating' => $ageRating,
            ':director' => $director
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieAC($ageRating,$cast)
    {
        $query_string = $this->sql_queries->selectACVar();

        $query_parameters = [
            ':ageRating' => $ageRating,
            ':cast' => $cast
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieRC($min,$max,$cast)
    {
        $query_string = $this->sql_queries->selectRCVar();

        $query_parameters = [
            ':min' => $min,
            ':max' => $max,
            ':cast' => $cast
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieRD($min,$max,$director)
    {
        $query_string = $this->sql_queries->selectRDVar();

        $query_parameters = [
            ':min' => $min,
            ':max' => $max,
            ':director' => $director
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieDC($director,$cast)
    {
        $query_string = $this->sql_queries->selectDCVar();

        $query_parameters = [
            ':director' => $director,
            ':cast' => $cast
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGAR($genre, $ageRating,$min,$max)
    {
        $query_string = $this->sql_queries->selectGARVar();

        $query_parameters = [
            ':genre' => $genre,
            ':ageRating' => $ageRating,
            ':min' => $min,
            ':max' => $max
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGAD($genre, $ageRating,$director)
    {
        $query_string = $this->sql_queries->selectGADVar();

        $query_parameters = [
            ':genre' => $genre,
            ':ageRating' => $ageRating,
            ':director' => $director
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGAC($genre, $ageRating,$cast)
    {
        $query_string = $this->sql_queries->selectGACVar();

        $query_parameters = [
            ':genre' => $genre,
            ':ageRating' => $ageRating,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGRD($genre,$min,$max,$director)
    {
        $query_string = $this->sql_queries->selectGRDVar();

        $query_parameters = [
            ':genre' => $genre,
            ':min' => $min,
            ':max' => $max,
            ':director' => $director
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGRC($genre,$min,$max,$cast)
    {
        $query_string = $this->sql_queries->selectGRCVar();

        $query_parameters = [
            ':genre' => $genre,
            ':min' => $min,
            ':max' => $max,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGDC($genre,$director,$cast)
    {
        $query_string = $this->sql_queries->selectGDCVar();

        $query_parameters = [
            ':genre' => $genre,
            ':director' => $director,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieARD($ageRating,$min,$max,$director)
    {
        $query_string = $this->sql_queries->selectARDVar();

        $query_parameters = [
            ':ageRating' => $ageRating,
            ':min' => $min,
            ':max' => $max,
            ':director' => $director
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieARC($ageRating,$min,$max,$cast)
    {
        $query_string = $this->sql_queries->selectARCVar();

        $query_parameters = [
            ':ageRating' => $ageRating,
            ':min' => $min,
            ':max' => $max,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieADC($ageRating,$director,$cast)
    {
        $query_string = $this->sql_queries->selectADCVar();

        $query_parameters = [
            ':ageRating' => $ageRating,
            ':director' => $director,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieRDC($min,$max,$director,$cast)
    {
        $query_string = $this->sql_queries->selectRDCVar();

        $query_parameters = [
            ':min' => $min,
            ':max' => $max,
            ':director' => $director,
            ':cast' => $cast
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGARD($genre, $ageRating,$min,$max,$director)
    {
        $query_string = $this->sql_queries->selectGARDVar();

        $query_parameters = [
            ':genre' => $genre,
            ':ageRating' => $ageRating,
            ':min' => $min,
            ':max' => $max,
            ':director' => $director
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGARC($genre, $ageRating,$min,$max,$cast)
    {
        $query_string = $this->sql_queries->selectGARCVar();

        $query_parameters = [
            ':genre' => $genre,
            ':ageRating' => $ageRating,
            ':min' => $min,
            ':max' => $max,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGRDC($genre,$min,$max,$director,$cast)
    {
        $query_string = $this->sql_queries->selectGRDCVar();

        $query_parameters = [
            ':genre' => $genre,
            ':min' => $min,
            ':max' => $max,
            ':director' => $director,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieARDC($ageRating,$min,$max,$director,$cast)
    {
        $query_string = $this->sql_queries->selectARDCVar();

        $query_parameters = [
            ':ageRating' => $ageRating,
            ':min' => $min,
            ':max' => $max,
            ':director' => $director,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectMovieGARDC($genre, $ageRating,$min,$max,$director,$cast)
    {
        $query_string = $this->sql_queries->selectGARDCVar();

        $query_parameters = [
            ':genre' => $genre,
            ':ageRating' => $ageRating,
            ':min' => $min,
            ':max' => $max,
            ':director' => $director,
            ':cast' => $cast
        ];

        $this->safeMovieSelect($query_string, $query_parameters);
    }

    /***************************
                misc
     ***************************/
    public function selectDistinctLocation()
    {
        $query_string = $this->sql_queries->getDistinctVar();

        $this->safeSelect($query_string);
    }

    public function selectShowtimes($film_id, $location)
    {
        $query_string = $this->sql_queries->selectShowtimesVar();

        $query_parameters = [
            ':film_id' => $film_id,
            ':location' => $location
        ];


        $this->safeMovieSelect($query_string, $query_parameters);
    }

    public function selectAllMovies()
    {
        $query_string = $this->sql_queries->selectAllMoviesVar();

        $this->safeSelect($query_string);
    }



    /***************************
            Safe Queries
     ***************************/

    private function safeMovieSelect($query_string, $params = null)
    {
        $this->errors['db_error'] = false;
        $query_parameters = $params;

        try
        {
            $this->prepared_statement = $this->db_handle->prepare($query_string);
            $this->execute_result = $this->prepared_statement->execute($query_parameters);
            $this->result = $this->prepared_statement->fetchAll(PDO::FETCH_ASSOC);
            $this->errors['execute-OK'] = $this->execute_result;
            return $this->result;
        }
        catch (PDOException $exception_object)
        {
            $error_message  = 'PDO Exception caught. ';
            $error_message .= 'Error with the database access.' . "\n";
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(), true) . "\n";
            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
        }
    }

    private function safeSelect($query_string)
    {
        $this->errors['db_error'] = false;

        try
        {
            $this->prepared_statement = $this->db_handle->query($query_string);
            $this->result = $this->prepared_statement->fetchAll(PDO::FETCH_ASSOC);
            $this->errors['execute-OK'] = $this->execute_result;
            return $this->result;

        }
        catch (PDOException $exception_object)
        {
            $error_message  = 'PDO Exception caught. ';
            $error_message .= 'Error with the database access.' . "\n";
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(), true) . "\n";
            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
        }
    }

    private function safeSingleSelect($query_string, $params = null)
    {
        $this->errors['db_error'] = false;
        $query_parameters = $params;

        try
        {
            $this->prepared_statement = $this->db_handle->prepare($query_string);
            $this->execute_result = $this->prepared_statement->execute($query_parameters);
            $this->result = $this->prepared_statement->fetch(PDO::FETCH_ASSOC);
            $this->errors['execute-OK'] = $this->execute_result;
            return $this->result;
        }
        catch (PDOException $exception_object)
        {
            $error_message  = 'PDO Exception caught. ';
            $error_message .= 'Error with the database access.' . "\n";
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(), true) . "\n";
            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
        }
    }

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
            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
        }
        return $this->errors['db_error'];
    }

    public function getResult()
    {
        return $this->result;
    }

}