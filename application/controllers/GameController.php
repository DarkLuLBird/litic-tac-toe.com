<?php

namespace application\controllers;

use application\core\Controller;

class GameController extends Controller
{
    public function indexAction()
    {
        $this->view->render('Game');
    }

    public function createAction()
    {
        if(!empty($_POST)){
            $url = 'play/game?hash=' . $this->model->createGame();
            $this->view->redirect($url);
        }
        $this->view->render('Create game');
    }

    public function joinAction()
    {
        if(!empty($_POST)){
            $hash = $this->model->joinGame($_POST);
            if(!empty($hash)){
                $url = 'play/game?hash=' . $hash;
                $this->view->redirect($url);
            }else{
                $this->view->message('error', 'You cant connect this game');
            }
        }
        $this->view->render('Join');
        
    }

    public function gameAction()
    {
        $args['opponent'] = $this->model->getOpponent($_GET);
        
        $args['field'] = $this->model->getField($_GET['hash']);
        
        $this->view->render('Game', $args);
    }
    
    public function turnAction()
    {   
        $this->model->turn($_POST);

        $this->view->render('turn');
    }
}