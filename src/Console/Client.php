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
            ->setDescription('Create new swoole tcp client')
        ;

        $this
            ->addArgument('host', InputArgument::REQUIRED, 'Swoole server host address')
            ->addArgument('port', InputArgument::REQUIRED, 'Swoole server port')
        ;
    }

    /**
     * @param $host
     * @param $port
     * @return \FastD\Swoole\Client
     */
    protected function connectToServer($host, $port)
    {
        $address = '';

        $address .= $host . ':' . $port;

        return new \FastD\Swoole\Client($address);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Continue with this action <info>[get /hello foo:bar] ? </info>', false);

        $action = $helper->ask($input, $output, $question);
        if (empty($action)) {
            throw new \RuntimeException('Not action input.');
        }

        $array = preg_split('/\s+/', $action);
        for ($i = 0; $i < 3; $i++) {
            if (!isset($array[$i])) {
                $array[$i] = null;
            }
        }

        $method = $array[0];
        $path = empty($array[1]) ? '/' : $array[1];

        $args = [];
        if (!empty($array[2])) {
            $arguments = explode(',', $array[2]);
            foreach ($arguments as $arg) {
                if (false !== strpos($arg, ':')) {
                    list($k, $v) = explode(':', $arg);
                    $args[$k] = $v;
                } else {
                    $args[] = $arg;
                }
            }
        }

        $json = $this->connectToServer($input->getArgument('host'), $input->getArgument('port'))->send(json_encode([
            'method' => $method,
            'path' => $path,
            'args' => $args,
        ]));

        $content = json_encode(json_decode($json, true), JSON_PRETTY_PRINT);
        $output->writeln('<info>' . $content . '</info>');
    }
}