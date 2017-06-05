<?php

use FastD\Application;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
class DatabaseTest extends \FastD\TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__.'/../app/default');

        return $app;
    }

    public function createDatabase()
    {
        $config = config()->get('database.default');

        return new \FastD\Model\Database([
            'database_type' => isset($config['adapter']) ? $config['adapter'] : 'mysql',
            'database_name' => $config['name'],
            'server' => $config['host'],
            'username' => $config['user'],
            'password' => $config['pass'],
            'charset' => isset($config['charset']) ? $config['charset'] : 'utf8',
            'port' => isset($config['port']) ? $config['port'] : 3306,
            'prefix' => isset($config['prefix']) ? $config['prefix'] : '',
        ]);
    }

    public function testGoneAwayConnection()
    {
        $database = $this->createDatabase();
        $tables = $database->query('show tables;')->fetchAll();
        $this->assertTrue(true);
    }

    public function testInsert()
    {
        database()->insert('hello', [
            'content' => 'hello world',
            'user' => 'foo',
            'created' => date('Y-m-d H:i:s'),
        ]);
        $row = database()->get('hello', '*', [
            'id' => database()->id(),
        ]);
        $this->assertInternalType('integer', $row['id']);

        $this->assertSame(true, database()->has('hello', [
            'id' => $row['id'],
        ]));
    }
}
