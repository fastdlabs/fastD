<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/30
 * Time: 下午4:35
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

$loader = include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../app/Application.php';

$app = new Application('test');

$app->bootstrap();

$response = $app->handleHttpRequest();

$response->send();

$app->terminate($response);

