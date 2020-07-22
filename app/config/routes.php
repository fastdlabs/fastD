<?php

$route = route();

$route->get('/', function () {
    abc
    return json([
        'foo' => 'bar'
    ]);
});

$route->post('/', function ($request) {
    return $request->getMethod();
});
