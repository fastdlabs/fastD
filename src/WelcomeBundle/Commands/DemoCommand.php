<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/5/7
 * Time: 下午5:15
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace WelcomeBundle\Commands;

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
        return 'welcome:bundle';
    }

    /**
     * @return void
     */
    public function configure()
    {
        $this
            ->setArgument('name')
            ->setOption('age')
        ;
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return int
     */
    public function execute(Input $input, Output $output)
    {
        $output->write('welcome fastd. Argument = ' . $input->get('name') . ' Options = ' . $input->get('age'));
    }
}