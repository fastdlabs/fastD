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

class ControllerCreate extends Command
{
    public function configure()
    {
        $this->setName('controller:create');

        $this->addArgument('name', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $controllerPath = app()->getPath() . '/src/Controller';
        if (!file_exists($controllerPath)) {
            mkdir($controllerPath, 0755, true);
        }

        $name = ucfirst($input->getArgument('name')) . 'Controller';
        $content = $this->createControllerTemplate($name);

        $controllerFile = $controllerPath . '/' . $name . '.php';

        if (file_exists($controllerFile)) {
            throw new \LogicException(sprintf('Controller %s is already exists', $name));
        }

        file_put_contents($controllerFile, $content);
        $output->writeln(sprintf('Controller %s created successful. path in %s', $name, $controllerPath));
    }

    public function createControllerTemplate($name)
    {
        return <<<CONTROLLER
<?php

namespace Controller;


use FastD\Http\Response;
use FastD\Http\ServerRequest;

class {$name}
{
    public function create(ServerRequest \$request)
    {
        return json([]);
    }

    public function patch(ServerRequest \$request)
    {
        return json([]);
    }

    public function delete(ServerRequest \$request)
    {
        return json([]);
    }

    public function find(ServerRequest \$request)
    {
        return json([]);
    }

    public function select(ServerRequest \$request)
    {
        return json([]);
    }
}
CONTROLLER;
    }
}