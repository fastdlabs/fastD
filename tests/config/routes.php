<?php

route()->get('/', 'IndexController@welcome');

route()->get('/foo/{name}', 'IndexController@sayHello');

route()->get('/db', 'IndexController@db');

route()->post('/foo/{name}', 'IndexController@middleware')->withAddMiddleware(new \Middleware\FooMiddleware());

route()->get('/model', 'IndexController@model');