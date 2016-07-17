<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/26
 * Time: 下午2:17
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Framework\Tests;

/**
 * Class WebTestCase
 *
 * @package FastD\Framework\Tests
 */
abstract class WebTestCase extends FrameworkTestCase
{
    /**
     * @return \FastD\Framework\Tests\TestClient
     */
    public static function createClient()
    {
        static::kernelBootstrap();

        return static::$application
            ->getContainer()
            ->singleton('kernel.dispatch')
            ->dispatch('handle.testing')
            ;
    }
}