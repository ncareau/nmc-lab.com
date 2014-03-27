<?php

//Article Page.
$a = $app['controllers_factory'];


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


$a->get('/', function () use ($periods, $app) {
    return $app['twig']->render('articlesList.html', array(
                'articles' => $periods
    ));
})->bind('article');




//List files in artciles
//Foreach month, add articles. 
foreach ($periods as $period => $articles) {
    foreach ($articles as $article) {
        $a->get('/' . $period . '/' . $article['url'] . "/", function () use ($app, $period, $article) {
            return $app['twig']->render('articles.html', array(
                        'article' => $article,
            ));
        });
    }
}

return $a;
