<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Process
 * @package FastD\Console
 */
class Process extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('process:create');
        $this->addArgument('process', InputArgument::REQUIRED);
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'set process name', app()->getName());
        $this->addOption('daemon', '-d', InputOption::VALUE_NONE, 'set process daemonize.');
        $this->setDescription('Create new processor.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = $input->getArgument('process');
        $process = str_replace(':', '\\', $process);
        $name = $input->getOption('name');
        if (!class_exists($process)) {
            throw new \RuntimeException(sprintf('Class "%s" is not found.', $process));
        }
        $process = new $process($name);
        if (!($process instanceof \FastD\Swoole\Process)) {
            throw new \RuntimeException('Process must be instance \FastD\Swoole\Process');
        }
        if ($input->hasParameterOption(['--daemon', '-d'])) {
            $process->daemon();
        }
        $pid = $process->start();
        echo $pid;
    }
}