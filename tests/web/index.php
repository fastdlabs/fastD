<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
include __DIR__.'/../../vendor/autoload.php';

use FastD\Application;

$app = new Application(__DIR__.'/..');

$app->run();
