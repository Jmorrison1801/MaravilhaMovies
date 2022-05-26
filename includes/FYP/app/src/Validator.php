<?php

namespace MaravilhaMovies;

class Validator
{
    private $cleanedEmail;
    private $cleanedPassword;
    private $cleanedLoginDetails;
    private $value;
    private $cleaned_adv_search;

    public function __construct() { }

    public function __destruct() { }

    public function validateLoginDetails($email, $password)
    {
        $this->cleanedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        $this->cleanedPassword = filter_var($password, FILTER_SANITIZE_STRING);
        $this->cleanedLoginDetails = [
            'Enter_Email_Address' => $this->cleanedEmail,
            'Enter_Password' => $this->cleanedPassword
        ];
        return $this->cleanedLoginDetails;
    }

    public function validateString($value)
    {
        $this->value = filter_var($value, FILTER_SANITIZE_STRING);
        return $this->value;
    }

    public function validateAdvSearch($tainted_param)
    {
        $cleaned_genre = filter_var($tainted_param['genre'],FILTER_SANITIZE_STRING);
        $cleaned_cast = filter_var($tainted_param['cast'],FILTER_SANITIZE_STRING);
        $cleaned_director = filter_var($tainted_param['director'],FILTER_SANITIZE_STRING);
        $cleaned_min = $tainted_param['min-date'];
        $cleaned_max = $tainted_param['max-date'];
        $cleaned_age_rating = $tainted_param['ageRating'];



        $this->cleaned_adv_search = [
            'genre' => $cleaned_genre,
            'min-date' => $cleaned_min,
            'max-date' => $cleaned_max,
            'cast' => $cleaned_cast,
            'director' => $cleaned_director,
            'ageRating' => $cleaned_age_rating,
        ];

        return $this->cleaned_adv_search;
    }


}