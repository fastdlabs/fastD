<?php

namespace FastD\Console\Command;

use FastD\Routing\Route;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RouteList extends Command
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
        $this->render($input, $output);
        return 0;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function render(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $rows = [];
        $table->setHeaders(array('Method', 'Path', 'Regex', 'Handle', 'Middleware'));
        [$statics, $dynamices] = app()->get('router')->routeMaps->getRoutes();
        foreach ($statics as $method => $static) {
            foreach ($static as $path => $route) {
                $rows[] = $this->route($path, $route);
            }
        }
        foreach ($dynamices as $dynamice) {
            foreach ($dynamice as $method => $routes) {
                foreach ($routes['routeMap'] as $route) {
                    $rows[] = $this->route($route->regex, $route);
                }
            }
        }

        $table->setRows($rows);
        $table->render();
    }

    protected function route($path, Route $route)
    {
        $m = [];
        $middleware = $route->getMiddlewares();
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

        $callback = $route->getHandler();
        if (is_object($callback)) {
            $callback = get_class($callback);
        } elseif (is_array($callback)) {
            if (is_object($callback[0])) {
                $callback[0] = get_class($callback[0]);
            }
            $callback = implode('@', $callback);
        }

        return [
            $route->method,
            $path,
            $route->regex,
            $callback,
            implode(',', $m),
        ];
    }
}
