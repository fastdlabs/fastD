<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Application;

$loader = include __DIR__ . '/../../../vendor/autoload.php';

$app = new Application(
    __DIR__ . '/..'
);

$app->run();
