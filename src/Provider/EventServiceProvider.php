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
use FastD\Event\EventDispatcher;
use FastD\Listener\RequestListener;
use FastD\Listener\ResponseListener;

class EventServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $dispatch = new EventDispatcher();

        // listener events
        $dispatch->on('request', RequestListener::class . '::handle');
        $dispatch->on('response', ResponseListener::class . '::handle');

        $container->add('event', $dispatch);
    }
}