<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Console;

use FastD\Http\ServerRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Route.
 */
class Routing extends Command
{
    public function configure()
    {
        $this
            ->setName('route')
            ->setHelp('Show all route')
            ->setDescription('Show you defined routes.')
            ->addArgument('method', InputArgument::OPTIONAL, 'Route request method.')
            ->addArgument('path', InputArgument::OPTIONAL, 'Route path.')
            ->addOption('data', 'd', InputOption::VALUE_OPTIONAL, 'Path request data.')
            ->addOption('all', 'a', InputOption::VALUE_OPTIONAL, 'Test all routes')
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
        if (empty($path = $input->getArgument('path'))) {
            $this->render($input, $output);
        } else {
            $this->test($input->getArgument('method'), $path, $input, $output);
        }

        return 0;
    }

    /**
     * @param $method
     * @param $path
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function test($method, $path, InputInterface $input, OutputInterface $output)
    {
        $request = new ServerRequest($method, $path, [
            'User-Agent' => 'FastD Console/'.version(),
        ]);

        $response = app()->handleRequest($request);

        $output->writeln(sprintf('Method: <info>%s</info>', $method));
        $output->writeln(sprintf('Path: <info>%s</info>', $path).PHP_EOL);

        $headersLIne = '';

        foreach ($response->getHeaders() as $name => $header) {
            $headersLIne .= $name.': '.$response->getHeaderLine($name).PHP_EOL;
        }

        $body = (string) $response->getBody();
        $body = json_encode(json_decode($body, true), JSON_PRETTY_PRINT);
        $output->writeln($headersLIne.PHP_EOL.$body);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function render(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $rows = [];
        $table->setHeaders(array('Path', 'Method', 'Callback', 'Middleware'));
        foreach (route()->aliasMap as $routes) {
            foreach ($routes as $route) {
                $m = [];
                $middleware = $route->getMiddleware();
                if (is_array($middleware)) {
                    foreach ($middleware as $value) {
                        if (is_object($value)) {
                            $m[] = get_class($value);
                        } else {
                            $m[] = $value;
                        }
                    }
                } elseif (is_object($middleware)) {
                    $m[] = get_class($middleware);
                }
                $rows[] = [
                    $route->getPath(),
                    $route->getMethod(),
                    $route->getCallback(),
                    implode(',', $m),
                ];
            }
        }

        $table->setRows($rows);

        $table->render();
    }
}
