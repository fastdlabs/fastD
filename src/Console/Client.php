<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Console;


use FastD\Swoole\Client\Sync\TCP;
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
            ->addOption('method', 'm', InputOption::VALUE_OPTIONAL, 'Request method', 'GET')
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
        switch ($input->getOption('type')) {
            case 'tcp':
            default:
                $address .= 'tcp://';
                $client = TCP::class;
        }

        $address .= $input->getArgument('host') . ':' . $input->getArgument('port');

        $questionHelper = $this->getHelper('question');
        $question = new Question('Please enter the send data.(default: <info>Hello World</info>, Enter (<info>exit/quit</info>) can be exit console.): ', 'Hello World');
        $sendData = $questionHelper->ask($input, $output, $question);

        if ('quit' === $sendData || 'exit' === $sendData) {
            return 0;
        }

        $method = $input->getParameterOption(['--method', '-m']);

        $client = new $client($address);

        $json = $client->send(json_encode([
            'cmd' => $sendData,
            'method' => $method,
        ]));

        $output->writeln(json_encode(json_decode($json), JSON_PRETTY_PRINT));
    }
}