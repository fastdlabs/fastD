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
//            $data = Json::decode($data);
            $data = [
                'cmd' => '/'
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $service = $data['cmd'];

        $serverRequest = new ServerRequest('GET', $service);

        $response = app()->handleRequest($serverRequest);

        return (string) $response->getBody();
    }
}