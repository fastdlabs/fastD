<?php

route()->get('/', 'IndexController@welcome')->withMiddleware('common.cache');
route()->get('/foo/{name}', 'IndexController@sayHello');
route()->get('/db', 'IndexController@db');
route()->post('/foo/{name}', 'IndexController@middleware')->withAddMiddleware(new \Middleware\FooMiddleware());
route()->get('/model', 'IndexController@model');
route()->get('/auth', 'IndexController@auth')->withAddMiddleware('basic.auth');
