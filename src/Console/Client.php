<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Console;


use FastD\Swoole\Async\Http;
use FastD\Swoole\Client\Sync\SyncClient;
use FastD\Swoole\Client\Sync\TCP;
use FastD\Swoole\Client\Sync\UDP;
use FastD\Swoole\Swoole;
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
            ->setName('client')
            ->setHelp('This command allows you to create swoole client...')
            ->setDescription('Create new swoole client')
        ;

        $this
            ->addArgument('host', InputArgument::REQUIRED, 'Swoole server host address')
            ->addArgument('port', InputArgument::REQUIRED, 'Swoole server port')
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
            case 'http':
                $address .= 'http://';
                $client = Http::class;
                break;
            case 'tcp':
                $address .= 'tcp://';
                $client = SyncClient::class;
                break;
            case 'udp':
                $client = UDP::class;
                $address .= 'udp://';
                break;
            case 'ws':
                $client = WebSocket::class;
                $address .= 'ws://';
                break;
            default:
                throw new \LogicException('Not support server type ' . $input->getOption('type'));
        }

        $address .= $input->getArgument('host') . ':' . $input->getArgument('port');

        $client = new $client($address);

        $questionHelper = $this->getHelper('question');
        $question = new Question('Please enter the send data.(default: <info>Hello World</info>, Enter (<info>exit/quit</info>) can be exit console.): ', 'Hello World');
        $sendData = $questionHelper->ask($input, $output, $question);

        if ('quit' === $sendData || 'exit' === $sendData) {
            return 0;
        }

        $client
            ->connect(function ($client) use ($sendData) {
                $client->send($sendData);
            })
            ->receive(function ($client, $data) use ($input, $output) {
                $output->writeln('<info>Receive: </info>' . $data);
                $this->execute($input, $output);
            })
            ->resolve()
        ;
    }
}