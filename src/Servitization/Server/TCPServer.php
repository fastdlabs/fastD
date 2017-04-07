<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\Server;

use FastD\Http\ServerRequest;
use FastD\Packet\Json;
use FastD\Pool\PoolInterface;
use FastD\Swoole\Server\TCP;
use swoole_server;

/**
 * Class TCPServer.
 */
class TCPServer extends TCP
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
     * @param swoole_server $server
     * @param $fd
     * @param $data
     * @param $from_id
     *
     * @return mixed
     */
    public function doWork(swoole_server $server, $fd, $data, $from_id)
    {
        if ('quit' == $data) {
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
        try {
            $response = app()->handleRequest($request);
        } catch (\Exception $e) {
            $response = app()->handleException($e);
        }
        $server->send($fd, (string) $response->getBody());

        return 0;
    }
}
