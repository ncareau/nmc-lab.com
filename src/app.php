<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {

    //Asset Function
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
        return sprintf('http://192.168.1.25/lab/%s', ltrim($asset, '/'));
    }));
    
    return $twig;
}));

return $app;