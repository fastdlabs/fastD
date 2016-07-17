<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/1/6
 * Time: 下午2:18
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Standard\Commands;

use FastD\Console\Input\Input;
use FastD\Console\Output\Output;
use FastD\Database\Fixtures\FixtureInterface;
use FastD\Database\Fixtures\FixtureLoader;
use Symfony\Component\Finder\Finder;

/**
 * Class OrmUpdateCommand
 *
 * @package FastD\Framework\Bundle\Commands
 */
class FdbSchemaCommand extends CommandAware
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'fdb:schema';
    }

    /**
     * @return void
     */
    public function configure()
    {
        $this
            ->setArgument('connection')
            ->setOption('bundle')
        ;
    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return int
     */
    public function execute(Input $input, Output $output)
    {
        $connection = $input->getArgument('connection');

        if (empty($connection)) {
            $connection = 'read';
        }

        $bundle = $input->getOption('bundle');

        $loader = new FixtureLoader($this->getDriver($connection));

        $fixtures = $this->scanFixtures($bundle);

        foreach ($fixtures as $name => $fixture) {
            $fixture = new $fixture;
            if ($fixture instanceof FixtureInterface) {
                $loader->registerFixture($fixture);
                $output->writeln(sprintf('Register "%s" schema. <success>[OK]</success>', $name));
            }
        }

        $loader->runSchema();
    }

    /**
     * @param string $bundle
     * @return array
     */
    protected function scanFixtures($bundle = null)
    {
        $finder = new Finder();

        $fixtures = [];

        if (null !== $bundle) {
            $bundle = $this->getContainer()->singleton('kernel')->getBundle($bundle);

            $path = $bundle->getRootPath() . '/Fixtures';
            $files = $finder->in($path)->name('*Fixture.php')->files();
            foreach ($files as $file) {
                $fixtures[] = $bundle->getNamespace() . '\\Fixtures\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }

            return $fixtures;
        }

        $bundles = $this->getContainer()->singleton('kernel')->getBundles();

        foreach ($bundles as $bundle) {
            $path = $bundle->getRootPath() . '/Fixtures';
            if (!file_exists($path)) {
                continue;
            }
            $files = $finder->in($path)->name('*Fixture.php')->files();
            foreach ($files as $file) {
                $fixtures[$bundle->getName()] = $bundle->getNamespace() . '\\Fixtures\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }
        }

        return $fixtures;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return '创建数据库结构并建立数据表';
    }
}