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

    /**
     * @param OutputInterface $output
     * @return int
     */
    protected function createApiDoc(OutputInterface $output)
    {
        $appName = config()->get('name');
        $dir = app()->getAppPath() . '/src';
        $indexHtml = app()->getAppPath() . "/web/{$appName}.html";
        $apiDataFile = app()->getAppPath() . "/web/{$appName}.json";

        $output->writeln(sprintf('Scanning dir: <info>%s</info>', $dir));

        $docs = \Swagger\scan($dir);

        $output->writeln(sprintf('Save api json data: <info>%s</info>', $apiDataFile));

        file_put_contents($apiDataFile, $docs);

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>{$appName} API Document</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<redoc spec-url='{$appName}.json'></redoc>
<script src="https://rebilly.github.io/ReDoc/releases/latest/redoc.min.js"> </script>
</body>
</html>
HTML;

        if (!file_exists($indexHtml)) {
            file_put_contents($indexHtml, $html);
            $output->writeln(sprintf('Save html: <info>%s</info>', $indexHtml));
        } else {
            $output->writeln(sprintf('Html <info>%s</info> is already exists.', $indexHtml));
        }

        return 0;
    }
}