<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Standard\Tests;

use FastD\Standard\Bundle;

class BundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBundle()
    {
        $bundle = new Bundle();

        $this->assertEquals(Bundle::class, $bundle->getName());

        $this->assertEquals(realpath(__DIR__ . '/Framework'), $bundle->getRootPath());
    }
}
