<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
use FastD\App;

$loader = include __DIR__ . '/vendor/autoload.php';

$app = new App(include __DIR__ . '/bootstrap.php');

$response = $app->handleHttpRequest();

$response->send();

$app->shutdown();

