<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD;

use Exception;
use FastD\Swoole\Client as SwooleClient;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class Client.
 */
class Client
{
    /**
     * @param InputInterface $input
     *
     * @return SwooleClient
     */
    public function connect(InputInterface $input)
    {
        return new SwooleClient($input->getArgument('schema'), false, false);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output = null)
    {
        if (null === $output) {
            $output = new ConsoleOutput();
        }

        $helper = new QuestionHelper();
        $question = new Question('Continue with this action <info>[get /hello foo:bar] ? </info>', false);

        $action = $helper->ask($input, $output, $question);
        if (empty($action)) {
            return $this->execute($input, $output);
        }

        if ('quit' === trim($action)) {
            $output->writeln('quit');

            return 0;
        }

        $array = preg_split('/\s+/', $action);
        for ($i = 0; $i < 3; ++$i) {
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

        try {
            $json = $this->connect($input)->send(json_encode([
                'method' => $method,
                'path' => $path,
                'args' => $args,
            ]));
            $content = json_encode(json_decode($json, true), JSON_PRETTY_PRINT);
            $output->writeln('<info>'.$content.'</info>');
            $this->execute($input, $output);
        } catch (Exception $e) {
            echo $e->getMessage();

            throw $e;
        }
    }
}
