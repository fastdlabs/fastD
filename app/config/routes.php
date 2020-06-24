<?php

$route = route();

$route->get('/', function () {
    abort('abc');
    return json([
        'foo' => 'bar'
    ]);
});

$route->post('/', function ($request) {
    return $request->getMethod();
});
