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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Document
 * @package FastD\Console
 */
class Document extends Command
{
    public function configure()
    {
        $this
            ->setName('doc')
            ->setDescription('Scan and create api.json into Swagger documentation.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->createApiDoc($output);
    }

    protected function createApiDoc(OutputInterface $output)
    {
        $dir = app()->getAppPath() . '/src/Http/Controller';
        $index = app()->getAppPath() . '/web/api.html';
        $apiDataFile = app()->getAppPath() . '/web/api.json';

        $output->writeln(sprintf('Scanning dir: <info>%s</info>', $dir));

        $docs = \Swagger\scan($dir);

        $output->writeln(sprintf('Save api json data: <info>%s</info>', $apiDataFile));

        file_put_contents($apiDataFile, $docs);

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>API Document</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<redoc spec-url='./api.json'></redoc>
<script src="https://rebilly.github.io/ReDoc/releases/latest/redoc.min.js"> </script>
</body>
</html>
HTML;

        file_put_contents($index, $html);
        $output->writeln(sprintf('Save html: <info>%s</info>', $index));
        return 0;
    }
}