<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Standard\Middleware;

use FastD\Container\ContainerAware;
use FastD\Middleware\Middleware;

/**
 * Class MiddlewareAware
 *
 * @package FastD\Standard\Middleware
 */
abstract class MiddlewareAware extends Middleware
{
    use ContainerAware;
}