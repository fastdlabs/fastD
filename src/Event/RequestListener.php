<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event;

use Exceptions\RouteCallbackException;
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
        $route = $container[RouteServiceProvider::SERVICE_NAME]->match($serverRequest->getMethod(), $serverRequest->server->getPathInfo());

        if (is_array(($callback = $route->getCallback()))) {
            $container->injectOn('controller', $callback[0])->withMethod($callback[1])->withArguments($route->getParameters());
        } else if (is_callable($callback)) {
            $container->injectOn('controller', $callback)->withArguments($route->getParameters());
        } else {
            throw new RouteCallbackException();
        }

        $response = $container->make('controller');

        $response->send();
    }
}