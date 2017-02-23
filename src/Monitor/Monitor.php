<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Monitor;


use FastD\Swoole\Client\Sync\SyncClient;
use swoole_process;

/**
 * Class Report
 * @package FastD\Monitor
 */
class Monitor
{
    public static function report(array $options = [])
    {
        $monitor = config()->get('monitor');
        foreach ($monitor as $value) {
            $client = new SyncClient($value);
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