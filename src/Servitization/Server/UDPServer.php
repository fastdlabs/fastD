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
use FastD\Swoole\Server\UDP;
use FastD\Servitization\OnWorkerStart;
use swoole_server;

/**
 * Class UDPServer.
 */
class UDPServer extends UDP
{
    use OnWorkerStart;

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
        try {
            $response = app()->handleRequest($request);
        } catch (\Exception $e) {
            $response = app()->handleException($e);
        }
        $server->sendto($clientInfo['address'], $clientInfo['port'], (string) $response->getBody());
    }
}
