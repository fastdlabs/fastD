<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Http\Controller;

use FastD\Http\JsonResponse;
use FastD\Http\ServerRequest;
use FastD\Middleware\Delegate;

class IndexController
{
    /**
     * @param ServerRequest $request
     * @param Delegate $delegate
     * @return \FastD\Http\JsonResponse
     */
    public function welcome(ServerRequest $request, Delegate $delegate)
    {
        return json([
            'foo' => 'bar'
        ]);
    }

    /**
     * @param ServerRequest $request
     * @param Delegate $delegate
     * @return JsonResponse
     */
    public function sayHello(ServerRequest $request, Delegate $delegate)
    {
        return json([
            'foo' => $request->getAttribute('name'),
        ]);
    }

    public function middleware(ServerRequest $serverRequest, Delegate $delegate)
    {
        return json([
            'foo' => 'bar'
        ]);
    }
}