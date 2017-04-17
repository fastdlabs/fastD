<?php
use FastD\Application;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */
class NotDBTest extends FastD\TestCase
{
    public function createApplication()
    {
        $app = new Application(__DIR__ . '/app/default');

        return $app;
    }

    public function testNotDatabaseSetting()
    {
        if ([] === config()->get('database')) {
            $this->expectException(LogicException::class);
            database();
        }
    }
}
