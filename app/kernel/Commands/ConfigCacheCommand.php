<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/18
 * Time: 下午6:06
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Framework\Bundle\Commands;

use Dobee\Console\Commands\Command;
use Dobee\Console\Format\Input;
use Dobee\Console\Format\Output;

class ConfigCacheCommand extends Command
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'config:caching';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {
        $this->setDescription('Thank for you use Config caching tool.');
    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        $output->writeln('Start caching application configuration.');

        $app = $this->getProvider();

        $config = $app->getContainer()->get('kernel.config');

        $path = $app->getRootPath() . DIRECTORY_SEPARATOR . $config->getCacheName();

        file_put_contents($path, '<?php ' . PHP_EOL . 'return ' . $config . ';');

        $output->writeln('Configuration caching successful. ', Output::STYLE_SUCCESS);

        $output->writeln('The configuration caching path in: ' . $app->getRootPath() . DIRECTORY_SEPARATOR . $config->getCacheName(), Output::STYLE_SUCCESS);
    }
}