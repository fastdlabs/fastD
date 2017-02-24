<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Monitor;


use FastD\Swoole\Server;
use FastD\Swoole\Client\Async\AsyncClient;
use swoole_process;

/**
 * Class Report
 * @package FastD\Monitor
 */
class Monitor
{
    public static function report(Server $server, array $options = [])
    {
        $serviceName = config()->get('name');
        $ip = get_local_ip();
        $monitor = config()->get('monitor');
        $pid = !isset($server->getSwoole()->master_pid) ? file_get_contents($server->getPid()) : $server->getSwoole()->master_pid;
        $options = array_merge([
            'service'   => $serviceName,
            'pid'       => $pid,
            'sock'      => $server->getScheme(),
            'host'      => $ip,
            'port'      => $server->getPort(),
            'error'     => $server->getSwoole()->getLastError(),
            'time'      => time()
        ], $options, $server->getSwoole()->stats());

        foreach ($monitor as $value) {
            $client = new AsyncClient($value);
            $client
                ->connect(function ($client) use ($options) {
                    $client->send(json_encode($options));
                })
                ->receive(function ($client, $data) {

                })
                ->resolve()
            ;
        }
    }
}