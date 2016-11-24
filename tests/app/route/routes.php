<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

app()->route()->get('/', [IndexController::class, 'handle']);
app()->route()->get('/', 'IndexController@handle');

app()->route()->get('/hello/[{name}]', function ($name) {
    return new \FastD\Http\Response('hello ' . $name . ' !');
}, ['name' => 'FastD']);

