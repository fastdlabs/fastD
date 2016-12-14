<?php
use FastD\Application;
use FastD\Http\ServerRequest;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__ . '/src');

        return $app;
    }

    public function createRequest($method, $path)
    {
        return new ServerRequest($method, $path);
    }
}