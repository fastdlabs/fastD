<?php

route()->addRoute('GET', '/', function () {
    return json([
        'foo' => 'bar'
    ]);
});
