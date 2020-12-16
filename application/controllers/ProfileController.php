<?php

namespace application\controllers;

use application\core\Controller;

class ProfileController extends Controller
{
    public function indexAction()
    {
        $args['rating'] = $this->model->getRating();
        $this->view->render('Profile', $args);
    }
}