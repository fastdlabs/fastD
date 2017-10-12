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
use FastD\Swoole\Server\UDP;
use swoole_server;

/**
 * Class UDPServer.
 */
class UDPServer extends UDP
{
    use OnWorkerStart;

    /**
     * @param swoole_server $server
     * @param $data
     * @param $clientInfo
     *
     * @return mixed
     */
    public function doPacket(swoole_server $server, $data, $clientInfo)
    {
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
        $server->sendto($clientInfo['address'], $clientInfo['port'], (string) $response->getBody());
        app()->shutdown($request, $response);

        return 0;
    }
}
