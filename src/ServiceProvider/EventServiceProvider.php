<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\ServiceProvider;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Inhere\Event\EventManager;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $em = new EventManager();

        $em->attach(static::ON_REQUEST, function () { echo 'ON_REQUEST'; });
        $em->attach(static::ON_EXCEPTION, function () { echo 'ON_EXCEPTION'; });
        $em->attach(static::ON_RESPONSE, function () { echo 'ON_RESPONSE'; });
        $em->attach(static::ON_SHUTDOWN, function () { echo 'ON_SHUTDOWN'; });

        $this->add('em', $em);

        unset($em);
    }
}