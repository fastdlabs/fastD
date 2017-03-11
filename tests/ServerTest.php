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

        $server->bootstrap();
    }
}
