<?php


namespace MaravilhaMovies;


class SearchValidator
{

    public function validateSearchTitle($tainted_param)
    {
        $clean_title = filter_var($tainted_param[''],FILTER_SANITIZE_STRING);

        return $clean_title;
    }

    public function validateSearchCast($tainted_param)
    {
        $clean_cast = filter_var($tainted_param[''],FILTER_SANITIZE_STRING);

        return $clean_cast;
    }

    public function validateSearchDirector($tainted_param)
    {
        $clean_director = filter_var($tainted_param[''],FILTER_SANITIZE_STRING);

        return $clean_director;
    }
}