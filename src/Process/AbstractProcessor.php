<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Process;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AbstractProcessor
 * @package FastD\Process
 */
abstract class AbstractProcessor extends Command
{
    protected function configure()
    {
        $this->addOption('daemon', '-d', InputOption::VALUE_NONE, 'set process daemonize.');
    }
}