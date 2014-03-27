<?php

// configure your app for the production environment
$app['twig.path'] = array(__DIR__.'/../templates');

$app['twig.options'] = array(
    'cache' => __DIR__.'/../var/cache/twig'
    );

$app['ads_ip_filter'] = array(
    '23.233.228.191',
);
