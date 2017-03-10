<?php

route()->get(['/', 'name' => 'welcome'], 'IndexController@welcome');

route()->get(['/foo/{name}', 'name' => 'foo'], 'IndexController@sayHello');

route()->get(['/db', 'name' => 'db'], 'IndexController@db');

route()->post(['/foo/{name}', 'name' => 'post'], 'IndexController@middleware')->withAddMiddleware(new \Middleware\FooMiddleware());

route()->get(['/model', 'name' => 'model'], 'IndexController@model');

route()->get(['/auth', 'name' => 'auth'], 'IndexController@auth')->withAddMiddleware('basic.auth');