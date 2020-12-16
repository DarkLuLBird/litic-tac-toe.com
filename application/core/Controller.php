<?php

namespace application\core;

use application\core\View;

abstract class Controller
{
    protected $route;
    protected $view;
    protected $model;
    protected $acl;

    public function __construct($route)
    {
        $this->route = $route;
        $this->acl = $this->loadAcl();
        $this->view = new View($route);
        $this->model = $this->loadModel($route['controller']);
    }

    public function loadModel($name)
    {
        $path = 'application\\models\\' . ucfirst($name);
        if(class_exists($path)){
            return new $path;
        }
    }

    public function loadAcl()
    {
        $path = 'application\\acl\\' . $this->route['controller'] . '.php';
        if(file_exists($path)){
            $this->acl = require $path;
            if(!$this->checkAcl()){
                View::errorCode(403);
            }
        }else{
            exit('Не найден файл распределения доступа');
        }
    }

    private function checkAcl()
    {
        if($this->isAcl('all')){
            return true;
        }
        elseif(isset($_SESSION['authorize']['id']) && $this->isAcl('authorize')){
            return true;
        }
        elseif(!isset($_SESSION['authorize']['id']) && $this->isAcl('guest')){
            return true;
        }
        elseif(isset($_SESSION['admin']) && $this->isAcl('admin')){
            return true;
        }

        return false;
    }

    private function isAcl($key)
    {  
        return in_array($this->route['action'], $this->acl[$key]);
    }
}