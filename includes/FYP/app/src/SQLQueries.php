<?php

namespace MaravilhaMovies;

class SQLQueries
{
    public function __construct(){}

    public function __destruct(){}

    /***************************
     Account Database Management
     ***************************/

    public static function insertAccountVar()
    {
        $query_string  = "INSERT INTO customer_tbl ";
        $query_string .= "SET email = :email, ";
        $query_string .= "password = :password ";

        return $query_string;
    }

    public static function insertFavouriteVar()
    {
        $query_string  = "INSERT INTO customer_tbl ";
        $query_string .= "SET favourites = :favourites, ";

        return $query_string;
    }

    public static function selectAccountVar()
    {
        $query_string = "SELECT * FROM customer_tbl ";
        $query_string .= "WHERE email = :email";

        return $query_string;
    }


    public static function deleteAccountVar()
    {
        $query_string = "DELETE FROM customer_tbl ";
        $query_string .= "WHERE AccountID = :AccountID";

        return $query_string;
    }

    public static function updateViewedMoviesVar()
    {
        $query_string = "UPDATE customer_tbl ";
        $query_string .= "SET recentlyViewed = :recentlyViewed ";
        $query_string .= "WHERE email = :email";

        return $query_string;
    }

    public static function updateFavouritesVar()
    {
        $query_string = "UPDATE customer_tbl ";
        $query_string .= "SET favourites = :favourites ";
        $query_string .= "WHERE email = :email";

        return $query_string;
    }

    /***************************
    Movie Database Management
     ***************************/

    public static function selectMovieVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE title LIKE :title";

        return $query_string;
    }

    public static function selectMovieIdVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE film_id = :film_id";

        return $query_string;
    }

    public static function selectRecentReleaseVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE releaseDate < :releaseDate";

        return $query_string;
    }


    /***************************
                 G
     ***************************/
    public static function selectMovieGenreVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre";

        return $query_string;
    }

    /***************************
                 D
     ***************************/
    public static function selectMovieDirectorVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE director LIKE :director";

        return $query_string;
    }

    /***************************
                C
     ***************************/
    public static function selectMovieCastVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE cast LIKE :cast";

        return $query_string;
    }
    /***************************
                R
     ***************************/
    public static function selectMovieDateVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE releaseDate > :min AND ";
        $query_string .= "releaseDate <= :max";

        return $query_string;
    }

    /***************************
                A
     ***************************/
    public static function selectMovieAgeVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE ageRating = :ageRating";

        return $query_string;
    }


    public static function selectGAVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND ageRating = :ageRating";

        return $query_string;
    }

    public static function selectGRVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND releaseDate > :min AND releaseDate < :max";

        return $query_string;
    }

    public static function selectGDVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND director LIKE :director";

        return $query_string;
    }

    public static function selectGCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectARVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE ageRating = :ageRating AND releaseDate > :min AND releaseDate < :max";

        return $query_string;
    }

    public static function selectADVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE ageRating = :ageRating AND director LIKE :director";

        return $query_string;
    }

    public static function selectACVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE ageRating = :ageRating AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectRDVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE releaseDate > :min AND releaseDate < :max AND director LIKE :director";

        return $query_string;
    }

    public static function selectRCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE releaseDate > :min AND releaseDate < :max AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectDCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE director LIKE :director AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectGARVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND ageRating = :ageRating AND releaseDate > :min AND releaseDate < :max";

        return $query_string;
    }

    public static function selectGADVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND ageRating = :ageRating AND director LIKE :director";

        return $query_string;
    }

    public static function selectGACVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND ageRating = :ageRating AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectGRDVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND releaseDate > :min AND releaseDate < :max AND director LIKE :director";

        return $query_string;
    }

    public static function selectGRCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND releaseDate > :min AND releaseDate < :max AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectGDCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND director LIKE :director AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectARDVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE ageRating = :ageRating AND releaseDate > :min AND releaseDate < :max AND director LIKE :director";

        return $query_string;
    }

    public static function selectARCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE ageRating = :ageRating AND releaseDate > :min AND releaseDate < :max AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectADCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE ageRating = :ageRating AND director LIKE :director AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectRDCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE releaseDate > :min AND releaseDate < :max AND director LIKE :director AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectGARDVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND ageRating = :ageRating AND releaseDate > :min AND releaseDate < :max AND director LIKE :director";

        return $query_string;
    }

    public static function selectGARCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND ageRating = :ageRating AND releaseDate > :min AND releaseDate < :max AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectGRDCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND releaseDate > :min AND releaseDate < :max AND director LIKE :director AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectARDCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE ageRating = :ageRating AND releaseDate > :min AND releaseDate < :max AND director LIKE :director AND cast LIKE :cast";

        return $query_string;
    }

    public static function selectGARDCVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "WHERE genre LIKE :genre AND ageRating = :ageRating AND releaseDate > :min AND releaseDate < :max AND director LIKE :director AND cast LIKE :cast";

        return $query_string;
    }

    /*******************
            misc
     *******************/
    public static function getDistinctVar()
    {
        $query_string = "SELECT DISTINCT location ";
        $query_string .= "FROM screening_tbl";

        return $query_string;
    }

    public static function getDistinctDatesVar()
    {
        $query_string = "SELECT DISTINCT showdate ";
        $query_string .= "FROM screening_tbl";

        return $query_string;
    }

    public static function getAllShowdatesVar()
    {
        $query_string = "SELECT * FROM screening_tbl ";
        $query_string .= "WHERE film_id = :film_id AND location = :location";


        return $query_string;
    }

    public static function getShowtimesVar()
    {
        $query_string = "SELECT * FROM screening_tbl ";
        $query_string .= "WHERE film_id = :film_id AND location = :location AND showdate = :showdate";


        return $query_string;
    }

    public static function selectAllMoviesVar()
    {
        $query_string = "SELECT * FROM films_tbl ";
        $query_string .= "ORDER BY releaseDate DESC";

        return $query_string;
    }



}