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
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                return sprintf($protocol . $_SERVER['HTTP_HOST'] . '/lab/%s', ltrim($asset, '/'));
            }));

            $twig->addFunction(new \Twig_SimpleFunction('articleUrl', function ($period, $articleUrl) {
                $x = explode('-', $period);
                $link = $x[0] .'/'. $x[1] . '/' . $articleUrl;
                
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                return sprintf($protocol . $_SERVER['HTTP_HOST'] . '/lab/%s', $link);
            }));

            return $twig;
        }));

return $app;
