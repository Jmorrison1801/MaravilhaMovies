<?php


namespace MaravilhaMovies;


class MovieManager
{
    private $database_connection_settings;
    private $sql_queries;
    private $DatabaseWrapper;
    private $film_id;
    private $title;
    private $genre;
    private $director;
    private $cast;
    private $ageRating;
    private $releaseDate;
    private $min;
    private $max;
    private $location;
    private $genreCollection;
    private $castCollection;
    private $showdate;

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

    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    public function setCast($cast)
    {
        $this->cast = $cast;
    }

    public function setDirector($director)
    {
        $this->director = $director;
    }

    public function setAgeRating($ageRating)
    {
        $this->ageRating = $ageRating;
    }


    public function displaySearchResults($collection)
    {
        $result = "
<section id='movie_collection_display'>
";
        foreach ($collection as $value)
        {
            $this->genreCollection = explode(",",$value['genre']);
            $this->castCollection = explode(",",$value['cast']);


            $result .= "
<div class='movie_result'>
<h1>{$value['title']}</h1>
<form action='movieView' method='POST'>
<div class='left_container'>
                    <input class='image-search' type='image' id='image' src={$value['imageURL']}>
                     <h3>Release Date</h3>
                    <p>{$value['releaseDate']}</p>
</div>
<div class='right_container'>
<input type='hidden' name='film_id' id='film_id' readonly value={$value['film_id']}>
                    <h3>Genre</h3>
                    <nav class='tag-search'>   ";
            foreach ($this->genreCollection as $genre)
            {
                $result .= "
                <a class='tag-search' href='searchResults?genre={$genre}'>$genre</a>
                ";
            }


                    $result .= "
                    </nav class='tag-search'>
                    <h3>Cast</h3>
                    <nav class='tag-search'>";

            foreach ($this->castCollection as $cast)
            {
                $result .= "
                 <a class='tag-search' href='searchResults?cast={$cast}'>$cast</a>
                ";
            }

                    $result .= "
                    </nav class='tag-search'>
                    <h3>Director</h3>
                    <nav class='tag-search'>
                    <a class='tag-search' href='searchResults?director={$value['director']}'>{$value['director']}</a>
                    </nav class='tag-search'>
                    <h3>Age Rating</h3>
                    <nav class='tag-search'>
                    <a class='tag-search' href='searchResults?ageRating={$value['ageRating']}'>{$value['ageRating']}</a>
                    </nav>
</div>
</div>
</form>
";
        }
        $result .= "</section>";
        return $result;
    }

    public function displayMoviesSession($collection)
    {
        $result = "
<div class='slide-div-session'>
<div class='slide-container'>
<h1>Movies you May Like...</h1>
";
        foreach ($collection as $value)
        {
            $result .= "
<form class='slider_form' action='movieView' method='POST'>
<input type='image' class='image'src={$value['imageURL']} width='300' height='450'>
<input type='hidden' name='film_id' id='film_id' readonly value={$value['film_id']}>
</form>
";
        }
        $result .= "
</div>
</div>";

        return $result;
    }

    public function displayRecentMovies($collection)
    {
        $result = "
<div class='slide-div-recent'>
<div class='slide-container'>
<h1>Recent Releases...</h1>
";
        foreach ($collection as $value)
        {
            $result .= "
<form class='slider_form' action='movieView' method='POST'>
<input type='image' class='image'src={$value['imageURL']} width='300' height='450'>
<input type='hidden' name='film_id' id='film_id' readonly value={$value['film_id']}>
</form>
";
        }
        $result .= "
</div>
</div>";

        return $result;
    }


    public function displayMovies($collection)
    {
        $result = "
<section id='movie_collection_display'>
        ";
        foreach ($collection as $value)
        {
            $result .= "
<h1>{$value['title']}</h1>
<form action='movieView' method='POST'>
<input type='image' class='image'src={$value['imageURL']} width='300' height='450'>
<input type='hidden' name='film_id' id='film_id' readonly value={$value['film_id']}>

</form>
";
        }

        $result .= "
        </section>";
        return $result;
    }


