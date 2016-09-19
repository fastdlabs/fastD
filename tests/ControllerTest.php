<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Tests;

use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Database\Drivers\DriverInterface;
use FastD\Database\Fdb;
use FastD\Http\Request;
use FastD\Standard\Controllers\Controller;
use FastD\Storage\Redis\Redis;
use FastD\Storage\Storage;
use Routes;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testConfig()
    {
        $controller = new Controller();

        $config = new Config();

        $config->set('name', 'janhuang');

        $controller->setContainer(new Container([
            'kernel.config' => $config,
        ]));

        $this->assertEquals('janhuang', $controller->getParameters('name'));
    }

    public function testDriver()
    {
        $controller = new Controller();

        $config = new Config();

        $config->set('database', [
            'local' => [
                'database_host'      => '127.0.0.1',
                'database_port'      => '3306',
                'database_name'      => 'test',
                'database_user'      => 'root',
                'database_pwd'       => '123456'
            ]
        ]);

        $controller->setContainer(new Container([
            'kernel.config' => $config,
            'kernel.database' => Fdb::class
        ]));

        $driver = $controller->getDriver('local');

        $this->assertInstanceOf(DriverInterface::class, $driver);
    }

    public function testAsset()
    {
        $controller = new Controller();

        $controller->setContainer(new Container([
            'kernel.storage' => Storage::class
        ]));
    }
}
