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
use FastD\Swoole\Server\TCP;
use swoole_server;

/**
 * Class TCPServer
 * @package FastD\Server
 */
class TCPServer extends TCP
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

        $request = new ServerRequest(strtoupper($method), $service);

        if (isset($data['args'])) {
            if ('GET' == $request->getMethod()) {
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

        $content = (string) $response->getBody();

        app()->shutdown($request, $response);

        return $content;
    }
}