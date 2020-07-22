<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */
include __DIR__.'/../../vendor/autoload.php';

use FastD\Application;
use FastD\FPM\FastCGI;

(new FastCGI(new Application(__DIR__ . '/../')))->start();
