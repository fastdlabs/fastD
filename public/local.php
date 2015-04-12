<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/11
 * Time: 下午3:36
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app/Application.php';

$app = new Application('local');

$app->boot();

$request = \Dobee\Http\Request::createGlobalRequest();

$response = $app->handleHttpRequest($request);

$response->send();

$app->terminate($request, $response);