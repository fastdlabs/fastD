<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/18
 * Time: 下午4:33
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace FastD\Framework\Bundle\Commands;

use FastD\Console\Commands\Command;
use FastD\Console\Format\Input;
use FastD\Console\Format\Output;

class Compile extends Command
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'compile';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {
        $this->setDescription('Thank for you use Compile tool.');
    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        // TODO: Implement execute() method.
    }
}