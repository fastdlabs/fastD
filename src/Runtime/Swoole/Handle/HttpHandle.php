<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Runtime\Swoole\Handle;


use FastD\Http\SwooleServerRequest;
use FastD\Swoole\Handlers\HandlerAbstract;
use FastD\Swoole\Handlers\HTTPHandlerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class HttpHandle
 * @package FastD\Server\Handle
 */
class HttpHandle extends HandlerAbstract implements HTTPHandlerInterface
{
    public function onRequest(Request $swooleRequet, Response $swooleResponse): void
    {
        $request = SwooleServerRequest::createServerRequestFromSwoole($swooleRequet);

        $response = container()->get('dispatcher')->dispatch($request);

        foreach ($response->getHeaders() as $key => $header) {
            $swooleResponse->header($key, $response->getHeaderLine($key));
        }

        foreach ($response->getCookieParams() as $key => $cookie) {
            $swooleResponse->cookie($key, $cookie);
        }

        $swooleResponse->status($response->getStatusCode());
        $swooleResponse->end((string) $response->getBody());
    }
}
