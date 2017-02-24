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
use FastD\Monitor\Report;
use FastD\Packet\Json;
use FastD\Swoole\Server\Tcp;
use swoole_server;

/**
 * Class TCPServer
 * @package FastD\Server
 */
class TCPServer extends Tcp
{
    /**
     * @param swoole_server $server
     * @param $fd
     * @param $data
     * @param $from_id
     * @return mixed
     */
    public function doWork(swoole_server $server, $fd, $data, $from_id)
    {
        try {
            $data = json_decode($data, true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $service = $data['cmd'];
        $method = $data['method'];

        $serverRequest = new ServerRequest(strtoupper($method), $service);

        if (isset($data['args'])) {
            if ('GET' == $serverRequest->getMethod()) {
                $serverRequest->withQueryParams($data['args']);
            } else {
                $serverRequest->withParsedBody($data['args']);
            }
        }

        $response = app()->handleRequest($serverRequest);

        unset($serverRequest);

        if (null !== config()->get('monitor', null)) {
            $server->task([
                'source' => isset($data['source']) ? $data['source'] : $server->connection_info($fd)['remote_ip'],
                'target' => get_local_ip(),
                'cmd' => $data['cmd'],
            ]);
        }

        return (string) $response->getBody();
    }
}