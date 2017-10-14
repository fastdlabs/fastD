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
use FastD\Swoole\Server\TCP;
use swoole_server;

/**
 * Class TCPServer.
 */
class TCPServer extends TCP
{
    use OnWorkerStart;

    /**
     * @param swoole_server $server
     * @param $fd
     * @param $data
     * @param $from_id
     *
     * @return int
     */
    public function doWork(swoole_server $server, $fd, $data, $from_id)
    {
        if ('quit' === $data) {
            $server->close($fd);

            return 0;
        }
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
        if (null !== $response->getFileDescriptor()) {
            $fd = $response->getFileDescriptor();
        }
        if (false === $server->connection_info($fd)) {
            return -1;
        }
        $server->send($fd, (string) $response->getBody());
        app()->shutdown($request, $response);

        return 0;
    }
}
