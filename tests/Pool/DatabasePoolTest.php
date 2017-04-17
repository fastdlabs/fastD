<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
use FastD\Pool\DatabasePool;

class DatabasePoolTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DatabasePool
     */
    protected $pool;

    public function setUp()
    {
        $this->pool = new DatabasePool([
            'default' => [
                'adapter' => 'mysql',
                'name' => 'ci',
                'host' => '127.0.0.1',
                'user' => 'root',
                'pass' => '',
                'charset' => 'utf8',
                'port' => 3306,
            ],
        ]);
    }

    public function testPool()
    {
        $this->assertInstanceOf(\Medoo\Medoo::class, $this->pool->getConnection('default'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetNotExistsConnection()
    {
        $this->pool->getConnection('not_exists');
    }

    public function testInitConnectionPool()
    {
        $this->pool->initPool();
    }
}
