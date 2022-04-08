<?php


namespace MaravilhaMovies;


class MovieManager
{
    private $database_connection_settings;
    private $sql_queries;
    private $DatabaseWrapper;
    private $film_id;
    private $title;
    private $description;
    private $genre;
    private $director;
    private $cast;
    private $ageRating;
    private $releaseDate;
    private $cinemas;
    private $metaphone;
    private $min;
    private $max;
    private $location;

    public function setMovie($film_id, $title, $description, $genre,
                             $director, $cast, $ageRating, $releaseDate,
                             $cinemas, $metaphone)
    {
        $this->film_id = $film_id;
        $this->title = $title;
        $this->description = $description;
        $this->genre = $genre;
        $this->director = $director;
        $this->cast = $cast;
        $this->ageRating = $ageRating;
        $this->releaseDate = $releaseDate;
        $this->cinemas = $cinemas;
        $this->metaphone = $metaphone;
    }

    /***************************
          Search Functions
     ***************************/


    public function searchTitle($app, $tainted_param)
    {

        $this->setTitle($tainted_param['search_movie']);
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectMovie();

        $rough_result = $database_wrapper->getResult();

        $result = "";

        foreach ($rough_result as $value)
        {

            $result .= "
<fieldset id='search_results'>
<form action='movieView' method='POST'>
<h1>{$value['title']}</h1>
<input type='text' name='film_id' id='film_id' readonly value={$value['film_id']}>
<br>
<img src={$value['imageURL']} width='500' height='600'>
<br>
<input id='view_btn' type='submit' value='View'>
</form>
</fieldset>";

        }

        return $result;
    }

