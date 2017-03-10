<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Console;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class Client
 * @package FastD\Console
 */
class Client extends Command
{
    public function configure()
    {
        $this
            ->setName('swoole:client')
            ->setHelp('This command allows you to create swoole client...')
            ->setDescription('Create new swoole tcp/udp client')
        ;

        $this
            ->addArgument('host', InputArgument::REQUIRED, 'Swoole server host address')
            ->addArgument('port', InputArgument::REQUIRED, 'Swoole server port')
            ->addArgument('cmd', InputArgument::REQUIRED, 'Request service name')
            ->addArgument('args', InputArgument::IS_ARRAY, 'Request service arguments', [])
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'Swoole server type', 'tcp')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $address = '';

        $address .= $input->getArgument('host') . ':' . $input->getArgument('port');

        $client = new \FastD\Swoole\Client($address);

        $args = [];
        foreach ($input->getArgument('args') as $arg) {
            if (false !== strpos($arg, ':')) {
                list($k, $v) = explode(':', $arg);
                $args[$k] = $v;
            } else {
                $args[] = $arg;
            }
        }

        $json = $client->send(json_encode([
            'cmd' => $input->getArgument('cmd'),
            'args' => $args,
        ]));

        $output->writeln(json_encode(json_decode($json), JSON_PRETTY_PRINT));
    }
}