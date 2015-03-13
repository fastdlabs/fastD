<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/7
 * Time: 上午1:57
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

$app = include __DIR__ . '/../app/bootstrap/bootstrap.php';

$app->bootstrap();

$response = $app->handleHttpRequest();

$response->send();

$app->terminate($response);

