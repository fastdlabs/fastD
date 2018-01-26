<?php

route()->get('/', 'IndexController@welcome');
route()->get('/foo/{name}', 'IndexController@sayHello');
route()->get('/db', 'IndexController@db');
route()->post('/foo/{name}', 'IndexController@middleware')->withAddMiddleware(new \Middleware\FooMiddleware());
route()->get('/model', 'IndexController@model');
route()->get('/auth', 'IndexController@auth');
route()->get('/queue', 'IndexController@queue');
route()->get('/abort', 'IndexController@abort');
route()->post('/abc', 'WelcomeController@welcome')->withMiddleware('validator');
route()->get('/session', 'IndexController@welcome')->withAddMiddleware('session');

