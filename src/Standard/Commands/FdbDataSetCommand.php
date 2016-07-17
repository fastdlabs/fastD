<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/12/24
 * Time: 下午7:01
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

/**
 * Class OrmRevertCommand
 *
 * @package FastD\Framework\Bundle\Commands
 */
class FdbDataSetCommand extends FdbSchemaCommand
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'fdb:data:set';
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
                $output->writeln(sprintf('Register "%s" data set. <success>[OK]</success>', $name));
            }
        }

        $loader->runDataSet();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return '数据填充';
    }
}