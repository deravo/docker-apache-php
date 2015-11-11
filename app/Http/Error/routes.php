<?php
$app->get('/',  ['uses' => 'IndexController@Index']);

$app->get('/Test', ['uses' => 'IndexController@Test']);