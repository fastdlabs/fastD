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
use FastD\Routing\RouteCollection;

class RouteServiceProvider implements ServiceProviderInterface
{
    const SERVICE_NAME = 'routing';

    public function register(App $app)
    {
        $app->getContainer()->add($this->getName(), new RouteCollection());

        include $app->getAppPath() . '/route/routes.php';
    }

    public function getName()
    {
        return static::SERVICE_NAME;
    }
}