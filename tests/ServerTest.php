<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Server;


class ServerTest extends TestCase
{
    public function createServer()
    {
        return new Server($this->createApplication());
    }

    public function testServerInit()
    {
        $server = $this->createServer();

        $this->assertEquals([
            'task_worker_num' => 20,
            'task_tmpdir' => '/tmp',
            'pid_file' => '/tmp/fast-d.pid',
            'worker_num' => 10
        ], $server->getSwoole()->setting);
    }
}
