<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Bundle\Server;

use FastD\Container\ContainerAware;
use FastD\Swoole\Server;

/**
 * Class ServerAware
 *
 * @package FastD\Bundle\Server
 */
abstract class ServerAware extends Server
{
    use ContainerAware;
}