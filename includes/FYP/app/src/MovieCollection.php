<?php


namespace MaravilhaMovies;


use phpDocumentor\Reflection\Types\Array_;

class MovieCollection
{
    private $showtimesCollection = array();
    private $searchResults = array();


    public function addMovie($showtime)
    {
        array_push($this->showtimesCollection, $showtime);
    }

    public function addResult($movie)
    {
        array_push($this->searchResults, $movie);
    }

    public function getResults()
    {
        return $this->searchResults;
    }

    public function getMovieCollection()
    {
        return $this->movieCollection;
    }
}