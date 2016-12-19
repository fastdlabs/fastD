<?php

route()->get('/', 'IndexController@welcome');

route()->get('/foo/{name}', 'IndexController@sayHello');

route()->post('/foo/{name}', 'IndexController@middleware')->withAddMiddleware(new \Middleware\FooMiddleware());