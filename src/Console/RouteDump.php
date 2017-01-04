<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Console;

use FastD\Routing\Route;
use FastD\Routing\RouteCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class RouteDump
 * @package FastD\Console
 */
class RouteDump extends Command
{
    /**
     * @return void|$this
     */
    public function configure()
    {
        $this->setName('route:dump');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }

    /**
     * @param RouteCollection $collection
     * @param Output $output
     * @param null $bundleName
     * @param int $style
     * @return int
     */
    public function showRouteCollections(RouteCollection $collection, Output $output, $bundleName = null, $style = self::STYLE_DETAIL)
    {
        $allRoutes = [];

        $bundles = $this->getContainer()->singleton('kernel')->getBundles();
        foreach ($bundles as $bundle) {
            foreach ($collection as $name => $route) {
                $callback = $route->getCallback();
                echo substr($callback, 0, strlen($bundle->getNamespace())) . PHP_EOL;
                if ($bundle->getNamespace() === substr($callback, 0, strlen($bundle->getNamespace()))) {
                    $allRoutes[$bundle->getName()][] = $route;
                }
            }
        }

        if ($style === self::STYLE_LIST) {
            $output->write('Name' . str_repeat(' ', 25 - strlen('name')));
            $output->write('Method' . str_repeat(' ', 15 - strlen('Method')));
            $output->write('Scheme' . str_repeat(' ', 15 - strlen('Schema')));
            $output->writeln('Path');
        }

        if (null === $bundleName) {
            foreach ($allRoutes as $name => $routes) {
                $output->writeln('<success>Bundle: </success>' . $name);
                foreach ($routes as $route) {
                    $this->formatOutput($route, $output, $style);
                }
            }
            return 0;
        }

        foreach ($allRoutes as $name => $routes) {
            if ($name == $bundleName) {
                $output->writeln('<success>Bundle: </success>' . $name);
                foreach ($routes as $route) {
                    $this->formatOutput($route, $output, $style);
                }
            }
        }
        return 0;
    }

    /**
     * Format route output to command line.
     *
     * @param Route  $route
     * @param Output $output
     * @param        $type
     */
    public function formatOutput(Route $route, Output $output, $type = self::STYLE_DETAIL)
    {
        switch ($type) {
            case self::STYLE_DETAIL:
                $output->write('Route [');
                $output->write('<success>"' . $route->getName() . '"</success>');
                $output->writeln(']');
                $output->writeln("Path:\t\t" . str_replace('//', '/', $route->getPath()));
                $output->writeln("Method:\t\t" . $route->getMethod());
                $output->writeln("Format:\t\t" . implode(', ', $route->getFormats()));
                $output->writeln("Callback:\t" . (is_callable($route->getCallback()) ? 'Closure' : $route->getCallback()));
                $output->writeln("Defaults:\t" . implode(', ', $route->getDefaults()));
                $output->writeln("Requirements:\t" . implode(', ', $route->getRequirements()));
                $output->writeln("Path-Regex:\t" . $route->getPathRegex());
                $output->writeln('');
                break;
            case self::STYLE_LIST:
            default:
                $name = $route->getName();
                $method = $route->getMethod();
                $schema = $route->getScheme() ?? 'http';
                $path = $route->getPath();
                $output->write($name . str_repeat(' ', 25 - strlen($name)));
                $output->write($method . str_repeat(' ', 15 - strlen($method)));
                $output->write($schema . str_repeat(' ', 15 - strlen($schema)));
                $output->writeln($path);
        }

        return;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return '获取所有路由列表, 获取通过 --bundle 选项指定';
    }
}