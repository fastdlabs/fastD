<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */
include __DIR__.'/../../../vendor/autoload.php';

use FastD\Application;

$app = new Application(__DIR__.'/../default');

$app->run();
