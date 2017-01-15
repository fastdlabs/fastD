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
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\ServerMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FooMiddleware extends ServerMiddleware
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $serverRequest, DelegateInterface $delegate)
    {
        if ('bar' == $serverRequest->getAttribute('name')) {
            return new JsonResponse([
                'foo' => 'middleware'
            ]);
        }
        return $delegate($serverRequest);
    }
}