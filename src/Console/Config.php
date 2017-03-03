<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Console;


use FastD\Utils\FileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Config
 * @package FastD\Console
 */
class Config extends Command
{
    public function configure()
    {
        $this->setName('config:dump');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $rows = [];
        $table->setHeaders(array('File', 'Config', 'Owner', 'Modify At',));

        foreach (glob(app()->getPath() . '/config/*') as $file) {
            $file = new FileObject($file);
            $rows[] = [
                $file->getFilename(),
                count(array_keys(load($file->getPathname()))) . ' Keys',
                posix_getpwuid($file->getOwner())['name'],
                date('Y-m-d H:i:s', $file->getMTime()),
            ];
        }

        $table->setRows($rows);
        $table->render();
    }
}