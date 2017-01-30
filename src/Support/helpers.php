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
use Monolog\Logger;

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
        return app()->get('router');
    }

    return app()->get('router')->group($prefix, $callback);
}

/**
 * @return Config
 */
function config () {
    return app()->get('config');
}

/**
 * @return mixed
 */
function request () {
    return app()->get('request');
}

/**
 * @param array $content
 * @param int $statusCode
 * @param array $headers
 * @return JsonResponse
 */
function json (array $content = [], $statusCode = Response::HTTP_OK, array $headers = []) {
    $headers['X-App-Version'] = Application::VERSION;
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
 * @return Logger
 */
function logger () {
    return app()->get('logger');
}

/**
 * @return mixed
 */
function cache () {
    return app()->get('cache');
}

/**
 * @return mixed
 */
function database () {
    return app()->get('database');
}


