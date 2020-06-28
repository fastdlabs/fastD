<?php

$route = route();

$route->get('/', function () {
    abort('abc', 500);
    return json([
        'foo' => 'bar'
    ]);
});

$route->post('/', function ($request) {
    return $request->getMethod();
});
