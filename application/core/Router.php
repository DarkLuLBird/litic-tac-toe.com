<?php

namespace application\core;

class Router
{
    private $routes = [];
    private $params = [];

    public function __construct()
    {
        $routes = require('application/config/routes.php');
        foreach($routes as $key => $value){
            $this->add($key, $value);
        }
    }

    private function add($route, $params)
    {
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    private function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $url = preg_replace('/^([^?]+)(\?.*?)?(#.*)?$/', '$1$3', $url);
        foreach($this->routes as $route => $params){
            if(preg_match($route, $url, $matches)){
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
    
    public function run()
    {
        if($this->match()){
            $controllerPath = 'application\\controllers\\' . ucfirst($this->params['controller']) . 'Controller';
            if(class_exists($controllerPath)){
                $action = $this->params['action'] . 'Action';
                if(method_exists($controllerPath, $action)){
                    $controller = new $controllerPath($this->params);
                    $controller->$action();
                }else{
                    View::errorCode(404);
                }
            }else{
                View::errorCode(404);
            }
        }else{
            View::errorCode(404);
        }
    }
}