<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;

use FastD\Migration\Migrate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Migration
 * @package FastD\Console
 */
class Migration extends Migrate
{
    public function configure()
    {
        parent::configure();
        $this->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'Database default connection', 'default');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = config()->get('database', []);
        $connection = $input->getOption('connection');
        if (!isset($config[$connection])) {
            throw new \RuntimeException(sprintf('Cannot found database "%s" config', $connection));
        }

        $this->config = $config[$connection];
        $this->config['dbname'] = $this->config['name'];

        $this->createConnection();

        return parent::execute($input, $output);
    }
}
