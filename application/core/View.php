<?php

namespace application\core;

class View
{
    protected $path;
    protected $route;
    protected $layout = 'default';

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = 'application/views/' . $route['controller'] . '/' . $route['action'] . '.php';
    }

    public function render($title = 'Title', $args = [])
    {
        extract($args);
        if(file_exists($this->path)){
            ob_start();
            require $this->path;
            $content = ob_get_clean();
            require 'application/views/layouts/' . $this->layout . '.php';
        }else{
            View::errorCode(404);
        }
    }

    public static function errorCode($code)
    {
        http_response_code($code);
        require 'application/views/errors/' . $code . '.php';
        exit;
    }

    public function redirect($url)
    {
        header('location: /' . $url);
        exit;
    }

    public function message($status, $message) {
		exit(json_encode(['status' => $status, 'message' => $message]));
    }
    
    public function setLayout($layout){
        $this->layout = $layout;
    }
}