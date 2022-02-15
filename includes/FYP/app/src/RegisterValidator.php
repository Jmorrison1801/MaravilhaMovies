<?php

namespace MaravilhaMovies;

class RegisterValidator
{
    private $emailchk;
    private $passwordchk;
    private $email;
    private $password;

    public function __construct()
    {
        $this->emailchk=null;
        $this->passwordchk=null;
    }
    public function __destruct() {}

    public function validateEmailAddress($email_to_check){
        $email_to_check = filter_var($email_to_check, FILTER_SANITIZE_EMAIL);
        $this->email = $email_to_check;
        if(filter_var($email_to_check, FILTER_VALIDATE_EMAIL)){
            $this->emailchk = true;
        }else{
            $this->emailchk = false;
        }
        return $this->emailchk;
    }

    public function validatePassword($password_to_check, $password_to_check_2){
        $password_to_check = filter_var($password_to_check, FILTER_SANITIZE_STRING);
        $password_to_check_2 = filter_var($password_to_check_2, FILTER_SANITIZE_STRING);
        $this->password = $password_to_check;
        if(strlen($password_to_check) >= 6 ){
            if($password_to_check == $password_to_check_2){
                $this->passwordchk = true;
            }
        }
        return $this->passwordchk;
    }

    public function getEmailchk(){
        return $this->emailchk;
    }

    public function getPasswordchk(){
        return $this->passwordchk;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }
}