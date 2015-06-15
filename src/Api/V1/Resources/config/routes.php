<?php
Routes::group('/api', function () {
    Routes::group('/v1', function () {
        Routes::get('/test', 'Api\\V1\\Events\\Index@indexAction')->setFormats(['json']);
    });
});
