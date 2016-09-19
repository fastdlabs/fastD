<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Standard\Commands;

use FastD\Console\Command\Command;
use FastD\Container\Container;
use FastD\Container\ContainerAware;

/**
 * Class CommandAware
 *
 * @package FastD\Standard\Commands
 */
abstract class CommandAware extends Command
{
    use ContainerAware;

    /**
     * CommandAware constructor.
     *
     * @param Container|null $container
     */
    public function __construct(Container $container = null)
    {
        $this->setContainer($container);
    }
}