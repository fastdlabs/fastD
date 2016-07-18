<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/29
 * Time: 下午4:21
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Commands;

use FastD\Config\Config;
use FastD\Console\Input\Input;
use FastD\Console\Output\Output;
use FastD\Framework\Kernel\AppKernel;
use FastD\Standard\Commands\CommandAware;

class ConfigCacheCommand extends CommandAware
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