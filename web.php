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

use FastD\App;
use FastD\Http\ServerRequest;

$loader = include __DIR__ . '/vendor/autoload.php';

$app = new App(include __DIR__ . '/bootstrap.php');

$response = $app->handleHttpRequest();

$response->send();

$app->shutdown();

