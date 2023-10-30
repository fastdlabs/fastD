<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace fastd\server\handler;


use FastD\Http\SwooleRequest;
use FastD\Swoole\Server\Handler\HandlerAbstract;
use FastD\Swoole\Server\Handler\HTTPHandlerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * swoole http 处理器，接受的http请求会使用该对象进行处理
 *
 * Class HttpHandle
 * @package FastD\Server\Handle
 */
class HttpHandler extends HandlerAbstract implements HTTPHandlerInterface
{
    public function onRequest(Request $swooleRequet, Response $swooleResponse): void
    {
        $request = SwooleRequest::createServerRequestFromSwoole($swooleRequet);

        if ($request->serverParams['PATH_INFO'] === '/favicon.ico') {
            $swooleResponse->end();
            return;
        }

        try {
            $response = container()->get('dispatcher')->dispatch($request);
            foreach ($response->getHeaders() as $key => $header) {
                $swooleResponse->header($key, $response->getHeaderLine($key));
            }
            foreach ($response->getCookies() as $key => $cookie) {
                $swooleResponse->cookie($key, $cookie);
            }
            $swooleResponse->status($response->getStatusCode());
            $swooleResponse->end((string) $response->getBody());
        } catch (\Exception $e) {
            $exceptionData = runtime()->handleException($e);
            $responseData = json($exceptionData, \FastD\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
            $swooleResponse->status($responseData->getStatusCode());
            $swooleResponse->end((string)$responseData->getBody());
        }
    }
}
