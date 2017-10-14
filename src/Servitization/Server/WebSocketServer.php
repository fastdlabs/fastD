<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Servitization\Server;

use FastD\Http\ServerRequest;
use FastD\Packet\Json;
use FastD\Servitization\OnWorkerStart;
use FastD\Swoole\Server\WebSocket;
use swoole_server;
use swoole_websocket_frame;

/**
 * Class WebSocketServer.
 */
class WebSocketServer extends WebSocket
{
    use OnWorkerStart;

    /**
     * @param swoole_server          $server
     * @param swoole_websocket_frame $frame
     *
     * @return int
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
        $response = app()->handleRequest($request);
        $fd = null !== ($fd = $response->getFileDescriptor()) ? $fd : $frame->fd;
        if (false === $server->connection_info($fd)) {
            return -1;
        }
        $server->push($fd, (string) $response->getBody());
        app()->shutdown($request, $response);

        return 0;
    }
}