    public function sessionMovies($app)
    {
        $session_wrapper = $app->getContainer()->get('SessionWrapper');
        $values = $session_wrapper->getSessionVar('movie');
        $movieCollection = $app->getContainer()->get('MovieCollection');
        foreach ($values as $id)
        {
            $result = $this->searchId($app, $id);

            $movies = $this->searchGenre($app, $result['genre']);
            if (sizeof($movies) < 6)
            {
                $genre = explode(",",$result['genre']);
                foreach ($genre as $value)
                {
                    $movies = $this->searchGenre($app, $value);
                    $movieCollection->addResults($movies);
                    $results = $movieCollection->getResults();
                }
            }
        }
        $movieResults = $this->displayMoviesSession($results);
        return $movieResults;
    }

    public function displayFavourites($collection)
    {
        $result = "
<section id='favourites_collection_display'>
";
        foreach ($collection as $value)
        {
            $this->genreCollection = explode(",",$value['genre']);
            $this->castCollection = explode(",",$value['cast']);


            $result .= "
<div class='movie_result'>
<h1>{$value['title']}</h1>
<form action='movieView' method='POST'>
<div class='left_container'>
                    <input class='image-search' type='image' id='image' src={$value['imageURL']}>
                     <h3>Release Date</h3>
                    <p>{$value['releaseDate']}</p>
                    <h1>{$value[0]}</h1>
                    <nav class=''>
                        <a class='tag-search' href='favourites?favourite={$value['film_id']}'>Remove From Favourites</a>
                    </nav>
</div>
<div class='right_container'>
                    <h3>Genre</h3>
                    <nav class='tag-search'>   ";
            foreach ($this->genreCollection as $genre)
            {
                $result .= "
                <a class='tag-search' href='searchResults?genre={$genre}'>$genre</a>
                ";
            }


            $result .= "
                    </nav class='tag-search'>
                    <h3>Cast</h3>
                    <nav class='tag-search'>";

            foreach ($this->castCollection as $cast)
            {
                $result .= "
                 <a class='tag-search' href='searchResults?cast={$cast}'>$cast</a>
                ";
            }

            $result .= "
                    </nav class='tag-search'>
                    <h3>Director</h3>
                    <nav class='tag-search'>
                    <a class='tag-search' href='searchResults?director={$value['director']}'>{$value['director']}</a>
                    </nav class='tag-search'>
                    <h3>Age Rating</h3>
                    <nav class='tag-search'>
                    <a class='tag-search' href='searchResults?ageRating={$value['ageRating']}'>{$value['ageRating']}</a>
                    </nav>
</div>
</div>
</form>
";
        }
        $result .= "</section>";
        return $result;
    }

    public function getFavourites($app, $email)
    {
        $movieCollection = $app->getContainer()->get('MovieCollection');

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
        $account = $this->DatabaseWrapper->getResult();

        $favourites = explode(',',$account['favourites']);

        foreach ($favourites as $id)
        {
            $movies = $this->searchId($app, $id);

            $now = date("Y-m-d");
            $movieRelease = $movies['releaseDate'];

            $date1 = date_create($now);
            $date2 = date_create($movieRelease);

            $diff=date_diff($date1,$date2);

            $diff = $diff->format('%R%a days');

            $days = '';

            if ($diff < 0)
            {
                $days = 'Currently Showing In Cinemas';
            } else {
                $days = 'Days till release: '.$diff;
            }

            array_push($movies,$days);
            $movieCollection->addResult($movies);
            $results = $movieCollection->getResults();
        }
        $movieResults = $this->displayFavourites($results);

        return $movieResults;
    }

    public function recentReleases($app)
    {
        $movieCollection = $app->getContainer()->get('MovieCollection');
        $this->releaseDate = date("Y-m-d");
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectRecentReleases();

        $results = $database_wrapper->getResult();
        $movieCollection->addResults($results);

        $movieResults = $this->displayRecentMovies($results);
        return $movieResults;
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

        $rough_result = $database_wrapper->getResult();
        $movieResults = $this->displaySearchResults($rough_result);
        return $movieResults;
    }


