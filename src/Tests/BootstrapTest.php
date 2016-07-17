<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/7/18
 * Time: 上午1:06
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Tests;

use FastD\App;

/**
 * Class BootstrapTest
 *
 * @package FastD\Framework\Tests\KernelTesting
 */
class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    public function testKernel()
    {
        $app = new App(include __DIR__ . '/../../bootstrap.php');

        $app->bootstrap();

        print_r($app);
    }
}