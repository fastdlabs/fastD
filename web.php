<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
$loader = include __DIR__ . '/vendor/autoload.php';

$app = new \FastD\App(include __DIR__ . '/bootstrap.php');

$app->run();

