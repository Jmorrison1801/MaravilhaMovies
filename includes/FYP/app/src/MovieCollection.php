<?php


namespace MaravilhaMovies;


use phpDocumentor\Reflection\Types\Array_;

class MovieCollection
{
    private $showtimesCollection = array();
    public $searchResults = array();
    private $favourties = array();


    public function addMovie($showtime)
    {
        array_push($this->showtimesCollection, $showtime);
    }

    public function addResult($movie)
    {
        array_push($this->searchResults, $movie);
    }

    public function addResults($collection)
    {
        $this->searchResults = array_merge($this->searchResults,$collection);
        $this->searchResults = array_unique($this->searchResults, SORT_REGULAR);
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