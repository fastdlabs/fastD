<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\Server;


use FastD\Application;
use FastD\Servitization\PoolInterface;
use FastD\Swoole\Server\TCP;
use swoole_server;

/**
 * Class MonitorStatusServer
 * @package FastD\Servitization\Server
 */
class MonitorStatusServer extends TCP
{
    /**
     * @param swoole_server $server
     * @param int $worker_id
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
     * @param $from_id
     */
    public function doConnect(swoole_server $server, $fd, $from_id)
    {
        $server->send($fd, sprintf('server: %s %s', app()->getName(), Application::VERSION) . PHP_EOL);
    }

    /**
     * @param swoole_server $server
     * @param $fd
     * @param $data
     * @param $from_id
     * @return mixed
     */
    public function doWork(swoole_server $server, $fd, $data, $from_id)
    {
        switch (trim($data)) {
            case 'quit':
                $server->send($fd, 'connection closed');
                $server->close($fd);
                break;
            case 'status':
            default:
                $info = $server->stats();
                $status = '';
                foreach ($info as $key => $value) {
                    $status .= "[" .date('Y-m-d H:i:s'). "]: " . $key . ': ' . $value . PHP_EOL;
                }
                $server->send($fd, $status);
                break;
        }
    }
}