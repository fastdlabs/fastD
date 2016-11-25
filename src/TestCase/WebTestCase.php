<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
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