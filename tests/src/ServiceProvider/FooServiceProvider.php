<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace ServiceProvider;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

class FooServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $container->add('foo', new class {
            public $name = 'foo';
        });
    }
}