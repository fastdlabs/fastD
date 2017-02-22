<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Monitor;


use FastD\Swoole\Client\Async\AsyncClient;
use FastD\Swoole\Client\Sync\SyncClient;
use FastD\Swoole\Process;
use swoole_process;

/**
 * Class Report
 * @package FastD\Monitor
 */
class Report extends Process
{
    /**
     * @param swoole_process $swoole_process
     * @return mixed
     */
    public function handle(swoole_process $swoole_process)
    {
        $discoveries = config()->get('discovery');
        $serviceName = config()->get('name');
        $ip = get_local_ip();

        timer_tick(1000, function () use ($discoveries, $serviceName, $ip) {
            $data = [
                'service'   => $serviceName,
                'pid'       => $this->server->getSwoole()->master_pid,
                'sock'      => $this->server->getScheme(),
                'host'      => $ip,
                'port'      => $this->server->getPort(),
                'error'     => $this->server->getSwoole()->getLastError(),
                'time'      => time()
            ];
            $data = array_merge($data, $this->server->getSwoole()->stats());
            $data = json_encode($data);
            foreach ($discoveries as $server) {
                try {
                    $client = new SyncClient($server, SWOOLE_SOCK_UDP);
                    $client
                        ->connect(function ($client) use ($ip, $data) {
                            $client->send($data);
                        })
                        ->receive(function ($client, $data) {
                            $client->close();
                        })
                        ->resolve()
                    ;
                } catch (\Exception $e) {
                    continue;
                }
            }
        });
    }
}