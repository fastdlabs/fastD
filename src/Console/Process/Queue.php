<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace Console\Process;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Queue extends Command
{
    protected function configure()
    {
        $this->addArgument('action');
        $this->setDescription('Run queue powered by swoole');
        $this->addOption('name', '', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getParameterOption(['--name'], 'fastd-queue');
        $queue = new \FastD\Process\Queue($name);
        switch ($input->getArgument('action')) {
            case 'start':
                $queue->start();
                break;
            case 'stop':
                break;
            case 'status':
            default:

        }
    }
}