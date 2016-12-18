<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Middleware;


use FastD\Http\JsonResponse;
use FastD\Http\ServerRequest;
use FastD\Middleware\Delegate;
use FastD\Middleware\ServerMiddleware;

class FooMiddleware extends ServerMiddleware
{
    public function __construct()
    {
        parent::__construct(function (ServerRequest $serverRequest, Delegate $delegate) {
            if ('bar' == $serverRequest->getAttribute('name')) {
                return new JsonResponse([
                    'foo' => 'middleware'
                ]);
            }
            return $delegate($serverRequest);
        });
    }
}