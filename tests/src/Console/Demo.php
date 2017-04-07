<?php

namespace Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class Demo extends Command
{
    public function configure()
    {
        $this->setName('demo');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('hello world');
    }
}
