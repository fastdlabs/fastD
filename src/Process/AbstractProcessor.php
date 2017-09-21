<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Process;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AbstractProcessor.
 */
abstract class AbstractProcessor extends Command
{
    protected function configure()
    {
        $this->addOption('daemon', '-d', InputOption::VALUE_NONE, 'set process daemonize.');
    }
}
