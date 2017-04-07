<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Servitization\Server;

use FastD\Http\ServerRequest;
use FastD\Packet\Json;
use FastD\Pool\PoolInterface;
use FastD\Swoole\Server\WebSocket;
use swoole_server;
use swoole_websocket_frame;

/**
 * Class WebSocketServer.
 */
class WebSocketServer extends WebSocket
{
    /**
     * @param swoole_server $server
     * @param int           $worker_id
     */
    public function onWorkerStart(swoole_server $server, $worker_id)
    {
        parent::onWorkerStart($server, $worker_id);

        foreach (app() as $service) {
            if ($service instanceof PoolInterface) {
                $service->initPool();
            }
        }
    }

    /**
     * @param swoole_server          $server
     * @param swoole_websocket_frame $frame
     *
     * @return mixed
     */
    public function doMessage(swoole_server $server, swoole_websocket_frame $frame)
    {
        $data = $frame->data;
        $data = Json::decode($data);
        $request = new ServerRequest($data['method'], $data['path']);
        if (isset($data['args'])) {
            if ('GET' === $request->getMethod()) {
                $request->withQueryParams($data['args']);
            } else {
                $request->withParsedBody($data['args']);
            }
        }
        try {
            $response = app()->handleRequest($request);
        } catch (\Exception $e) {
            $response = app()->handleException($e);
        }
        $server->push($frame->fd, (string) $response->getBody());

        return 0;
    }
}
