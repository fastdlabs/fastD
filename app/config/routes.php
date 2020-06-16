<?php

$route = route();

$route->get('/', function () {
    return json([
        'foo' => 'bar'
    ]);
});

$route->post('/', function ($request) {
    return $request->getMethod();
});
