<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));
//Fetch all articles.
if ($handle = opendir('../src/articles')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $period = str_replace('.php', '', $entry);
            $periods[$period] = include('../src/articles/' . $entry);
        }
    }
    closedir($handle);
}


$app->get('/', function () use ($app, $periods) {
            return $app['twig']->render('index.html', array(
                'articles'=> $periods['2014-03']
            ));
        })
        ->bind('home')
;

$app->get('/code/', function () use ($app) {
            return $app['twig']->render('code.html', array());
        })
        ->bind('code')
;

$app->get('/games/', function () use ($app) {
            return $app['twig']->render('games.html', array());
        })
        ->bind('games')
;
$app->get('/tech/', function () use ($app) {
            return $app['twig']->render('tech.html', array());
        })
        ->bind('tech')
;
$app->get('/about/', function () use ($app) {
            return $app['twig']->render('about.html', array());
        })
        ->bind('about')
;

//Articles binding.
$app->mount('/a', include 'articlesController.php');

//Error binding.
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html',
        'errors/' . substr($code, 0, 2) . 'x.html',
        'errors/' . substr($code, 0, 1) . 'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});



