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

        if (false === $route = app()->get('router')->getRoute($data['cmd'])) {
            $server->send($fd, 'route ' . $data['cmd'] . ' is not found');
            $server->close($fd);
            return -1;
        }

        $request = new ServerRequest($route->getMethod(), $route->getPath());

        if (isset($data['args'])) {
            if ('GET' == $request->getMethod()) {
                $request->withQueryParams($data['args']);
            } else {
                $request->withParsedBody($data['args']);
            }
        }

        try {
            app()->add('request', $request);
            $response = app()->get('dispatcher')->callMiddleware($route, $request);
        } catch (\Exception $e) {
            $response = app()->handleException($e);
        }

        $content = (string) $response->getBody();
        $server->send($fd, $content);
        if (isset($data['keep_alive'])) {
            false === $data['keep_alive'] && $server->close($fd);
        }
        app()->shutdown($request, $response);
        return 0;
    }
}