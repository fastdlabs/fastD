<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console\Process;

use FastD\Swoole\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Process.
 */
class Creator extends Command
{
    protected function configure()
    {
        $this->setName('process:create');
        $this->addArgument('process', InputArgument::REQUIRED);
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'set process name', app()->getName());
        $this->addOption('daemon', '-d', InputOption::VALUE_NONE, 'set process daemonize.');
        $this->setDescription('Create new processor.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
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
        if (!($process instanceof Process)) {
            throw new \RuntimeException('Process must be instance \FastD\Swoole\Process');
        }
        if ($input->hasParameterOption(['--daemon', '-d'])) {
            $process->daemon();
        }
        $path = $this->targetDirectory();
        $pid = $process->start();
        $file = $path.'/'.$name.'.pid';
        file_put_contents($file, $pid);
        $output->writeln(sprintf('Process %s is started, pid: %s', $name, $pid));
        $output->writeln(sprintf('Pid file save is %s', $file));

        return $pid;
    }

    protected function targetDirectory()
    {
        $path = app()->getPath().'/runtime/process';
        if (!file_exists($path)) {
            mkdir($path, true, 0755);
        }

        return $path;
    }
}