    public function searchId($app, $film_id)
    {
        $this->setFilmId($film_id);
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectMovieId();

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function getDistinctLocations($app)
    {
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectDistinctLocation();

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function getShowtimes($app, $location, $film_id)
    {
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectShowtimes($location, $film_id);

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function getAllMovies($app)
    {
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectAllMovies();

        $result = $database_wrapper->getResult();

        return $result;
    }

    /***************************
           ADVANCED SEARCH
     ***************************/
    public function advanceSearch($app, $tainted_param)
    {
        $this->director = "%".$tainted_param['director']."%";
        $this->cast = "%".$tainted_param['cast']."%";
        $this->ageRating = $tainted_param['ageRating'];
        $this->genre = "%".$tainted_param['genre']."%";
        $this->min = $tainted_param['min-date'];
        $this->max = $tainted_param['max-date'];
        $movieCollection = $app->getContainer()->get('MovieCollection');

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min == '' && $this->max == '' && $this->director == '%%' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGA();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->min != '' && $this->max != '' && $this->ageRating == 'Any' && $this->director == '%%' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGR();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->director != '%%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGD();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->cast != '%%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '' && $this->director == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->genre == '%Any%' && $this->director == '%%' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieAR();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->director != '%%' && $this->genre == '%Any%' && $this->min == '' && $this->max == '' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieAD();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->cast != '%%' && $this->genre == '%Any%' && $this->min == '' && $this->max == '' && $this->director == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieAC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->min != '' && $this->max != '' && $this->director != '%%' && $this->genre == '%Any%' && $this->ageRating == 'Any' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieRD();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->min != '' && $this->max != '' && $this->cast != '%%' && $this->genre == '%Any%' && $this->ageRating == 'Any' && $this->director == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieRC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->director != '%%' && $this->cast != '%%' && $this->genre == '%Any%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieDC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min != '' && $this->max != ''&& $this->director == '%%' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGAR();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->director != '%%' && $this->min == '' && $this->max == '' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGAD();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }


        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->cast != '%%' && $this->min == '' && $this->max == '' && $this->director == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGAC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->ageRating == 'Any' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGRD();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->min != '' && $this->max != '' && $this->cast != '%%' && $this->ageRating == 'Any' && $this->director == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGRC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->director != '%%' && $this->cast != '%%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGDC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->genre == '%Any%' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieARD();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->cast != '%%' && $this->genre == '%Any%' && $this->director == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieARC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->director != '%%' && $this->cast != '%%' && $this->genre == '%Any%' && $this->min == '' && $this->max == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieADC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast != '%%' && $this->genre == '%Any%' && $this->ageRating == 'Any')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieRDC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGARD();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->cast != '%%' && $this->director == '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGARC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast != '%%' && $this->ageRating == 'Any')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGRDC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast != '%%' && $this->genre == '%Any%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieARDC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast != '%%')
        {
            $query = $app->getContainer()->get('SQLQueries');
            $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

            $db_conf = $app->getContainer()->get('settings');
            $database_connection_settings = $db_conf['pdo_settings'];


            $this->setDatabaseWrapper($database_wrapper);
            $this->setSQLQueries($query);
            $this->setDatabaseConnectionSettings($database_connection_settings);

            $this->selectMovieGARDC();

            $result = $database_wrapper->getResult();

            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }








        return $movieCollection->getResults();
    }



    /***************************
        Database Functions
     ***************************/
    public function selectMovieGA()
    {
        $genre = $this->genre;
        $ageRating = $this->ageRating;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGA($genre,$ageRating);
    }

    public function selectMovieGR()
    {
        $genre = $this->genre;
        $min = $this->min;
        $max = $this->max;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGR($genre,$min,$max);
    }

    public function selectMovieGD()
    {
        $genre = $this->genre;
        $director = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGD($genre,$director);
    }

    public function selectMovieGC()
    {
        $genre = $this->genre;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGC($genre,$cast);
    }

    public function selectMovieAR()
    {
        $ageRating = $this->ageRating;
        $min = $this->min;
        $max = $this->max;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieAR($ageRating,$min,$max);
    }

    public function selectMovieAD()
    {
        $ageRating = $this->ageRating;
        $director = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieAD($ageRating,$director);
    }

    public function selectMovieAC()
    {
        $ageRating = $this->ageRating;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieAC($ageRating,$cast);
    }

    public function selectMovieRD()
    {
        $min = $this->min;
        $max = $this->max;
        $director = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieRD($min,$max,$director);
    }

    public function selectMovieRC()
    {
        $min = $this->min;
        $max = $this->max;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieRC($min,$max,$cast);
    }

    public function selectMovieDC()
    {
        $director = $this->director;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieDC($director,$cast);
    }

    public function selectMovieGAR()
    {
        $genre = $this->genre;
        $ageRating = $this->ageRating;
        $min = $this->min;
        $max = $this->max;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGAR($genre,$ageRating,$min,$max);
    }

    public function selectMovieGAD()
    {
        $genre = $this->genre;
        $ageRating = $this->ageRating;
        $director = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGAD($genre,$ageRating,$director);
    }

    public function selectMovieGAC()
    {
        $genre = $this->genre;
        $ageRating = $this->ageRating;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGAC($genre,$ageRating,$cast);
    }

    public function selectMovieGRD()
    {
        $genre = $this->genre;
        $min = $this->min;
        $max = $this->max;
        $director = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGRD($genre,$min,$max);
    }

    public function selectMovieGRC()
    {
        $genre = $this->genre;
        $min = $this->min;
        $max = $this->max;
        $cast = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGRC($genre,$min,$max,$cast);
    }

    public function selectMovieGDC()
    {
        $genre = $this->genre;
        $director = $this->director;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGDC($genre,$director,$cast);
    }

    public function selectMovieARD()
    {
        $ageRating = $this->ageRating;
        $min = $this->min;
        $max = $this->max;
        $director = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieARD($ageRating,$min,$max,$director);
    }

    public function selectMovieARC()
    {
        $ageRating = $this->ageRating;
        $min = $this->min;
        $max = $this->max;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieARC($ageRating,$min,$max,$cast);
    }

    public function selectMovieADC()
    {
        $ageRating = $this->ageRating;
        $director = $this->director;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieADC($ageRating,$director,$cast);
    }

    public function selectMovieRDC()
    {
        $min = $this->min;
        $max = $this->max;
        $director = $this->director;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieRDC($min,$max,$director,$cast);
    }

    public function selectMovieGARD()
    {
        $genre = $this->genre;
        $ageRating = $this->ageRating;
        $min = $this->min;
        $max = $this->max;
        $director = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGARD($genre,$ageRating,$min,$max,$director);
    }

    public function selectMovieGARC()
    {
        $genre = $this->genre;
        $ageRating = $this->ageRating;
        $min = $this->min;
        $max = $this->max;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGARC($genre,$ageRating,$min,$max,$cast);
    }

    public function selectMovieGRDC()
    {
        $genre = $this->genre;
        $min = $this->min;
        $max = $this->max;
        $director = $this->director;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGRDC($genre,$min,$max,$director,$cast);
    }

    public function selectMovieARDC()
    {
        $ageRating = $this->ageRating;
        $min = $this->min;
        $max = $this->max;
        $director = $this->director;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieARDC($ageRating,$min,$max,$director,$cast);
    }

    public function selectMovieGARDC()
    {
        $genre = $this->genre;
        $ageRating = $this->ageRating;
        $min = $this->min;
        $max = $this->max;
        $director = $this->director;
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGARDC($genre,$ageRating,$min,$max,$director,$cast);
    }





    public function selectMovie()
    {
        $title = $this->title;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovie($title);
    }

    public function selectAllMovies()
    {
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectAllMovies();
    }

    public function selectMovieId()
    {
        $film_id = $this->film_id;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieId($film_id);
    }

    public function selectMovieDate($min, $max)
    {
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieReleaseDate($min, $max);
    }

    public function selectMovieCast($cast)
    {
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieCast($cast);
    }

    public function selectMovieDirector($director)
    {
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieDirector($director);
    }

    public function selectMovieAgeRating($ageRating)
    {
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieAgeRating($ageRating);
    }

    public function selectMovieGenre($genre)
    {
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGenre($genre);
    }

    public function selectDistinctLocation()
    {
        $title = $this->title;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectDistinctLocation();

    }

    public function selectShowtimes($location, $film_id)
    {
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectShowtimes($film_id, $location);
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

    public function setTitle($title){
        $this->title = $title;
    }

    public function setFilmId($film_id)
    {
       $this->film_id = $film_id;
    }


}