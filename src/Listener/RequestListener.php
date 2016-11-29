<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Listener;

use Exceptions\RouteCallbackException;
use FastD\Container\Container;
use FastD\Http\ServerRequest;

class RequestListener
{
    /**
     * @param Container $container
     * @param ServerRequest $serverRequest
     */
    public static function handle(Container $container, ServerRequest $serverRequest)
    {
        $pathInfo = isset($serverRequest->getServerParams()['PATH_INFO']) ? $serverRequest->getServerParams()['PATH_INFO'] : null;
        if (null === $pathInfo) {
            $pathInfo = str_replace($serverRequest->getServerParams()['SCRIPT_NAME'], '', $serverRequest->getUri()->getPath());
        }
        $route = $container['router']->match($serverRequest->getMethod(), $pathInfo);

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