<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/30
 * Time: 下午11:16
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Welcome\Commands;

use FastD\Console\Command\Command;
use FastD\Console\IO\Input;
use FastD\Console\IO\Output;

class DemoCommand extends Command
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'demo:command';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {
        // TODO: Implement configure() method.
    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        $output->writeln('hello world');
    }
}