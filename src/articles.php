<?php
//Article Page.

//Fetch all articles.
//List files in artciles

//Foreach month, add articles. 


$a = $app['controllers_factory'];
$a->get('/', function () { return 'Blog home page'; });

return $a;