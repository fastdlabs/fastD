<?php

route()->get('/', 'IndexController@welcome');

route()->get('/store', 'IndexController@store');

route()->get('/hello/[{name}]', function ($name) {
    return response('hello ' . $name . ' !');
});