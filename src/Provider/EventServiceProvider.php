<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Provider;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Event\BootstrapListener;
use FastD\Event\EventDispatcher;
use FastD\Event\RequestListener;
use FastD\Event\ResponseListener;

class EventServiceProvider implements ServiceProviderInterface
{
    const SERVICE_NAME = 'event';

    public function register(Container $container)
    {
        $dispatch = new EventDispatcher();

        $dispatch->on('bootstrap', BootstrapListener::class . '::handle');
        $dispatch->on('request', RequestListener::class . '::handle');
        $dispatch->on('response', ResponseListener::class . '::handle');
        $dispatch->on('middleware', '');
        $dispatch->on('store', '');
        $dispatch->on('repository', '');
        $dispatch->on('event', '');

        $container->add($this->getName(), $dispatch);
    }

    public function getName()
    {
        return static::SERVICE_NAME;
    }
}