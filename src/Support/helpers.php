<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Application;

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
 * @param $name
 * @return mixed
 */
function event ($name) {
    return app()->get('event')->trigger($name);
}

/**
 * @param $name
 * @return \FastD\Store\Store
 */
function store ($name) {
    return app()->get('store')->get($name);
}

/**
 * @return \Psr\Http\Message\ServerRequestInterface
 */
function request () {
    return app()->get('request');
}

/**
 * @param string $content
 * @param int $statusCode
 * @param array $headers
 * @return \FastD\Http\Response
 */
function response ($content = '', $statusCode = \FastD\Http\Response::HTTP_OK, array $headers = []) {
    $headers['X-FastD-Version'] = \FastD\Application::VERSION;
    $headers['X-Powered-By'] = app()->getName();
    return new \FastD\Http\Response($content, $statusCode, $headers);
}

/**
 * @param array $content
 * @param int $statusCode
 * @param array $headers
 * @return \FastD\Http\JsonResponse
 */
function json (array $content = [], $statusCode = \FastD\Http\Response::HTTP_OK, array $headers = []) {
    $headers['X-FastD-Version'] = \FastD\Application::VERSION;
    $headers['X-Powered-By'] = app()->getName();
    return new \FastD\Http\JsonResponse($content, $statusCode, $headers);
}

/**
 * @param $url
 * @return \FastD\Http\RedirectResponse
 */
function redirect ($url) {
    return new \FastD\Http\RedirectResponse($url);
}

/**
 * @return \Monolog\Logger
 */
function logger () {
    return app()->get('logger');
}

function storage ($name) {

}

function database ($name) {

}

function config () {
    return app()->get('config');
}
