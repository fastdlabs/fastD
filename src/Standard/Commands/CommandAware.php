<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/5/9
 * Time: 下午10:51
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
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