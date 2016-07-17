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

namespace FastD\Framework\Tests\Framework;

use FastD\Framework\App;
use FastD\Framework\Tests\WebTestCase;

/**
 * Class BootstrapTest
 *
 * @package FastD\Framework\Tests\KernelTesting
 */
class BootstrapTest extends WebTestCase
{
    public function testKernel()
    {
        $app = new App([]);

        $app->bootstrap();

        $this->assertEquals('dev', $app->getEnvironment());
    }
}