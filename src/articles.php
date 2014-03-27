<?php

//Article Page.
$a = $app['controllers_factory'];




$a->get('/', function () {
    return 'Blog home page';
})->bind('article');


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

//List files in artciles
//Foreach month, add articles. 
foreach ($periods as $period => $articles) {
    foreach($articles as $article) {
        $a->get('/' . $period . '/'.$article['url']."/", function () use ($app, $period, $article) {
            return $app['twig']->render('articles.html', array(
                'article' => $article,
            ));
        });
    }
}

return $a;
