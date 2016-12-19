<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Application;
use FastD\Config\Config;
use FastD\Http\JsonResponse;
use FastD\Http\RedirectResponse;
use FastD\Http\Response;

/**
 * @return Application
 */
function app () {
    return Application::$app;
}

/**
 * @param $prefix
 * @param $callback
 * @return \FastD\Routing\RouteCollection
 */
function route ($prefix = null, callable $callback = null) {
    if (null === $prefix) {
        return app()['router'];
    }

    return app()['router']->group($prefix, $callback);
}

/**
 * @return Config
 */
function config () {
    return app()['config'];
}

/**
 * @return \Psr\Http\Message\ServerRequestInterface
 */
function request () {
    return app()['request'];
}

/**
 * @param array $content
 * @param int $statusCode
 * @param array $headers
 * @return JsonResponse
 */
function json (array $content = [], $statusCode = Response::HTTP_OK, array $headers = []) {
    $headers['X-App-Version'] = Application::VERSION;
    $headers['X-Powered-By'] = app()->getName();
    return new JsonResponse($content, $statusCode, $headers);
}

/**
 * @param $url
 * @return RedirectResponse
 */
function redirect ($url) {
    return new RedirectResponse($url);
}

/**
 * @return \Monolog\Logger
 */
function logger () {
    return app()['logger'];
}

function storage ($name) {

}

function database ($name) {

}


