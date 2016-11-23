<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Commands;

use FastD\Bundle\Console\ConsoleAware;
use FastD\Config\Config;
use FastD\Console\Input\Input;
use FastD\Console\Output\Output;

class ConfigCacheCommand extends ConsoleAware
{
    const CACHE_NAME = 'config.cache';

    /**
     * @return string
     */
    public function getName()
    {
        return 'config:cache';
    }

    /**
     * @return void
     */
    public function configure()
    {
        // TODO: Implement configure() method.
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return int
     */
    public function execute(Input $input, Output $output)
    {
        $kernel = $this->getContainer()->get('kernel');

        $config = new Config();

        $prod = $kernel->getRootPath() . '/config/config_prod.php';

        if (file_exists($prod)) {
            $config->load($prod);
        }

        foreach ($kernel->getBundles() as $bundle) {
            $bundle->registerConfiguration($config, AppKernel::ENV_PROD);
        }

        $caching = $kernel->getRootPath() . DIRECTORY_SEPARATOR . ConfigCacheCommand::CACHE_NAME;

        file_put_contents($caching, '<?php return ' . var_export($config->all(), true) . ';');

        $output->writeln('Caching to ' . $caching . '...... <success>[OK]</success>');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return '生成全局配置缓存, 在生产环境下必须执行此命令';
    }
}