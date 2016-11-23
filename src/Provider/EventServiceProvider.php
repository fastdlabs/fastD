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
use FastD\Event\EventDispatcher;

class EventServiceProvider implements ServiceProviderInterface
{
    const SERVICE_NAME = 'kernel.event';

    public function register(App $app)
    {
        $dispatch = new EventDispatcher();

        $dispatch->on('request', function () {
            echo 'hello world';
        });

        $app->getContainer()->add($this->getName(), $dispatch);
    }

    public function getName()
    {
        return static::SERVICE_NAME;
    }
}