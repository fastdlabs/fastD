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
use FastD\Config\Config;
use FastD\Contract\ServiceProviderInterface;

class ConfigurableServiceProvider implements ServiceProviderInterface
{
    const SERVICE_NAME = 'kernel.config';

    public function register(App $app)
    {
        $app->getContainer()->add($this->getName(), new Config());
    }

    public function getName()
    {
        return static::SERVICE_NAME;
    }
}