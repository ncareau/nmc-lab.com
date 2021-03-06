<?php
/**
 * Controller
 */

use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\MonologServiceProvider;

//Activate Logs
if($app['debug']){
    $app->register(new MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__.'/../var/logs/silex_dev.log',
    ));
}else{
    $app->register(new MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__.'/../var/logs/silex.log',
    ));

    $app['monolog'] = $app->share($app->extend('monolog',
        function($monolog, $app) {
            if (!$app['debug']) {
                $monolog->pushHandler(new Monolog\Handler\NativeMailerHandler(
                    $app['admin_email'],
                    $app['appname'] . ' -- Error Log',
                    $app['email_from'],
                    Monolog\Logger::ERROR
                ));
            }

            return $monolog;
        }));
}


//Fetch all articles.
$articles = array();
//Open articles folder.
if ($handle = opendir('../src/articles')) {
    while (false !== ($year = readdir($handle))) {
        if ($year != "." && $year != "..") {

            //Open the year folder.
            if ($handle = opendir('../src/articles'. DIRECTORY_SEPARATOR . $year)) {
                while (false !== ($periods = readdir($handle))) {
                    if ($periods != "." && $periods != "..") {

                        //Include all articles
                        $articleFile = include('../src/articles' . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $periods);
                        foreach ($articleFile as $date => $article){
                            $articles[$date] = new Article($article);
                        }
                    }
                }
            }

        }
    }
    closedir($handle);
}
krsort($articles);
$lastArticle = $articles[key($articles)];


$app->get('/', function () use ($app, $lastArticle) {
    return $app['twig']->render('index.html.twig', array(
                'article' => $lastArticle
    ));
})->bind('home');


$app->get('/code/', function () use ($app) {
    return $app['twig']->render('code.html.twig', array());
})->bind('code');


$app->get('/games/', function () use ($app) {
    return $app['twig']->render('games.html.twig', array());
})->bind('games');


$app->get('/tech/', function () use ($app) {
    return $app['twig']->render('tech.html.twig', array());
})->bind('tech');


$app->get('/about/', function () use ($app) {
    return $app['twig']->render('about.html.twig', array());
})->bind('about');

$app->get('/articles/', function () use ($app, $articles) {
    return $app['twig']->render('articleList.html.twig', array(
                'articles' => $articles
    ));
})->bind('articles');

//Add link alias to specific articles.
$app->get('/2014/03/how-to-create-new-libgdx-project-in-netbeans-8.html', function () use ($app, $articles) {
    return $app['twig']->render('articleView.html.twig', array(
                'article' => $articles['2014-03-20'],
    ));
});

//All articles binding.
$app->get('/{y}/{m}/{articleUrl}/', function ($y, $m, $articleUrl) use ($app, $articles) {
        foreach ($articles as $article) {
            if ($article->url == $articleUrl && $article->getMonth() == $m && $article->getYear() == $y) {
                return $app['twig']->render('articleView.html.twig', array(
                            'article' => $article
                ));
            }
        }
    return $app['twig']->render('articleNotFound.html.twig');
});

//Error binding.
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    if($code == 404){
        return new Response($app['twig']->render('errors/404.html.twig', array()), $code);
    }

    if($code == 500){
        return new Response($app['twig']->render('errors/500.html.twig', array()), $code);
    }

    return new Response($app['twig']->render('errors/default.html.twig', array()), $code);
});