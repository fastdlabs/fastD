<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event;

use FastD\App;
use FastD\Provider\ConfigurableServiceProvider;
use FastD\Provider\RouteServiceProvider;
use FastD\Provider\StoreServiceProvider;

class BootstrapListener
{
    public static function handle(App $app)
    {
        $app->register(new ConfigurableServiceProvider());
        $app->register(new RouteServiceProvider());
        $app->register(new StoreServiceProvider());
    }
}