<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\Discovery;


use FastD\Swoole\Client\Sync\SyncClient;
use FastD\Swoole\Process;
use swoole_process;

/**
 * Class Discover
 * @package FastD\Discovery
 */
class Discover extends Process
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
        $pid = !isset($this->server->getSwoole()->master_pid) ? file_get_contents($this->server->getPid()) : $this->server->getSwoole()->master_pid;
        timer_tick(1000, function () use ($discoveries, $serviceName, $ip, $pid) {
            $data = [
                'service'   => $serviceName,
                'pid'       => $pid,
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
                    $client = new SyncClient($server);
                    $client
                        ->connect(function ($client) use ($ip, $data) {
                            $client->send($data);
                        })
                        ->receive(function ($client, $data) {

                        })
                        ->resolve()
                    ;
                    unset($client);
                } catch (\Exception $e) {
                    continue;
                }
            }
        });
    }
}