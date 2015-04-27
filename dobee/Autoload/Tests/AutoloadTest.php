<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/23
 * Time: 下午10:45
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Autoload\Tests;

use Demo\Demo;
use Dobee\Autoload\ClassLoader;
use Test\Test;

class AutoloadTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        ClassLoader::getLoader(array(
            'Demo\\' => array(__DIR__ . '/demo'),
            'Test\\' => array(__DIR__ . '/test'),
            '\\' => array(__DIR__),
            'Dobee\\' => array(__DIR__ . '/../../../Dobee'),
        ));
    }

    public function testLoader()
    {
        print_r(new Test());

        print_r(new Demo());

        print_r(new \Root());

        print_r(new Dobee());
    }
}