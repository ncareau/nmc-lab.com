<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));
//Fetch all articles.
$periods = array();
if ($handle = opendir('../src/articles')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $period = str_replace('.php', '', $entry);
            $periods[$period] = include('../src/articles/' . $entry);
        }
    }
    closedir($handle);
}
end($periods);
$lastPediod = key($periods);
$lastArticle = end($periods[$lastPediod]);


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

$app->get('/articles/', function () use ($app, $periods) {
    $allArticles = array();
    foreach($periods as $period){
        foreach ($period as $date => $article) {
            $allArticles[$date] = $article;
        }
    }
    return $app['twig']->render('articleList.html.twig', array(
                'articles' => $allArticles
    ));
})->bind('articles');

//Add link alias to specific article.
$app->get('/2014/03/how-to-create-new-libgdx-project-in-netbeans-8.html', function () use ($app, $periods) {
    return $app['twig']->render('article.html.twig', array(
                'article' => $periods['2014-03']['2014-03-20'],
    ));
});

//All articles binding.        
$app->get('/{y}/{m}/{articleUrl}/', function ($y, $m, $articleUrl) use ($app, $periods) {
    if (isset($periods[$y . '-' . $m])) {
        $periodArticles = $periods[$y . '-' . $m];
        foreach ($periodArticles as $article) {
            if ($article['url'] == $articleUrl) {
                return $app['twig']->render('article.html.twig', array(
                            'article' => $article
                ));
            }
        }
    }
    return $app['twig']->render('articleNotFound.html.twig');
});

//Error binding.
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html.twig',
        'errors/' . substr($code, 0, 2) . 'x.html.twig',
        'errors/' . substr($code, 0, 1) . 'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});



