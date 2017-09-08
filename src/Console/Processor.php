<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;

use FastD\Swoole\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Process.
 */
class Processor extends Command
{
    /**
     * php bin/console process {name} {args} {options}.
     */
    protected function configure()
    {
        $this->setName('process');
        $this->addArgument('process', InputArgument::OPTIONAL);
        $this->addOption('pid', '-p', InputOption::VALUE_OPTIONAL, 'set process pid path.');
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'set process name.', app()->getName());
        $this->addOption('daemon', '-d', InputOption::VALUE_NONE, 'set process daemonize.');
        $this->addOption('list', '-l', InputOption::VALUE_NONE, 'show all processes.');
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

        if ($input->hasParameterOption(['--list', '-l']) || empty($process)) {
            return $this->showProcesses($input, $output);
        }

        $processes = config()->get('processes', []);

        if (!isset($processes[$process])) {
            throw new \RuntimeException(sprintf('Process %s cannot found', $process));
        }

        $processor = $processes[$process];
        if (!class_exists($processor)) {
            throw new \RuntimeException(sprintf('Class "%s" is not found.', $process));
        }
        $name = $input->getOption('name');
        $process = new $processor($name);
        if (!($process instanceof Process)) {
            throw new \RuntimeException('Process must be instance of \FastD\Swoole\Process');
        }
        if ($input->hasParameterOption(['--daemon', '-d'])) {
            $process->daemon();
        }

        $path = $this->targetDirectory($input);
        $file = $path.'/'.$name.'.pid';

        $pid = $process->start();
        file_put_contents($file, $pid);

        $output->writeln(sprintf('Process %s is started, pid: %s', $name, $pid));
        $output->writeln(sprintf('Pid file save is %s', $file));

        return $pid;
    }

    /**
     * @param InputInterface $input
     *
     * @return string
     */
    protected function targetDirectory(InputInterface $input)
    {
        $pid = $input->getParameterOption(['--path', '-p']);

        if (empty($pid)) {
            $path = app()->getPath().'/runtime/process';
        } else {
            $path = dirname($pid);
        }
        if (!file_exists($path)) {
            mkdir($path, true, 0755);
        }

        return $path;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function showProcesses(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(array('Process', 'Pid', 'Status', 'Start At', 'Runtime'));
        $rows = [];
        foreach (config()->get('processes', []) as $name => $processor) {
            $rows[] = [
                $name,
                '',
                '',
                '',
            ];
        }
        $table->setRows($rows);
        $table->render();

        return 0;
    }
}
