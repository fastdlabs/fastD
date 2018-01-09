<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace Middleware;


use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class SessionMiddleware
 * @package Middleware
 */
class SessionMiddleware extends Middleware
{
    const SESSION_ID_KEY = 'session-id';

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        $cookies = $request->getCookieParams();

        if (!isset($cookies[static::SESSION_ID_KEY])) {
            $sessionId = session_create_id();
        } else {
            $sessionId = $cookies[static::SESSION_ID_KEY];
        }

        $request->withHeader(static::SESSION_ID_KEY, $sessionId);

        $response = $next->process($request);

        return $response->withCookie(static::SESSION_ID_KEY, $sessionId);
    }
}