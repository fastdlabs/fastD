<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Process;


use swoole_process;

class Queue extends \FastD\Swoole\Queue
{
    public function handle(swoole_process $process)
    {
        while (true) {
            $recv = $process->pop();
            echo "From Master: $recv\n";
        }
    }
}