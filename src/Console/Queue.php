<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;

use FastD\Process\Queue as SQ;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Queue.
 */
class Queue extends Command
{
    protected function configure()
    {
        $this->setName('queue');
        $this->addArgument('action');
        $this->setDescription('Run queue powered by swoole');
        $this->addOption('msg', '-m', InputOption::VALUE_OPTIONAL);
        $this->addOption('subscript', '-s', InputOption::VALUE_OPTIONAL);
        $this->addOption('name', '', InputOption::VALUE_OPTIONAL);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getParameterOption(['--name'], 'fastd-queue');
        $queue = new SQ($name);
        switch ($input->getArgument('action')) {
            case 'start':
                $queue->start();

                break;
            case 'stop':
                break;
            case 'status':
            default:
        }
        $queue->wait(function ($pid) {
            echo $pid;
        });
    }
}
