<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace Processor;

use FastD\Process\AbstractProcess;
use swoole_process;

class DemoProcessor extends AbstractProcess
{
    public function handle(swoole_process $swoole_process)
    {
        timer_tick(1000, function ($id) {
            static $index = 0;
            ++$index;
            echo $index.PHP_EOL;
            if (3 === $index) {
                timer_clear($id);
            }
        });
    }
}
