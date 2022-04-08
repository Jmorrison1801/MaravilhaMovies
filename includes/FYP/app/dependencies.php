<?php

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig']
    );

    // Instantiate and add Slim\Twig specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};

$container['RegisterValidator'] = function ($container){
    $model = new \MaravilhaMovies\RegisterValidator();
    return $model;
};

$container['DatabaseWrapper'] = function($container){
    $model = new \MaravilhaMovies\DatabaseWrapper();
    return $model;
};

$container['SQLQueries'] = function($container){
    $model = new \MaravilhaMovies\SQLQueries();
    return $model;
};

$container['AccountManager'] = function($container){
    $model = new \MaravilhaMovies\AccountManager();
    return $model;
};

$container['SessionModel'] = function($container){
    $model = new \MaravilhaMovies\SessionModel();
    return $model;
};

$container['SessionWrapper'] = function($container){
    $model = new \MaravilhaMovies\SessionWrapper();
    return $model;
};

$container['BcryptWrapper'] = function($container){
    $model = new \MaravilhaMovies\BcryptWrapper();
    return $model;
};

$container['MovieManager'] = function($container){
    $model = new \MaravilhaMovies\MovieManager();
    return $model;
};

$container['MovieCollection'] = function($container){
    $model = new \MaravilhaMovies\MovieCollection();
    return $model;
};