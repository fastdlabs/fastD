<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Server;


use FastD\Application;
use FastD\Container\Container;
use FastD\Http\Stream;
use FastD\Runtime;
use FastD\Swoole\Handlers\HTTPHandler;
use FastD\Swoole\HTTP;
use FastD\Swoole\Server\ServerAbstract;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class App.
 */
class Swoole extends Runtime
{
}
