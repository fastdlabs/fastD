<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

app()->route()->get('/', [\Welcome\Controller\IndexController::class, 'welcomeAction']);

app()->route()->get('/hello/[{name}]', function ($name) {
    return response('hello ' . $name . ' !');
});

