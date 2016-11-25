<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event;

use FastD\Container\Container;
use FastD\Http\ServerRequest;
use FastD\Provider\RouteServiceProvider;

class RequestListener
{
    /**
     * @param Container $container
     * @param ServerRequest $serverRequest
     */
    public static function handle(Container $container, ServerRequest $serverRequest)
    {
        $response = $container[RouteServiceProvider::SERVICE_NAME]->dispatch($serverRequest->getMethod(), $serverRequest->server->getPathInfo());

        $response->send();
    }
}