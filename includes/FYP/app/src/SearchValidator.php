<?php


namespace MaravilhaMovies;


class SearchValidator
{

    public function validateSearchTitle($cleaned_param)
    {
        $clean_title = filter_var($cleaned_param[''],FILTER_SANITIZE_STRING);

        return $clean_title;
    }

    public function validateSearchCast($cleaned_param)
    {
        $clean_cast = filter_var($cleaned_param[''],FILTER_SANITIZE_STRING);

        return $clean_cast;
    }

    public function validateSearchDirector($cleaned_param)
    {
        $clean_director = filter_var($cleaned_param[''],FILTER_SANITIZE_STRING);

        return $clean_director;
    }
}