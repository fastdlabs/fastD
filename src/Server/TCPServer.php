<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Server;


use FastD\Http\ServerRequest;
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
            $data = Json::decode($data);
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

        return (string) $response->getBody();
    }
}