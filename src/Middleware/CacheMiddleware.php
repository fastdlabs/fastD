<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Middleware;


use FastD\Http\Response;
use FastD\Utils\DateObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CacheMiddleware
 * @package Middleware
 */
class CacheMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $next
     * @return mixed|ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        $key = $request->getUri()->getPath();
        $key = md5($key);
        $cache = cache()->getItem($key);
        if ($cache->isHit()) {
            $value = $cache->get();
            return (new Response($value))->withHeader('cache', '1');
        }
        $date = new DateObject(date('Y-m-d H:i:s', time() + 5));
        $response = $next->next($request);
        $cache->set((string) $response->getBody());
        $cache->expiresAt($date);
        cache()->save($cache);
        return $response->withExpires($date);
    }
}