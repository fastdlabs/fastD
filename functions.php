<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

/**
 * @param array $bootstrap
 * @return \FastD\App
 */
function app (array $bootstrap = []) {
    return \FastD\App::app($bootstrap);
}

/**
 * @return \FastD\Routing\Route
 */
function route () {
    return app()->getContainer()->singleton('kernel.routing')->addRoute();
}