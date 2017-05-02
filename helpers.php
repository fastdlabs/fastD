<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

use FastD\Application;
use FastD\Config\Config;
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Model\Database;
use FastD\Model\Model;
use FastD\Model\ModelFactory;
use FastD\Packet\Json;
use FastD\Routing\RouteCollection;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

/**
 * @return Application
 */
function app()
{
    return Application::$app;
}

/**
 * @param $prefix
 * @param $callback
 *
 * @return RouteCollection
 */
function route($prefix = null, callable $callback = null)
{
    if (null === $prefix) {
        return app()->get('router');
    }

    return app()->get('router')->group($prefix, $callback);
}

/**
 * @return Config
 */
function config()
{
    return app()->get('config');
}

/**
 * @return ServerRequestInterface
 */
function request()
{
    return app()->get('request');
}

/**
 * @param $statusCode
 * @return JsonResponse
 */
function response($statusCode = 200)
{
    if (!app()->has('response')) {
        app()->add('response', new JsonResponse([], $statusCode));
    }

    return app()->get('response');
}

/**
 * @param array $content
 * @param int   $statusCode
 *
 * @return JsonResponse
 */
function json(array $content = [], $statusCode = Response::HTTP_OK)
{
    return response()
        ->withContent(Json::encode($content))
        ->withStatus($statusCode)
        ;
}

/**
 * @param $message
 * @param $statusCode
 *
 * @throws Exception
 */
function abort($message, $statusCode)
{
    throw new Exception((is_null($message) ? Response::$statusTexts[$statusCode] : $message), $statusCode);
}

/**
 * @return Logger
 */
function logger()
{
    return app()->get('logger');
}

/**
 * @param $key
 *
 * @return AbstractAdapter
 */
function cache($key = 'default')
{
    return app()->get('cache')->getCache($key);
}

/**
 * @param $key
 *
 * @return Database
 */
function database($key = 'default')
{
    return app()->get('database')->getConnection($key);
}

/**
 * @param $name
 * @param $key
 *
 * @return Model
 */
function model($name, $key = 'default')
{
    return ModelFactory::createModel($name, $key);
}

/**
 * @return \swoole_server
 */
function server()
{
    return app()->get('server');
}
