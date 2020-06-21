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
 * Class Client.
 */
class Client extends Command
{
    protected function configure()
    {
        $this
            ->setName('client')
            ->addArgument('uri', InputArgument::REQUIRED)
            ->addOption('data', '-d', InputOption::VALUE_OPTIONAL, 'send data')
            ->addOption('json', null, InputOption::VALUE_NONE, 'output json format')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $uri = $input->getArgument('uri');

        $data = $input->getParameterOption(['--data', '-d']);
        if (file_exists($data)) {
            $data = file_get_contents($data);
        }

        client()->createRequest($uri);

        if (false === strpos(client()->getProtocol(), 'http') && empty($data)) {
            $data = ' ';
        }

        $response = client()->send($data);

        if ($input->hasParameterOption(['--json'])) {
            $json = $response->getContents();
            $header = '';
            foreach ($response->getHeaders() as $key => $value) {
                $header .= $key.': '.$response->getHeaderLine($key).PHP_EOL;
            }
            $response = $header."\r\n".json_encode(json_decode($json, true), JSON_PRETTY_PRINT);
        } elseif (false !== strpos(client()->getProtocol(), 'http')) {
            $response = (string) $response;
        } else {
            $response = $response->getContents();
        }

        $output->writeln($response);

        return 0;
    }
}
