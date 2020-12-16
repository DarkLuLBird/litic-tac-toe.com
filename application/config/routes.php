<?php

return [
    // MainController.
    '' => [
        'controller' => 'main',
        'action' => 'index',
    ],

    'login' => [
        'controller' => 'main',
        'action' => 'login',
    ],

    'register' => [
        'controller' => 'main',
        'action' => 'register',
    ],

    'confirm' => [
        'controller' => 'main',
        'action' => 'confirm'
    ],

    'forgot' => [
        'controller' => 'main',
        'action' => 'forgot',
    ],

    'recover' => [
        'controller' => 'main',
        'action' => 'recover',
    ],

    'logout' => [
        'controller' => 'main',
        'action' => 'logout',
    ],

    'home' => [
        'controller' => 'main',
        'action' => 'home',
    ],

    // ProfileController.
    'profile' => [
        'controller' => 'profile',
        'action' => 'index',
    ],

    // GameController.
    'play' => [
        'controller' => 'game',
        'action' => 'index',
    ],
    
    'play/create' => [
        'controller' => 'game',
        'action' => 'create',
    ],

    'play/game' => [
        'controller' => 'game',
        'action' => 'game',
    ],

    'play/join' => [
        'controller' => 'game',
        'action' => 'join',
    ],

    'play/turn' => [
        'controller' => 'game',
        'action' => 'turn',
    ],
];