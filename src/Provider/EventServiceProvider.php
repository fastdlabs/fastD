<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Provider;

use FastD\App;
use FastD\Contract\ServiceProviderInterface;
use FastD\Event\BootstrapListener;
use FastD\Event\EventDispatcher;
use FastD\Event\RequestListener;

class EventServiceProvider implements ServiceProviderInterface
{
    const SERVICE_NAME = 'event';

    public function register(App $app)
    {
        $dispatch = new EventDispatcher();

        $dispatch->on('bootstrap', new BootstrapListener());
        $dispatch->on('request', new RequestListener());

        $app->getContainer()->add($this->getName(), $dispatch);
    }

    public function getName()
    {
        return static::SERVICE_NAME;
    }
}