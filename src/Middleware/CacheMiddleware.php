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
use FastD\Packet\Json;
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
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        $action = $request->getMethod();
        if ('GET' === $action) {
            $key = md5($request->getUri()->getPath());
            $cache = cache()->getItem($key);
            if ($cache->isHit()) {
                $value = Json::decode($cache->get());
                return json($value)
                    ->withHeader('X-Cache', $key);
            }
            $response = $next->next($request);
            $cache->set((string) $response->getBody());

            $expireAt = DateObject::createFromTimestamp(time() + config()->get('common.cache.lifetime', 60));
            $cache->expiresAt($expireAt);
            cache()->save($cache);
            return $response->withExpires($expireAt);
        }
        return $next->next($request);
    }
}