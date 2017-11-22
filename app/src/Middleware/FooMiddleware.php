<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace Middleware;

use FastD\Http\JsonResponse;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FooMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $serverRequest, DelegateInterface $delegate)
    {
        if ('bar' === $serverRequest->getAttribute('name')) {
            return new JsonResponse([
                'foo' => 'middleware',
            ]);
        }

        return $delegate($serverRequest);
    }
}
