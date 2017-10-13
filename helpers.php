<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */
use FastD\Application;
use FastD\Config\Config;
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Model\Database;
use FastD\Model\Model;
use FastD\Model\ModelFactory;
use FastD\Packet\Swoole;
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
 * @return string
 */
function version()
{
    return Application::VERSION;
}

/**
 * @return RouteCollection
 */
function route()
{
    return app()->get('router');
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
 * @return Response
 */
function response()
{
    return app()->get('response');
}

/**
 * @param array $content
 * @param int   $statusCode
 *
 * @return Response
 */
function binary(array $content, $statusCode = Response::HTTP_OK)
{
    return new Response(Swoole::encode($content), $statusCode);
}

/**
 * @param array $content
 * @param int   $statusCode
 *
 * @return Response
 */
function json(array $content = [], $statusCode = Response::HTTP_OK)
{
    return new JsonResponse($content, $statusCode);
}

/**
 * @param $statusCode
 * @param $message
 *
 * @throws Exception
 */
function abort($statusCode, $message = null)
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

function client()
{
}

/**
 * @return \FastD\Swoole\Server
 */
function server()
{
    return app()->get('server');
}

/**
 * @return swoole_server
 */
function swoole()
{
    return server()->getSwoole();
}
