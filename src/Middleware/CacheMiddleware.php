<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Middleware;

use FastD\Http\Response;
use FastD\Utils\DateObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CacheMiddleware.
 */
class CacheMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $next
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        if ('GET' !== $request->getMethod()) {
            return $next->process($request);
        }

        $key = md5($request->getUri()->getPath().http_build_query($request->getQueryParams()));
        $cache = cache()->getItem($key);
        if ($cache->isHit()) {
            list($content, $headers) = $cache->get();

            return new Response($content, Response::HTTP_OK, $headers);
        }

        $response = $next->process($request);
        if (Response::HTTP_OK !== $response->getStatusCode()) {
            return $response;
        }

        $expireAt = DateObject::createFromTimestamp(time() + config()->get('common.cache.lifetime', 60));

        $response->withHeader('X-Cache', $key)->withExpires($expireAt);

        $cache->set([
            (string) $response->getBody(),
            $response->getHeaders(),
        ]);

        cache()->save($cache->expiresAt($expireAt));

        return $response;
    }
}
