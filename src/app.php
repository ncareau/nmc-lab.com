<?php
/**
 * Application
 */


use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();

require __DIR__.'/../config/config.php';

$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
            //Asset Function
            $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                return sprintf($protocol . $app['asset_url'] . '/%s', ltrim($asset, '/'));
            }));

            //App Config
            $twig->addFunction(new \Twig_SimpleFunction('appConfig', function ($name) use ($app) {
                if($app[$name]){
                    return $app[$name];
                }else{
                    return null;
                }
            }));

            return $twig;
        }));

return $app;
