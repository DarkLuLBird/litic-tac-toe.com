<?php

namespace application\controllers;

use application\core\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $this->view->render('Main');
    }

    public function registerAction()
    {
        if(!empty($_POST)){
            if(!$this->model->registerValidate($_POST)){
                $this->view->message('error', $this->model->showError());
            }
            $this->model->tryRegisterUser($_POST);
            $this->view->redirect('confirm');
        }
        $this->view->render('Register');
    }

    public function loginAction()
    {
        if(!empty($_POST)){
            if(!$this->model->loginValidate($_POST)){
                $this->view->message('error', $this->model->showError());
            }
            $this->model->tryLoginUser($_POST);
            $this->view->redirect('home');
        }
        $this->view->render('Login');
    }

    public function confirmAction()
    {
        if(!empty($_GET)){
            $this->model->tryConfirmUser($_GET);
            $this->view->redirect('home');
        }

        $this->view->render('Confirming e-mail');
    }

    public function forgotAction()
    {
        if(!empty($_POST)){
            $this->model->forgotPassword($_POST);
        }
        $this->view->render('Forgot password');
    }

    public function recoverAction()
    {
        if(!empty($_POST)){
            $this->model->tryRecoverPassword($_POST);
            $this->view->redirect('home');
        }
        
        $this->view->render('Recover');
    }

    public function logoutAction()
    {
        $this->model->logoutUser();
        $this->view->redirect('');
    }

    public function homeAction()
    {
        $this->view->render('Home');
    }
}