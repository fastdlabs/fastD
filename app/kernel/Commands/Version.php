<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/5/21
 * Time: 上午11:56
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Kernel\Commands;

use FastD\Console\Command;
use FastD\Console\IO\Input;
use FastD\Console\IO\Output;
use Kernel\AppKernel;

class Version extends Command
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'app:version';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {

    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        $output->write('Doboee application version: ');
        $output->writeln(AppKernel::VERSION, Output::STYLE_SUCCESS);
    }
}