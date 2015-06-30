<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/6/30
 * Time: 下午5:41
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Api\V1\Commands;

use FastD\Console\Command;
use FastD\Console\IO\Input;
use FastD\Console\IO\Output;

class Test extends Command
{

    public function getName()
    {
        return 'api:demo';
    }

    public function configure()
    {
        // TODO: Implement configure() method.
    }

    public function execute(Input $input, Output $output)
    {
        $output->writeln('api demo');
    }
}