<?php

use FastD\Application;
use FastD\Http\ServerRequest;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
class TestCase extends \FastD\Test\TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__);

        return $app;
    }

    public function createRequest($method, $path, array $header = [], \Psr\Http\Message\StreamInterface $body = null, array $server = [])
    {
        return new ServerRequest($method, $path, $header, $body, $server);
    }
}
