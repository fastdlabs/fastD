<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

/**
 * @return \FastD\App
 */
function app () {
    return \FastD\App::$app;
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
    return app()->get('store')->getStore($name);
}

/**
 * @param string $content
 * @param int $statusCode
 * @param array $headers
 * @return \FastD\Http\Response
 */
function response ($content = '', $statusCode = 200, array $headers = []) {
    return new \FastD\Http\Response($content, $statusCode, $headers);
}

/**
 * @param $url
 * @return \FastD\Http\RedirectResponse
 */
function redirect ($url) {
    return new \FastD\Http\RedirectResponse($url);
}

/**
 * @param $name
 * @param array $arguments
 * @return mixed
 */
function factory ($name, array $arguments = []) {
    return app()->make($name, $arguments);
}