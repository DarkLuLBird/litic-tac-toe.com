<?php

namespace application\support\validators;

class UserValidator
{
    private $error;

    public function registerValidate($post)
    {
        $usernameLength = iconv_strlen($post['username']);
        // $passwordLength = iconv_strlen($post['password']);

        // Validates username.
        if(empty($post['username'])){
            $this->error = 'Please enter your username';
            return false;
        }
        if($usernameLength < 5 or $usernameLength > 25){
            $this->error = 'Username must be 5 to 25 symbols';
            return false;
        }

        // Validates e-mail.
        if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
            $this->error = 'Wrong e-mail';
            return false;
        }

        // Validates password
        // if(empty($post['password'])){
        //     $this->error = 'Please enter your password';
        //     return false;
        // }
        // if ($passwordLength <= '8') {
        //     $this->error = 'Password must be at least 8 symbols';
        //     return false;
        // }
        // if(!preg_match("#[0-9]+#", $post['password'])) {
        //     $this->error = 'Your password must contain at least 1 number';
        //     return false;
        // }
        // if(!preg_match("#[A-Z]+#", $post['password'])) {
        //     $this->error = 'Your password must contain at least 1 capital letter';
        //     return false;
        // }
        // if(!preg_match("#[a-z]+#", $post['password'])) {
        //     $this->error = 'Your password must contain at least 1 lowercase letter';
        //     return false;
        // }
        // if($post['password'] != $post['password_repeat']){
        //     $this->error = 'Password mismatch';
        //     return false;
        // }
        $this->passwordValidate($post);

        return true;
    }

    public function passwordValidate($post){
        $passwordLength = iconv_strlen($post['password']);

        if(empty($post['password'])){
            $this->error = 'Please enter your password';
            return false;
        }
        if ($passwordLength <= '8') {
            $this->error = 'Password must be at least 8 symbols';
            return false;
        }
        if(!preg_match("#[0-9]+#", $post['password'])) {
            $this->error = 'Your password must contain at least 1 number';
            return false;
        }
        if(!preg_match("#[A-Z]+#", $post['password'])) {
            $this->error = 'Your password must contain at least 1 capital letter';
            return false;
        }
        if(!preg_match("#[a-z]+#", $post['password'])) {
            $this->error = 'Your password must contain at least 1 lowercase letter';
            return false;
        }
        if($post['password'] != $post['password_repeat']){
            $this->error = 'Password mismatch';
            return false;
        }
        
        return true;
    }
    
    public function loginValidate($post)
    {
        // Validates username.
        if(empty($post['username'])){
            $this->error = 'Please enter your username';
            return false;
        }

        // Validates password
        if(empty($post['password'])){
            $this->error = 'Please enter your password';
            return false;
        }

        return true;
    }

    public function getError()
    {
        return $this->error;
    }
}