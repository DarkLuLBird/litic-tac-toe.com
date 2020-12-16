<?php

namespace application\models;

use application\core\Model;
use application\support\media\Image;
use application\support\validators\UserValidator;

class Main extends Model
{
    private $error;
    private $image;
    private $userValidator;

    public function __construct() {
        parent::__construct();

        $this->image = new Image;
        $this->userValidator = new UserValidator;
    }
    
    public function registerValidate($post)
    {
        $isValidated = $this->userValidator->registerValidate($post);
        if(!$isValidated){
            $this->error = $this->userValidator->getError();
        }
        return $isValidated;
    }

    public function loginValidate($post)
    {   
        $username = $post['username'];

        $params = [
            'username' => $username,
        ];
        $sql = "SELECT `email_confirmed` FROM users WHERE username = :username";
        $data = $this->db->select($sql, $params);

        if(intval($data['email_confirmed']) != 1){
            return false;
        }

        $isValidated = $this->userValidator->loginValidate($post);
        if(!$isValidated){
            $this->error = $this->userValidator->getError();
        }
        return $isValidated;
    }

    public function tryRegisterUser($post)
    {
        $username = $post['username'];
        $email = $post['email'];

        $password = password_hash($post['password'], PASSWORD_DEFAULT);

        $hash = md5($username . time());

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "To: <$email>\r\n";
        $headers .= "From: <mail@example.com>\r\n";

        $message = '
            <html>
            <head>
            <title>Подтвердите Email</title>
            </head>
            <body>
            <p>Что бы подтвердить Email, перейдите по <a href="http://litic-tac-toe.com/confirm?hash=' . $hash . '">ссылке</a></p>
            </body>
            </html>
        ';

        $params = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'hash' => $hash,
        ];
        $sql = "INSERT INTO `users` (`username`, `email`, `password`, `hash`) VALUES (:username, :email, :password, :hash)";
        $this->db->query($sql, $params);

        mail($email, 'Confirm your e-mail adress', $message, $headers);
    }

    public function tryLoginUser($post)
    {
        $username = $post['username'];
        $password = $post['password'];

        $params =[
            'username' => $username,
        ];
        $sql = "SELECT `password`, `id` FROM `users` WHERE `username` = :username";
        $data = $this->db->select($sql, $params);
        $data['username'] = $username;

        if(password_verify($password, $data['password'])){
            $this->authorize($data);
        }
    }

    public function tryConfirmUser($get)
    {
        $hash = $get['hash'];

        $params = [
            'hash' => $hash,
        ];
        $sql = "SELECT `id`, `email_confirmed`, `username` FROM users WHERE `hash` = :hash";
        $data = $this->db->select($sql, $params);

        if(intval($data['email_confirmed']) == 0){
            $value = 1;
            $id = $data['id'];

            $params = [
                'id' => $id,
                'value' => $value,
            ];
            $sql = "UPDATE users SET `email_confirmed` = :value WHERE `id` = :id";
            $this->db->query($sql, $params);
            $this->dropHash($hash);
        }
        $this->authorize($data);
    }

    public function forgotPassword($post)
    {
        $email = $post['email'];
        $hash = md5($email . time());

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "To: <$email>\r\n";
        $headers .= "From: <mail@example.com>\r\n";

        $message = '
            <html>
            <head>
            <title>Восстановление пароля</title>
            </head>
            <body>
            <p>Что бы восстановить пароль, перейдите по <a href="http://litic-tac-toe.com/recover?hash=' . $hash . '">ссылке</a></p>
            </body>
            </html>
        ';

        $params = [
            'email' => $email,
            'hash' => $hash,
        ];
        $sql = "UPDATE users SET `hash` = :hash WHERE `email` = :email";
        $this->db->query($sql, $params);

        mail($email, 'Password recover', $message, $headers);
    }

    public function tryRecoverPassword($post)
    {
        $hash = $_SERVER['REQUEST_URI'];
        $hash = explode('=', $hash);
        $hash = end($hash);

        $password = password_hash($post['password'], PASSWORD_DEFAULT);
        
        $params = [
            'hash' => $hash,
            'password' => $password,
        ];
        $sql = "UPDATE users SET `password` = :password WHERE `hash` = :hash";
        $this->db->query($sql, $params);

        $selectParams = [
            'hash' => $hash,
        ];
        $selectSql = "SELECT `id`, `username` FROM users WHERE `hash` = :hash";
        $data = $this->db->select($selectSql, $selectParams);

        $this->dropHash($hash);
        
        $this->authorize($data);
    }

    public function logoutUser()
    {
        $_SESSION = array();
    }

    private function authorize($data)
    {
        $_SESSION['authorize']['id'] = $data['id'];
        $_SESSION['authorize']['username'] = $data['username'];
    }

    private function dropHash($hash)
    {
        $params = [
            'hash' => $hash,
        ];
        $sql = "UPDATE users SET `hash` = NULL WHERE `hash` = :hash";
        $this->db->query($sql, $params);
    }

    public function showError()
    {
        return $this->error;
    }
}