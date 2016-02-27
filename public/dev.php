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

/**
 * The local develop environment index.
 */
include __DIR__ . '/../vendor/autoload.php';

$app = new Application('dev');
$app->boot();
$response = $app->createHttpRequestHandler();
$response->send();
$app->shutdown($app);
