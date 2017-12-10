<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Client.
 */
class Client extends Console
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->add(new \FastD\Console\Client());
    }

    /**
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @return int
     * @throws \Exception
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $argv = $_SERVER['argv'];
        $script = array_shift($argv);
        array_unshift($argv, 'client');
        array_unshift($argv, $script);

        return parent::run(new ArgvInput($argv), $output);
    }
}
