<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace ServiceProvider;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Symfony\Component\Console\Command\Command;

class Foo
{
    public $name = 'foo';
}

class DemoConsole extends Command
{
    protected function configure()
    {
        $this->setName('app:demo:console');
    }
}

class FooServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function register(Container $container)
    {
        $container->add('foo', new Foo());
        config()->merge([
            'consoles' => [
                DemoConsole::class,
            ],
        ]);
    }
}