    /***************************
          Search Functions
     ***************************/

    public function searchGenre($app, $genre)
    {
        $this->genre = "%".$genre."%";
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectMovieGenre();

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function searchCast($app, $cast)
    {
        $this->cast = "%".$cast."%";
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectMovieCast();

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function searchDirector($app, $director)
    {
        $this->director = "%".$director."%";
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectMovieDirector();

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function searchAgeRating($app, $ageRating)
    {
        $this->ageRating = $ageRating;
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectMovieAgeRating();

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function searchTitle($app, $title)
    {

        $this->setTitle($title);
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectMovie();

        $rough_result = $database_wrapper->getResult();

        $movieResults = $this->displaySearchResults($rough_result);

        return $movieResults;
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

    public function getAllShowdates($app,$film_id,$location)
    {
        $this->film_id = $film_id;
        $this->location = $location;
        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectAllShowdates();

        $result = $database_wrapper->getResult();

        return $result;
    }

    public function getShowtimes($app, $location, $film_id, $showdate)
    {
        $this->location = $location;
        $this->film_id = $film_id;
        $this->showdate = $showdate;

        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        $this->selectShowtimes();

        $result = $database_wrapper->getResult();

        return $result;
    }


    /***************************
           ADVANCED SEARCH
     ***************************/
    public function advanceSearch($app, $cleaned_param)
    {
        $this->director = "%".$cleaned_param['director']."%";
        $this->cast = "%".$cleaned_param['cast']."%";
        $this->ageRating = $cleaned_param['ageRating'];
        $this->genre = "%".$cleaned_param['genre']."%";
        $this->min = $cleaned_param['min-date'];
        $this->max = $cleaned_param['max-date'];
        $movieCollection = $app->getContainer()->get('MovieCollection');

        $query = $app->getContainer()->get('SQLQueries');
        $database_wrapper = $app->getContainer()->get('DatabaseWrapper');

        $db_conf = $app->getContainer()->get('settings');
        $database_connection_settings = $db_conf['pdo_settings'];


        $this->setDatabaseWrapper($database_wrapper);
        $this->setSQLQueries($query);
        $this->setDatabaseConnectionSettings($database_connection_settings);

        if ($this->genre == '%Any%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '' && $this->director == '%%' && $this->cast == '%%')
        {
            $this->selectAllMovies();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '' && $this->director == '%%' && $this->cast == '%%')
        {
            $this->selectMovieGenre();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre == '%Any%' && $this->ageRating != 'Any' && $this->min == '' && $this->max == '' && $this->director == '%%' && $this->cast == '%%')
        {
            $this->selectMovieAgeRating();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre == '%Any%' && $this->ageRating == 'Any' && $this->min != '' && $this->max != '' && $this->director == '%%' && $this->cast == '%%')
        {
            $this->selectMovieDate();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre == '%Any%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '' && $this->director != '%%' && $this->cast == '%%')
        {
            $this->selectMovieDirector();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre == '%Any%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '' && $this->director == '%%' && $this->cast != '%%')
        {
            $this->selectMovieCast();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min == '' && $this->max == '' && $this->director == '%%' && $this->cast == '%%')
        {
            $this->selectMovieGA();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->min != '' && $this->max != '' && $this->ageRating == 'Any' && $this->director == '%%' && $this->cast == '%%')
        {
            $this->selectMovieGR();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->director != '%%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '' && $this->cast == '%%')
        {
            $this->selectMovieGD();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->cast != '%%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '' && $this->director == '%%')
        {
            $this->selectMovieGC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->genre == '%Any%' && $this->director == '%%' && $this->cast == '%%')
        {
            $this->selectMovieAR();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->director != '%%' && $this->genre == '%Any%' && $this->min == '' && $this->max == '' && $this->cast == '%%')
        {
            $this->selectMovieAD();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->cast != '%%' && $this->genre == '%Any%' && $this->min == '' && $this->max == '' && $this->director == '%%')
        {
            $this->selectMovieAC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->min != '' && $this->max != '' && $this->director != '%%' && $this->genre == '%Any%' && $this->ageRating == 'Any' && $this->cast == '%%')
        {
            $this->selectMovieRD();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->min != '' && $this->max != '' && $this->cast != '%%' && $this->genre == '%Any%' && $this->ageRating == 'Any' && $this->director == '%%')
        {
            $this->selectMovieRC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->director != '%%' && $this->cast != '%%' && $this->genre == '%Any%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '')
        {
            $this->selectMovieDC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min != '' && $this->max != ''&& $this->director == '%%' && $this->cast == '%%')
        {
            $this->selectMovieGAR();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->director != '%%' && $this->min == '' && $this->max == '' && $this->cast == '%%')
        {
            $this->selectMovieGAD();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }


        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->cast != '%%' && $this->min == '' && $this->max == '' && $this->director == '%%')
        {
            $this->selectMovieGAC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->ageRating == 'Any' && $this->cast == '%%')
        {
            $this->selectMovieGRD();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->min != '' && $this->max != '' && $this->cast != '%%' && $this->ageRating == 'Any' && $this->director == '%%')
        {
            $this->selectMovieGRC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->director != '%%' && $this->cast != '%%' && $this->ageRating == 'Any' && $this->min == '' && $this->max == '%%')
        {
            $this->selectMovieGDC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->genre == '%Any%' && $this->cast == '%%')
        {
            $this->selectMovieARD();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->cast != '%%' && $this->genre == '%Any%' && $this->director == '%%')
        {
            $this->selectMovieARC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->director != '%%' && $this->cast != '%%' && $this->genre == '%Any%' && $this->min == '' && $this->max == '%%')
        {
            $this->selectMovieADC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast != '%%' && $this->genre == '%Any%' && $this->ageRating == 'Any')
        {
            $this->selectMovieRDC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast == '%%')
        {
            $this->selectMovieGARD();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->cast != '%%' && $this->director == '%%')
        {
            $this->selectMovieGARC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast != '%%' && $this->ageRating == 'Any')
        {
            $this->selectMovieGRDC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast != '%%' && $this->genre == '%Any%')
        {
            $this->selectMovieARDC();
            $result = $database_wrapper->getResult();
            foreach ($result as $value)
            {
                $movieCollection->addResult($value);
            }
        }

        if ($this->genre != '%Any%' && $this->ageRating != 'Any' && $this->min != '' && $this->max != '' && $this->director != '%%' && $this->cast != '%%')
        {
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

    public function selectRecentReleases()
    {
        $releaseDate = $this->releaseDate;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectRecentRelease($releaseDate);
    }

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

    public function selectMovieDate()
    {
        $min = $this->min;
        $max = $this->max;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieReleaseDate($min, $max);
    }

    public function selectMovieCast()
    {
        $cast = $this->cast;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieCast($cast);
    }

    public function selectMovieDirector()
    {
        $director = $this->director;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieDirector($director);
    }

    public function selectMovieAgeRating()
    {
        $ageRating = $this->ageRating;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieAgeRating($ageRating);
    }

    public function selectMovieGenre()
    {
        $genre = $this->genre;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectMovieGenre($genre);
    }

    public function selectDistinctLocation()
    {
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectDistinctLocation();
    }

    public function selectAllShowdates()
    {
        $film_id = $this->film_id;
        $location = $this->location;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectAllShowdates($film_id,$location);
    }


    public function selectShowtimes()
    {
        $film_id = $this->film_id;
        $location = $this->location;
        $showdate = $this->showdate;
        $this->DatabaseWrapper->setSqlQueries($this->sql_queries);
        $this->DatabaseWrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->DatabaseWrapper->makeDatabaseConnection();
        $this->DatabaseWrapper->selectShowtimes($film_id, $location, $showdate);
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